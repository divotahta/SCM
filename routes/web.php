<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Owner\PurchaseController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\PurchaseReportController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EoqCalculatorController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::put('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report');

    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos');
    Route::get('/pos/search-products', [PosController::class, 'searchProducts'])->name('pos.search-products');
    Route::get('/pos/customers', [PosController::class, 'getCustomers'])->name('pos.customers');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/process-transaction', [PosController::class, 'processTransaction'])->name('pos.process-transaction');
    Route::get('/pos/receipt/{id}', [PosController::class, 'printReceipt'])->name('pos.receipt');
    Route::post('/pos/void/{id}', [PosController::class, 'voidTransaction'])->name('pos.void');
    Route::get('/pos/status/{id}', [PosController::class, 'getTransactionStatus'])->name('pos.status');

    // Transaction Management
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
    Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export.pdf');
    Route::get('/transactions/counts', [TransactionController::class, 'getTransactionCounts'])->name('transactions.counts');
    Route::get('/transactions/print/{transaction}', [TransactionController::class, 'printStruk'])->name('transactions.print');

    // Stock Management
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/history', [StockController::class, 'history'])->name('stocks.history');
    Route::get('/stocks/forecast', [StockController::class, 'forecast'])->name('stocks.forecast');
    Route::post('/stocks/{product}/adjust', [StockController::class, 'adjust'])->name('stocks.adjust');
    Route::get('stocks/{product}/barcode', [StockController::class, 'generateBarcode'])->name('stocks.barcode');
    Route::get('stocks/export', [StockController::class, 'exportReport'])->name('stocks.export');

    // Product Management
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products/broadcast', [ProductController::class, 'broadcast'])->name('products.broadcast');
    Route::get('products/check-code', [ProductController::class, 'checkCode'])->name('products.check-code');
    Route::resource('products', ProductController::class);

    // Customer Management
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/broadcast', [CustomerController::class, 'broadcast'])->name('customers.broadcast');

    // Purchase Routes
    Route::get('/purchases', [AdminPurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [AdminPurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [AdminPurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [AdminPurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}/edit', [AdminPurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('/purchases/{purchase}', [AdminPurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/{purchase}', [AdminPurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::post('/purchases/import', [AdminPurchaseController::class, 'import'])->name('purchases.import');
    Route::get('/purchases/export', [AdminPurchaseController::class, 'export'])->name('purchases.export');
    Route::post('/purchases/broadcast', [AdminPurchaseController::class, 'broadcast'])->name('purchases.broadcast');
    Route::post('/purchases/{purchase}/approve', [AdminPurchaseController::class, 'approve'])->name('purchases.approve');
    Route::post('/purchases/{purchase}/reject', [AdminPurchaseController::class, 'reject'])->name('purchases.reject');
    Route::post('/purchases/{purchase}/receive', [AdminPurchaseController::class, 'receive'])->name('purchases.receive');


    // Supplier Management
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import');
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::post('suppliers/broadcast', [SupplierController::class, 'broadcast'])->name('suppliers.broadcast');

    // EOQ Calculator Routes
    Route::get('/stocks/eoq-calculator', [EoqCalculatorController::class, 'index'])->name('stocks.eoq.calculator');
    Route::post('/stocks/eoq-calculate', [EoqCalculatorController::class, 'calculate'])->name('stocks.eoq.calculate');

    // Ganti dengan route baru
    Route::get('/eoq-calculator', [EoqCalculatorController::class, 'index'])->name('eoq.calculator');
    Route::post('/eoq-calculate', [EoqCalculatorController::class, 'calculate'])->name('eoq.calculate');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/list', [NotificationController::class, 'getNotifications'])->name('notifications.list');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/admin/notifications', [NotificationController::class, 'getNotifications'])->name('admin.notifications');
});

// Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Purchase Approval Routes
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::post('/purchases/{purchase}/approve', [PurchaseController::class, 'approve'])->name('purchases.approve');
    Route::post('/purchases/{purchase}/reject', [PurchaseController::class, 'reject'])->name('purchases.reject');
});

// Purchase Report Routes
Route::middleware(['auth', 'role:admin,owner'])->group(function () {
    Route::get('/reports/purchases', [PurchaseReportController::class, 'index'])->name('admin.reports.purchases.index');
    Route::get('/reports/purchases/export/pdf', [PurchaseReportController::class, 'exportPdf'])->name('admin.reports.purchases.export.pdf');
    Route::get('/reports/purchases/export/excel', [PurchaseReportController::class, 'exportExcel'])->name('admin.reports.purchases.export.excel');
});

require __DIR__.'/auth.php';
