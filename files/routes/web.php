<?php
use Inertia\Inertia;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Stevebauman\Location\Facades\Location;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FoodMenuController;
use App\Http\Controllers\LocalAreaController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\BranchSelectionController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\SslCommerzPaymentController;

Route::get('track', function (Request $request) {
    $location = Location::get('5.162.104.224');
});

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/privacy', function () {
    return Inertia::render('Privacy');
})->name('privacy');

Route::get('/terms', function () {
    return Inertia::render('Terms');
})->name('terms');

Route::get('/sso-login', function (Request $request) {
    // Step 1: Check expiration
    if ($request->expires < now()->timestamp) {
        abort(403, 'Link expired');
    }

    // Step 2: Verify signature
    $originalUrl = $request->url(); // without query
    $query = http_build_query([
        'phone' => $request->phone,
        'name' => $request->name,
        'password' => $request->password,
        'expires' => $request->expires,
    ]);

    $expectedSignature = hash_hmac('sha256', $originalUrl . '?' . $query, config('app.key'));

    if (!hash_equals($expectedSignature, $request->signature)) {
        abort(403, 'Invalid signature');
    }

    // Step 3: Create or login user
    $user = \App\Models\User::firstOrCreate(
        ['phone' => $request->phone],
        ['name' => $request->name ?? 'Your Name', 'password' => $request->password]
    );

    Auth::login($user);

    return redirect('/');
});


Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function (Request $request) {
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');
        $query = Branch::query()->active();
        if ($userLat && $userLng) {
            $query->withDistance($userLat, $userLng)
                ->orderBy('distance');
        } else {
            $query->orderBy('name');
        }
        $locals = DB::table('r_search')->select('id', 'name', 'division_id', 'district_id', 'own_id')->get();

        $branches = $query->get();
    return Inertia::render('Home', [
         'branches' => $branches,
            'locals' => $locals,
            'userLocation' => [
                'latitude' => $userLat ? (float)$userLat : null,
                'longitude' => $userLng ? (float)$userLng : null
            ]
    ]);
})->name('home');


Route::middleware(['auth', 'not-customer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'not-customer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{user}', [UserController::class, 'delete'])->name('user.destroy');
    Route::resource('categories', CategoryController::class);
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])
        ->name('categories.update-order');
    Route::resource('branches', BranchController::class);
    Route::resource('locals', LocalAreaController::class);
    Route::resource('foods', FoodController::class);

    // orders.update-payment-status

    Route::patch('order/update-status/{order}', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');

    Route::get('branch_food/{branch_id}', [FoodController::class, 'foodByBranch'])->name('food_by_branch');

    Route::get('package', [PackageController::class, 'index'])->name('package.index');
    Route::delete('package/delete/{package}', [PackageController::class, 'destroy'])->name('package.destroy');
    Route::get('package/create', [PackageController::class, 'create'])->name('package.create');
    Route::post('package/create', [PackageController::class, 'store']);

    Route::get('package/edit/{package}', [PackageController::class, 'edit'])->name('package.edit');
    Route::put('package/update{package}', [PackageController::class, 'update'])->name('package.update');
    Route::get('/dashboard', function(){ return Inertia::render('Dashboard'); })->name('dashboard');
    Route::get('area/{city_id}', [BranchController::class, 'get_area'])->name('areas.selected');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

// SSLCOMMERZ Start 01313050944 01403909989
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout'])->name('checkout');
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);


Route::get('/locations', [BranchSelectionController::class, 'index'])
    ->name('customer.locations');
Route::get('/branch/{branch}/menu', [FoodMenuController::class, 'index'])
    ->name('customer.branch.menu');

Route::middleware(['auth'])->group(function () {

    Route::get('/checkout/{branch}', [CheckoutController::class, 'checkout'])
        ->name('customer.checkout');
    Route::post('/orders', [CheckoutController::class, 'store'])->name('customer.orders.store');
    Route::post('/addresses', [CheckoutController::class, 'storeAddress'])
        ->name('customer.addresses.store');
    Route::put('/addresses/{address}', [CheckoutController::class, 'updateAddress'])
        ->name('customer.addresses.update');
    Route::delete('/addresses/{address}', [CheckoutController::class, 'deleteAddress'])
        ->name('customer.addresses.delete');
    Route::post('/addresses/{address}/default', [CheckoutController::class, 'setDefaultAddress'])
        ->name('customer.addresses.setDefault');


    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});

require __DIR__ . '/auth.php';
