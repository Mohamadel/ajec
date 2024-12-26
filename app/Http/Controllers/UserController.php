<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Solidarite;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $data = [
            'total_epargne' => $user->epargnes->sum('amount'),
            'total_credit_pending' => $user->credits->where('status', 'Pending')->sum('amount'),
            'total_credit_repaid' => $user->credits->where('status', 'Repaid')->sum('amount'),
            'total_amendes' => $user->amendes->sum('amount'),
            'total_solidarite' => $user->solidarites->sum('amount'),
        ];

        return view('user.dashboard', compact('data'));
    }

    public function contributeSolidarite()
    {
        $solidariteCost = Setting::where('key_name', 'solidarite_cost')->first()->value;

        Solidarite::create([
            'user_id' => auth()->id(),
            'amount' => $solidariteCost,
            'date' => now(),
        ]);

        return redirect()->back()->with('success', 'Contribution de solidarité effectuée.');
    }

    public function globalView(Request $request)
    {
        // Récupérer les filtres
        $sortField = $request->get('sort_field', 'name'); // Par défaut, tri par nom
        $sortOrder = $request->get('sort_order', 'asc');  // Ordre ascendant par défaut
        $search = $request->get('search', null);

        // Construire la requête
        $query = User::with(['epargnes', 'solidarites', 'credits', 'amendes']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Appliquer le tri
        $users = $query->get()->sortBy(function ($user) use ($sortField) {
            if ($sortField === 'total_epargne') {
                return $user->epargnes->sum('total_amount');
            } elseif ($sortField === 'total_cotisation') {
                return $user->cotisations->sum('amount');
            } elseif ($sortField === 'total_credit') {
                return $user->credits->where('status', 'Pending')->sum('amount');
            } elseif ($sortField === 'total_amende') {
                return $user->amendes->sum('amount');
            }
            return $user->$sortField;
        }, $sortOrder === 'asc' ? SORT_REGULAR : SORT_DESC);

        return view('user.global', compact('users', 'sortField', 'sortOrder', 'search'));
    }
}
