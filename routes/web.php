<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoicePDFController;
use App\Http\Controllers\MailsController;
use App\Http\Livewire\AccountsReceivable\AccountsReceivableComponent;
use App\Http\Livewire\Buyer\BuyerComponent;
use App\Http\Livewire\CashRegister\CashMovementComponent;
use App\Http\Livewire\CashRegister\CashRegisterComponent;
use App\Http\Livewire\Category\CategoriesComponent;
use App\Http\Livewire\Cellar\CellarProcessedComponent;
use App\Http\Livewire\Cellar\CellarUnprocessedComponent;
use App\Http\Livewire\Charts\ChartsComponent;
use App\Http\Livewire\ClassProduct\ClassProductComponent;
use App\Http\Livewire\Client\ClientComponent;
use App\Http\Livewire\Client\ShowClientComponent;
use App\Http\Livewire\Count\CountsComponent;
use App\Http\Livewire\Credits\CreditComponent;
use App\Http\Livewire\Dashboard\DashboardComponent;
use App\Http\Livewire\DebtsPays\DebtsPaysComponent;
use App\Http\Livewire\Discount\DiscountComponent;
use App\Http\Livewire\Document\DocumentsComponent;
use App\Http\Livewire\Dropbox\DropboxComponent;
use App\Http\Livewire\Exoneration\ExonerationComponent;
use App\Http\Livewire\Expense\ExpensesComponent;
use App\Http\Livewire\Family\FamiliesComponent;
use App\Http\Livewire\Inventory\InventoryAjustmentComponent;
use App\Http\Livewire\Paybill\PaybillComponent;
use App\Http\Livewire\Payment\PaymentComponent;
use App\Http\Livewire\PaymentInvoice\PaymentInvoiceComponent;
use App\Http\Livewire\Product\DiaryBookComponent;
use App\Http\Livewire\Inventory\InventoryComponent;
use App\Http\Livewire\Lot\LotsComponent;
use App\Http\Livewire\PayMethodInformation\PayMethodsInformationComponent;
use App\Http\Livewire\Plan\PlansComponent;
use App\Http\Livewire\PriceList\PriceListsComponent;
use App\Http\Livewire\Product\ProductsComponent;
use App\Http\Livewire\Provider\ProvidersComponent;
use App\Http\Livewire\Referred\ReferredComponent;
use App\Http\Livewire\Report\DetailReportDocumentsComponent;
use App\Http\Livewire\Report\DetailReportExpensesComponent;
use App\Http\Livewire\Report\ExpensesResumeComponent;
use App\Http\Livewire\Report\InventoryReportComponent;
use App\Http\Livewire\Report\IVA\IvaComponent;
use App\Http\Livewire\Report\IVA\IvaDetailsComponent;
use App\Http\Livewire\Report\ResumeReportDocumentsComponent;
use App\Http\Livewire\Report\ResumeReportExpensesComponent;
use App\Http\Livewire\Ridivi\RidiviComponent;
use App\Http\Livewire\Seller\SellerComponent;
use App\Http\Livewire\SystemPay\ListSystemPaysComponent;
use App\Http\Livewire\SystemPay\SystemPayComponent;
use App\Http\Livewire\SystemPayAdmin\SystemPayAdminComponent;
use App\Http\Livewire\SystemPayAdmin\ClientSystemComponent;
use App\Http\Livewire\Tax\TaxComponent;
use App\Http\Livewire\Transfer\TransferComponent;
use App\Http\Livewire\Tutorial\TutorialsComponent;
use App\Http\Livewire\Voucher\PendingElectronicReceiptComponent;
use App\Http\Livewire\Voucher\VouchersComponent;
use App\Http\Livewire\Zone\ZonesComponent;
use App\Models\Team;
use Laravel\Jetstream\Http\Controllers\Livewire\TeamController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('principal', ["clients" => Team::all()->count() * 30]);
});

Route::middleware(['auth:sanctum', 'verified', 'team'])->get('/users/{team}', [Controller::class, 'showUser'])->name('teams.showUser');
Route::middleware(['auth:sanctum', 'verified', 'team'])->get('/configuracion/{team}', [TeamController::class, 'show'])->name('configuracion');

Route::group(['middleware' => ['auth:sanctum', 'verified', 'team', 'dataCompany', 'pay']], function () {
    Route::get('/cxp/{id_provider}', DebtsPaysComponent::class)->name("cxpProvider");
    Route::get('/cxp', DebtsPaysComponent::class)->name("cxp");
    Route::get('/cxc/{id_client}', AccountsReceivableComponent::class)->name("cxcClient");
    Route::get('/cxc', AccountsReceivableComponent::class)->name("cxc");
    Route::get('/categories', CategoriesComponent::class)->name("categories");
    Route::get('/zones', ZonesComponent::class)->name("zones");
    Route::get('/lots', LotsComponent::class)->name("lots");
    Route::get('/families', FamiliesComponent::class)->name("families");
    Route::get('/products', ProductsComponent::class)->name("products");
    Route::get('/inventory', InventoryComponent::class)->name("inventory");
    Route::get('/inventoryAjustment', InventoryAjustmentComponent::class)->name("inventoryAjustment");
    Route::get('/providers', ProvidersComponent::class)->name("providers");
    Route::get('/discounts', DiscountComponent::class)->name("discounts");
    Route::get('/exonerations', ExonerationComponent::class)->name("exonerations");
    Route::get('/taxes', TaxComponent::class)->name("taxes");
    Route::get('/counts', CountsComponent::class)->name("counts");
    Route::get('/pricelists', PriceListsComponent::class)->name("pricelists");
    Route::get('/pricelists/{list}', PriceListsComponent::class);
    Route::get('/vouchers', VouchersComponent::class)->name("vouchers");
    Route::get('/expenses', ExpensesComponent::class)->name("expenses");
    Route::get('/plans', PlansComponent::class)->name("plans");
    Route::get('/payMethodsInformation', PayMethodsInformationComponent::class)->name("payMethodsInformation");
    Route::get('/clients', ClientComponent::class)->name("clients");
    Route::get('/clients/{id_client}', ShowClientComponent::class);
    Route::get('/classproducts', ClassProductComponent::class)->name("classproducts");
    Route::get('/payments', PaymentComponent::class)->name("payments");
    Route::get('/paymentsinvoices', PaymentInvoiceComponent::class)->name("paymentsInvoices");
    Route::get('/documents', DocumentsComponent::class)->name("documents");
    Route::get('documentPDF/{clave}', [InvoicePDFController::class, 'generatePDF'])->name('documentPDF');
    Route::get('ticketPDF/{id}', [DocumentsComponent::class, 'ticketPDF'])->name('ticketPDF');

    //Route::get('/product.import', [ImportController::class, 'importProducts'])->name('product.import');
    Route::get('/pendingElectronicReceipt', PendingElectronicReceiptComponent::class)->name("pendingElectronicReceipt");
    Route::get('/expensesDetail', DetailReportExpensesComponent::class)->name("expensesDetail");
    Route::get('/expensesResume', ResumeReportExpensesComponent::class)->name("expensesResume");
    Route::get('/documentsDetail', DetailReportDocumentsComponent::class)->name("documentsDetail");
    Route::get('/documentsResume', ResumeReportDocumentsComponent::class)->name("documentsResume");
    Route::get('/sellers', SellerComponent::class)->name("sellers");
    Route::get('/buyers', BuyerComponent::class)->name("buyers");
    Route::get('/credits', CreditComponent::class)->name("credits");
    Route::get('/paybills', PaybillComponent::class)->name("paybills");
    Route::get('/transfers', TransferComponent::class)->name("transfers");
    Route::get('/bank', RidiviComponent::class)->name("bank");
    Route::get('/bankSettings', RidiviComponent::class)->name('bankSettings');
    Route::get('/charts', ChartsComponent::class)->name("charts");

    Route::get('/expensesResume', ExpensesResumeComponent::class)->name("expensesResume");
    //bodega
    Route::get('/cellarUnprocessed', CellarUnprocessedComponent::class)->name("cellarUnprocessed");
    Route::get('/cellarProcessed', CellarProcessedComponent::class)->name("cellarProcessed");
    Route::get('ticketCeller/{id}', [InvoicePDFController::class, 'ticketCeller'])->name('ticketCeller');
    Route::get('ticketCellerProcessed/{id}', [InvoicePDFController::class, 'ticketCellerProcessed'])->name('ticketCellerProcessed');


    Route::get('/referrals', ReferredComponent::class)->name("referrals");
    Route::get('/myReferrals', ReferredComponent::class)->name("myReferrals");
    Route::get('/myPays', ReferredComponent::class)->name("myPays");
    //Reportes
    Route::get('/iva', IvaComponent::class)->name("iva");
    Route::get('/ivaDetail', IvaDetailsComponent::class)->name("ivaDetail");
    Route::get('/diaryBook', DiaryBookComponent::class)->name("diaryBook");
    Route::get('/cashRegister', CashRegisterComponent::class)->name("cashRegister");
    Route::get('/cashMovement', CashMovementComponent::class)->name("cashMovement");

    Route::get('/downloadReportCXC/{client}', [AccountsReceivableComponent::class, 'downloadReportCXC'])->name("downloadReportCXC");

    Route::get('/listPays', ListSystemPaysComponent::class)->name("listPays");
    Route::get('/tutorials', TutorialsComponent::class)->name("categoriesTutorials");
    Route::get('/tutorials/{category}', TutorialsComponent::class)->name("tutorials");
    Route::get('/dashboard', DashboardComponent::class)->name("dashboard");
    Route::get('/adminPays', SystemPayAdminComponent::class)->name("adminPays");
    Route::get('/clientSystem', ClientSystemComponent::class)->name("clientSystem");
});



Route::middleware(['auth:sanctum', 'verified', 'team'])->get('/listPays', ListSystemPaysComponent::class)->name("listPays");
//referidos 
Route::get('/referredby/{code}', [ReferredComponent::class, 'referredby'])->name("referredby");
Route::get('/membership/{plan?}/{referred?}', SystemPayComponent::class)->name("membership");
//correos
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/mails', [MailsController::class, 'chargeMails'])->name('mails');
