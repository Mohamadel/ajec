<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Epargne;
use App\Models\Credit;
use App\Models\Solidarite;
use App\Models\Amende;
use App\Models\Setting;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function dashboard()
    {
        $solidarites = Solidarite::where('user_id', auth()->id())->get();
        $amendes = Amende::where('user_id', auth()->id())->get();
        $solidariteCost = Setting::where('key_name', 'solidarite_cost')->first()->value ?? 0;

        return view('user.dashboard', compact('solidarites', 'amendes'));
    }

    public function viewEpargne()
    {
        $epargnes = Epargne::where('user_id', auth()->id())->get();
        $totalEpargne = $epargnes->sum('amount'); // Calculer le total dans le contrôleur

        return view('transactions.epargne', compact('epargnes', 'totalEpargne'));
    }

    public function storeEpargne(Request $request)
    {
        $request->validate(['parts' => 'required|integer|min:1']);
        $user = auth()->user();

        $costPerPart = Setting::where('key_name', 'cost_per_part')->first()->value ?? 1000; // Valeur par défaut
        $total = $request->parts * $costPerPart;

        Epargne::create([
            'user_id' => $user->id,
            'parts' => $request->parts,
            'amount' => $total,
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Épargne effectuée avec succès.');
    }

    // Afficher les crédits et le montant maximum possible
    public function viewCredit()
    {
        $user = auth()->user();

        $credits = Credit::where('user_id', $user->id)->get();
        $totalEpargne = Epargne::where('user_id', $user->id)->sum('amount') ?? 0;
        $maxCreditAmount = $totalEpargne * 3;

        // Vérifiez si l'utilisateur a des crédits non remboursés
        $unpaidCredits = Credit::where('user_id', $user->id)
            ->where('payment_status', '!=', 'Paid')
            ->exists();

        return view('transactions.credit', compact('credits', 'totalEpargne', 'maxCreditAmount', 'unpaidCredits'));
    }

    public function viewCreditsPriority()
    {
        // Récupérer tous les utilisateurs avec leurs crédits
        $users = User::with(['credits' => function ($query) {
            $query->orderBy('created_at', 'asc'); // Les crédits sont triés par date de création
        }])->get();

        // Préparer les données des priorités pour chaque utilisateur
        $usersCredits = $users->map(function ($user) {
            $totalCredits = $user->credits->sum('approved_amount'); // Montant total approuvé
            $totalPaid = $user->credits->sum('amount_paid'); // Montant total payé
            $remaining = $totalCredits - $totalPaid; // Montant restant à rembourser

            // Priorité basée sur les crédits non approuvés
            $pendingCredits = $user->credits->where('status', 'Pending')->count();

            return [
                'name' => $user->name,
                'email' => $user->email,
                'total_credits' => $totalCredits,
                'total_paid' => $totalPaid,
                'remaining' => $remaining,
                'pending_credits' => $pendingCredits,
            ];
        });

        // Trier par priorité (les utilisateurs avec des crédits en attente en premier, triés par montant restant)
        $usersCredits = $usersCredits->sortByDesc(function ($user) {
            return [$user['pending_credits'], $user['remaining']];
        });

        return view('user.credits_priority', compact('usersCredits'));
    }


    public function createAndStoreCredit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'duration_days' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $totalEpargne = Epargne::sum('amount'); // Total des épargnes de tous les utilisateurs
        $totalCreditsUsed = Credit::where('payment_status', '!=', 'Paid')->sum('approved_amount') - Credit::sum('amount_paid'); // Montants encore en circulation
        $availableBalance = $totalEpargne - $totalCreditsUsed; // Solde disponible dans la caisse

        if ($availableBalance <= 0) {
            return redirect()->back()->with('error', 'La caisse est vide. Aucun crédit ne peut être approuvé pour le moment.');
        }

        if ($request->amount > $availableBalance) {
            return redirect()->back()->with('error', 'Le montant demandé dépasse le solde disponible dans la caisse.');
        }

        $userEpargne = Epargne::where('user_id', $user->id)->sum('amount'); // Épargne de l'utilisateur
        if ($request->amount > $userEpargne * 3) {
            return redirect()->back()->with('error', 'Le montant demandé dépasse trois fois votre épargne.');
        }

        $interestRate = Setting::where('key_name', 'interest_rate')->value('value') ?? 10;

        // Créez le crédit
        Credit::create([
            'user_id' => $user->id,
            'requested_amount' => $request->amount,
            'approved_amount' => $request->amount,
            'amount_paid' => 0,
            'interest_rate' => $interestRate,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'date_borrowed' => null,
            'date_due' => now()->addDays($request->duration_days),
        ]);

        return redirect()->route('transactions.credit')->with('success', 'Votre demande de crédit a été soumise avec succès.');
    }


    public function payCredit(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();

        $credit = Credit::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'Approved') // Le crédit doit être approuvé
            ->first();

        if (!$credit) {
            return redirect()->back()->with('error', 'Crédit introuvable ou non éligible au paiement.');
        }

        $remainingAmount = $credit->approved_amount - $credit->amount_paid;

        if ($remainingAmount <= 0) {
            return redirect()->back()->with('info', 'Ce crédit est déjà entièrement payé.');
        }

        if ($request->amount > $remainingAmount) {
            return redirect()->back()->with('error', 'Le montant payé dépasse le solde restant.');
        }

        // Mettre à jour le montant payé
        $credit->increment('amount_paid', $request->amount);

        // Mettre à jour le statut de paiement
        if ($credit->amount_paid >= $credit->approved_amount) {
            $credit->update([
                'payment_status' => 'Paid',
                'date_paid' => now(),
            ]);
        } else {
            $credit->update([
                'payment_status' => 'Partial', // Paiement partiel
                'date_borrowed' => now(), // Correct
            ]);
        }

        return redirect()->route('transactions.credit')->with('success', 'Paiement effectué avec succès.');
    }

    


    public function viewSolidarite()
    {
        // Récupérer les cotisations de solidarité de l'utilisateur connecté
        $solidarites = Solidarite::where('user_id', auth()->id())->get();

        // Retourner la vue avec les données
        return view('transactions.solidarite', compact('solidarites'));
    }

    public function storeSolidarite(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100', // Exemple : montant minimum
        ]);

        // Enregistrement de la cotisation
        Solidarite::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'date' => now(),
            'status' => 'Paid',
        ]);

        return redirect()->route('transactions.solidarite')->with('success', 'Cotisation enregistrée avec succès.');
    }

    public function viewAmende()
    {
        // Récupérer les amendes de l'utilisateur connecté
        $amendes = Amende::where('user_id', auth()->id())->get();

        // Retourner la vue avec les amendes
        return view('transactions.amende', compact('amendes'));
    }

    public function payAmende($id)
    {
        $amende = Amende::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$amende) {
            return redirect()->route('transactions.amende')->with('error', 'Amende introuvable ou non autorisée.');
        }

        if ($amende->status === 'paid') {
            return redirect()->route('transactions.amende')->with('info', 'Cette amende est déjà payée.');
        }

        // Marquer l'amende comme payée
        $amende->update(['status' => 'paid']);

        return redirect()->route('transactions.amende')->with('success', 'Amende payée avec succès.');
    }


}

