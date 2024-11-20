<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BuyerProductController;
use App\Http\Controllers\VisitorProductController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//User Authentication
//Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
//   Route::post('/login', [AuthController::class, 'login']);
//    Route::post('/register', [AuthController::class, 'register']);
//    Route::post('/logout', [AuthController::class, 'logout']);
//});




// Public/Visitor's Access
Route::prefix('visitor-products')->controller(VisitorProductController::class)->group(function() {
    Route::get('/preshow', 'products');
    Route::get('/showByCategory/{category}', 'showByCategory');
    Route::get('/search', 'search');
});


//Authenticated Seller Routes
Route::group(['middleware' => 'sellerAuth','prefix'=>'seller-products'], function($router){
    Route::controller(ProductController::class)->group(function(){

    Route::get('/listedProducts','products');
    Route::get('/pendingProducts','getPendingProducts');
    Route::get('/show/{product_id}','show');
    Route::post('/store','store');
    Route::post('/update/{product_id}','update');
    Route::put('/approveProduct/{product_id}','approveProduct');
    Route::delete('/destroy/{product_id}','destroy');
    Route::get('/search', 'search');
    Route::get('/showByCategory/{category}', 'showByCategory');
    });
});

//Authenticated Buyer Routes
Route::group(['middleware' => 'buyerAuth','prefix'=>'buyer-products'], function($router){
    Route::controller(BuyerProductController::class)->group(function(){
      
    Route::get('/all','buyerListedProducts');      
    Route::get('/search', 'search');
    Route::get('/sortby', 'sortProducts');
    Route::get('/showByCategory/{category}', 'showByCategory');
    });
});

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$request_method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Base path for the API routes
$base_path = '/E-commerce/api.php';


// Register Route
if ($request_uri === $base_path . '/register' && $request_method === 'POST') {
    require __DIR__ . '/controllers/registerUser.php'; 
    exit();
}

// Login Route
if ($request_uri === $base_path . '/login' && $request_method === 'POST') {
    require __DIR__ . '/controllers/loginUser.php'; 
    exit();
}

// Logout Route
if ($request_uri === $base_path . '/logout' && $request_method === 'POST') {
    require __DIR__ . '/controllers/logoutUser.php';
    exit();
}


// Profile Update Route
if ($request_uri === $base_path . '/update/user' && $request_method === 'POST') {
    require __DIR__ . '/controllers/updateUser.php';
    exit();
}

// Verify Email
if ($request_uri === $base_path. '/verify/email' && $request_method === 'POST') {
    require __DIR__. '/verification/verifyEmail.php';
    exit();
}

// Get User Profile Route
if ($request_uri === $base_path . '/user/profile' && $request_method === 'GET') {
    require __DIR__ . '/controllers/getUserProfile.php';
    exit();
}


// If no route matches, return 404S
http_response_code(404);
echo json_encode(['message' => 'Endpoint not found']);




