<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\Epargne;
use App\Models\Credit;
use App\Models\Amende;
use App\Models\Solidarite;


class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     */
    public function dashboard()
    {
        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'total_epargne' => Epargne::sum('amount'),
            'total_credits' => Credit::sum('requested_amount'),
            'credits_pending' => Credit::where('status', 'Pending')->sum('requested_amount'),
            'credits_repaid' => Credit::where('status', 'Repaid')->sum('requested_amount'),
            'total_amendes' => Amende::sum('amount'),
            'amendes_unpaid' => Amende::where('status', 'unpaid')->sum('amount'), // Correctement récupéré
            'total_solidarite' => Solidarite::sum('amount'),
        ];


        return view('admin.dashboard', compact('stats'));
    }

    public function showGraphique()
    {
        // Préparation des données pour le graphique
        $chartData = [
            'labels' => ['Épargne', 'Crédits', 'Amendes', 'Solidarité'],
            'datasets' => [
                [
                    'label' => 'Montant Total',
                    'data' => [
                        Epargne::sum('amount'),          // Total des épargnes
                        Credit::sum('requested_amount'), // Total des crédits demandés
                        Amende::sum('amount'),           // Total des amendes
                        Solidarite::sum('amount')        // Total des solidarités
                    ],
                    'backgroundColor' => ['#4CAF50', '#FFC107', '#F44336', '#00BCD4'],
                ],
            ],
        ];

        // Retourner la vue avec les données du graphique
        return view('admin.graphique', compact('chartData'));
    }

    public function listCredits()
    {
        // Récupérer tous les crédits avec leurs utilisateurs associés
        $credits = Credit::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.credit', compact('credits'));
    }

    public function manageCredit(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject', // Action obligatoire : approuver ou rejeter
        ]);

        $credit = Credit::findOrFail($id);

        if ($credit->status !== 'Pending') {
            return redirect()->back()->with('error', 'Ce crédit ne peut pas être géré car il n\'est pas en attente.');
        }

        if ($request->action === 'approve') {
            $credit->update([
                'status' => 'Approved',
                'approval_status' => 'Approuvé',
                'approved_date' => now(),
            ]);

            return redirect()->back()->with('success', 'Le crédit a été approuvé avec succès.');
        }

        if ($request->action === 'reject') {
            $credit->update([
                'status' => 'Rejected',
                'approval_status' => 'Rejeté',
                'rejected_date' => now(),
            ]);
        
            \Log::info('Crédit rejeté : ', ['id' => $credit->id, 'admin' => auth()->user()->id]);
        
            return redirect()->back()->with('success', 'Le crédit a été rejeté avec succès.');
        }
    }


    public function viewAmendes()
    {
        $users = User::all(); // Récupérer tous les utilisateurs pour le formulaire
        $amendes = Amende::with('user')->get(); // Inclure les relations utilisateur

        return view('admin.amende', compact('users', 'amendes'));
    }

    public function createAmende(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
        ]);

        // Récupérer le coût de l'amende depuis les paramètres globaux
        $amendeCost = Setting::where('key_name', 'amende')->value('value') ?? 500;

        // Création de l'amende avec le montant défini
        Amende::create([
            'user_id' => $request->user_id,
            'amount' => $amendeCost, // Utilisation du montant global
            'reason' => $request->reason,
            'status' => 'unpaid',
            'date' => now(),
        ]);

        return redirect()->route('admin.createAmende')->with('success', 'Amende ajoutée avec succès.');
    }

    // AdminController.php
    public function viewSolidarites()
        {
            $solidarites = Solidarite::with('user')->latest()->get();
            return view('admin.solidarite', compact('solidarites'));
        }

    /**
     * Met à jour les paramètres globaux (coût des parts, taux d'intérêt, montant des amendes).
     */
    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validation des données du formulaire
            $request->validate([
                'cost_per_part' => 'required|numeric|min:1',
                'interest_rate' => 'required|numeric|min:0',
                'amende_cost' => 'required|numeric|min:0',
                'solidarite_cost' => 'required|numeric|min:0',
            ]);

            // Mise à jour ou création des paramètres
            $settingsData = $request->only(['cost_per_part', 'interest_rate', 'amende_cost', 'solidarite_cost']);
            foreach ($settingsData as $key => $value) {
                Setting::updateOrCreate(
                    ['key_name' => $key],
                    ['value' => $value]
                );
            }

            return redirect()->route('admin.setting')->with('success', 'Paramètres mis à jour avec succès.');
        }

        // Récupération des paramètres pour affichage
        $settings = Setting::all();

        return view('admin.setting', compact('settings'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Afficher la liste des utilisateurs
    public function viewUsers()
    {
        // Récupérer tous les utilisateurs
        $users = User::orderBy('created_at', 'desc')->get();

        // Retourner la vue avec les utilisateurs
        return view('admin.user', compact('users'));
    }
}
