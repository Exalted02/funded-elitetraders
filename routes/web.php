<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailManagementController;
use App\Http\Controllers\EmailSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChangePasswordController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ChallengesController;
use App\Http\Controllers\PayoutsController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CertificateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('clear-cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:cache');
    \Artisan::call('optimize:clear');
	Log::info('Clear all cache');
    dd("Cache is cleared");
});
Route::get('db-migrate', function () {
    \Artisan::call('migrate');
    dd("Database migrated");
});
Route::get('db-seed', function () {
    \Artisan::call('db:seed');
    dd("Database seeded");
});
Route::get('/', [ProfileController::class, 'welcome']);
Route::get('/run-script', [ProfileController::class, 'run_script']);
Route::get('/trading-journal-table', [ProfileController::class, 'getTradingJournalTable']);

Route::get('lang/home', [LangController::class, 'index']);
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');

Route::post('/delete-kyc', [VerificationController::class, 'delete_kyc_doc'])->name('client.delete-kyc');
//Client
Route::middleware(['auth', 'client'])->name('client.')->group(function () {
	//Dashboard
	Route::get('/dashboard-challenge', [DashboardController::class, 'dashboard_challenge'])->name('dashboard-challenge');
	Route::get('/dashboard/{id}', [DashboardController::class, 'index'])->name('dashboard');
	
	//Account
	Route::get('/account', [DashboardController::class, 'account'])->name('account');
	Route::post('/account-data', [DashboardController::class, 'account_data'])->name('account-data');
		
	//Verification
	Route::get('/verification', [VerificationController::class, 'index'])->name('verification');
	Route::post('/verification', [VerificationController::class, 'save'])->name('client.verification'); 
	
	//Route::post('/delete-kyc', [VerificationController::class, 'delete_kyc_doc'])->name('client.delete-kyc');
		
	//Withdraw
	Route::name('withdraw.')->group(function () {
		Route::get('/withdraw', [DashboardController::class, 'withdraw'])->name('index');
		Route::post('/withdraw-request-amount', [DashboardController::class, 'withdraw_request_amount'])->name('withdraw-request-amount');
		Route::post('/withdraw-submit', [DashboardController::class, 'withdraw_submit'])->name('withdraw-submit');
	});
	
	
	Route::post('/update-client-account', [DashboardController::class, 'update_client_account'])->name('updateaccount');
});
Route::middleware(['auth', 'client'])->group(function () {
	//Payouts
	Route::name('payouts.')->group(function () {
		Route::get('/payout', [PayoutsController::class, 'random_payout_details'])->name('random-payout-details');
		Route::get('/payouts/data', [PayoutsController::class, 'getRandomPayoutData'])->name('data');
	});
});
//Admin	
Route::get('/certificate/{id}', [CertificateController::class, 'viewCertificate'])->name('certificate.view');
Route::middleware(['auth', 'admin'])->group(function () {
	//User-Accounts
	Route::name('users.')->group(function () {
		Route::get('/users', [UserController::class, 'index'])->name('index');
		Route::get('/users/data', [UserController::class, 'getUsers'])->name('data');
		
		Route::post('/user-update-data', [UserController::class, 'update_data'])->name('user-update-data');
		Route::post('/user-data-submit', [UserController::class, 'submit_data'])->name('user-data-submit');
		Route::post('/user-update-status', [UserController::class, 'update_status'])->name('user-update-status');
		Route::post('/get_delete_data', [UserController::class, 'get_delete_data'])->name('get_delete_data');
		Route::post('/final_delete_submit', [UserController::class, 'final_delete_submit'])->name('final_delete_submit');

		Route::post('/users/adjust-balance', [UserController::class, 'adjust_balance'])->name('adjust-balance'); //now no work
		Route::post('/users/multi-adjust-balance', [UserController::class, 'multi_adjust_balance'])->name('multi-adjust-balance'); //now no work
		
		Route::post('/user-allow-withdraw', [UserController::class, 'allow_withdraw'])->name('user-allow-withdraw');
		Route::post('/multi-allow-withdraw-submit', [UserController::class, 'multi_allow_withdraw_submit'])->name('multi-allow-withdraw-submit');
		
		Route::post('/multi-send-user-email', [UserController::class, 'multi_send_user_email'])->name('multi-send-user-email');
	});
	
	
	
	//Challenges
	Route::name('challenges.')->group(function () {
		// Route::get('/challenges', [ChallengesController::class, 'index'])->name('index');
		// Route::post('/challenges', [ChallengesController::class, 'index'])->name('index');
		
		Route::get('/challenges', [ChallengesController::class, 'index'])->name('index');
		Route::get('/challenges/data', [ChallengesController::class, 'getChallenges'])->name('data');


		Route::post('/challenges/check-email', [ChallengesController::class, 'check_email'])->name('check-email');
		Route::post('/challenges/trader-challenge-amount', [ChallengesController::class, 'trader_challenge_amount'])->name('trader-challenge-amount');
		Route::post('/challenges/challenge-submit', [ChallengesController::class, 'challenge_submit'])->name('challenge-submit');
		Route::post('/challenges/challenge-import-submit', [ChallengesController::class, 'challenge_import_submit'])->name('challenge-import-submit');
		Route::post('/challenges/challenge-details', [ChallengesController::class, 'challenge_details'])->name('challenge-details');
		
		Route::post('/challenge-update-status', [ChallengesController::class, 'update_status'])->name('challenge-update-status');
		
		//For adjust balance
		Route::post('/challenge-ajax-details', [ChallengesController::class, 'challenge_ajax_details'])->name('challenge-ajax-details');
		Route::post('/adjust-balance', [ChallengesController::class, 'adjust_balance'])->name('adjust-balance');
		Route::post('/multi-adjust-balance', [ChallengesController::class, 'multi_adjust_balance'])->name('multi-adjust-balance');
	});
	
	//Certificate
	Route::name('certificate.')->group(function () {
		Route::get('/certificates', [CertificateController::class, 'index'])->name('index');
		Route::get('/certificates/data', [CertificateController::class, 'getCertificate'])->name('data');
		
		Route::post('/certificate/certificate-submit', [CertificateController::class, 'certificate_submit'])->name('certificate-submit');
		Route::post('/certificate-update-data', [CertificateController::class, 'update_data'])->name('certificate-update-data');
		
		Route::post('/get_certificate_delete_data', [CertificateController::class, 'get_delete_data'])->name('get_delete_data');
		Route::post('/final_certificate_delete_submit', [CertificateController::class, 'final_delete_submit'])->name('final_delete_submit');
	});
	
	//Payouts
	Route::name('payouts.')->group(function () {
		Route::get('/payouts', [PayoutsController::class, 'index'])->name('index');
		Route::get('/payouts/payoutdata', [PayoutsController::class, 'getPayoutsData'])->name('payoutdata');
		
		Route::post('/payouts-update-status', [PayoutsController::class, 'update_status'])->name('payouts-update-status');
		Route::post('/payouts-multi-update-status', [PayoutsController::class, 'multi_update_status'])->name('payouts-multi-update-status');
		Route::post('/payouts-details', [PayoutsController::class, 'payout_details'])->name('payout-details');
		
		/*Route::get('/payout', [PayoutsController::class, 'random_payout_details'])->name('random-payout-details');
		Route::get('/payouts/data', [PayoutsController::class, 'getRandomPayoutData'])->name('data');*/
	});
	
	//Kyc
	Route::get('/kyc', [KycController::class, 'index'])->name('kyc');
	Route::get('/kyc/data', [KycController::class, 'getKycData'])->name('kyc.data');
	
	Route::post('/kyc-document', [KycController::class, 'kyc_document'])->name('kyc-document');
	Route::post('/kyc-doc-status-update', [KycController::class, 'kyc_document_status_update'])->name('kyc-doc-status-update');
	Route::post('/multi-kyc-doc-status-update', [KycController::class, 'multi_kyc_document_status_update'])->name('multi-kyc-doc-status-update');
	
	//ChangePassword
	Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password');
	Route::post('/change-password', [ChangePasswordController::class, 'save_data'])->name('change-password-save');

	//EmailSettings
	Route::get('/email-settings', [EmailSettingsController::class, 'index'])->name('user.email-settings');
	Route::post('/email-settings', [EmailSettingsController::class, 'save_data'])->name('email-settings-save');

	// Email Management Routes
	Route::get('email-management', [EmailManagementController::class,'index'])->name('email-management');
	Route::get('/email-management-edit/{id}', [EmailManagementController::class, 'email_management_edit'])->name('email-management-edit');
	Route::post('/email-management-edit-save',[EmailManagementController::class,'manage_email_management_process'])->name('email-management-edit-save');
	
	Route::get('/admin/impersonate/{id}', [UserController::class, 'impersonateUser'])->name('admin.impersonate');
	
	//Route::get('/back', [UserController::class, 'back_to_admin'])->name('users.back');
});

Route::middleware(['auth'])->group(function () {
	
    Route::get('/back', [UserController::class, 'back_to_admin'])->name('users.back');
	
	Route::post('/change-multi-status',[CommonController::class,'change_multi_status'])->name('change-multi-status');
});

require __DIR__.'/auth.php';
