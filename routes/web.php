<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Authentification
Auth::routes();


// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes Utilisateurs
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:user'])->group(function () {
        // Tableau de bord utilisateur
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::post('/user/solidarite', [UserController::class, 'contributeSolidarite'])->name('user.contributeSolidarite');

        // Épargne
        Route::get('/transactions/epargne', [TransactionController::class, 'viewEpargne'])->name('transactions.epargne');
        Route::post('/transactions/epargne', [TransactionController::class, 'storeEpargne'])->name('transactions.epargne.store');
        
        // Crédits
        Route::get('/transactions/credit', [TransactionController::class, 'viewCredit'])->name('transactions.credit');
        Route::post('/transactions/credit', [TransactionController::class, 'createAndStoreCredit'])->name('transactions.credit.create');
        Route::post('/credit/pay/{id}', [TransactionController::class, 'payCredit'])->name('credit.pay');
        
        // Cotisations de solidarité
        Route::get('/transactions/solidarite', [TransactionController::class, 'viewSolidarite'])->name('transactions.solidarite');
        Route::post('/transactions/solidarite', [TransactionController::class, 'storeSolidarite'])->name('transactions.solidarite.store');

        // Amendes
        Route::get('/transactions/amende', [TransactionController::class, 'viewAmende'])->name('transactions.amende');
        Route::post('/transactions/amende/pay/{id}', [TransactionController::class, 'payAmende'])->name('transactions.amende.pay');
        
        Route::get('/transaction/graphique', [UserController::class, 'dashboard'])->name('transaction.graphique');
        Route::get('/user/credits_priority', [TransactionController::class, 'viewCreditsPriority'])->name('user.credits.priority');

    });

    /*
    |--------------------------------------------------------------------------
    | Routes Administrateurs
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        // Tableau de bord administrateur
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::match(['get', 'post'], '/admin/setting', [AdminController::class, 'settings'])->name('admin.setting');


        // Gestion des utilisateurs
        Route::get('/admin/users', [AdminController::class, 'viewUsers'])->name('admin.users');
        Route::post('/admin/users/promote', [AdminController::class, 'promoteUser'])->name('admin.users.promote');
        
        //Gestion des credits(approbation/rejet)
        Route::post('/admin/credit/{id}/manage', [AdminController::class, 'manageCredit'])->name('admin.credit.manage');
        Route::get('/admin/credit', [AdminController::class, 'listCredits'])->name('admin.credit'); // Liste des crédits

        // Gestion des amendes
        Route::get('/admin/amende', [AdminController::class, 'viewAmendes'])->name('admin.amendes');
        Route::post('/admin/amende', [AdminController::class, 'createAmende'])->name('admin.createAmende');

        // Route pour la gestion des solidarités par l'admin
        Route::get('/admin/solidarite', [AdminController::class, 'viewSolidarites'])->name('admin.solidarites');

        Route::get('/admin/graphique', [AdminController::class, 'showGraphique'])->name('admin.graphique');
    });
});
