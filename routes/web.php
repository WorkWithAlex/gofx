<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\StrategiesController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\LibraryController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/refund-and-cancellation-policy', [HomeController::class, 'refundCancellation'])->name('refund-cancellation');

/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/
Route::get('/contact', [ContactUsController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactUsController::class, 'submitContactForm'])
    ->middleware('throttle:10,1')
    ->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Courses
|--------------------------------------------------------------------------
*/
Route::get('/courses', [CoursesController::class, 'index'])->name('courses.list');
Route::get('/enroll', [CoursesController::class, 'index'])->name('courses.index');

Route::get('/courses/{slug}', [CoursesController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('courses.show');

/*
|--------------------------------------------------------------------------
| Checkout + Payments (PayU)
|--------------------------------------------------------------------------
|
| Notes:
| - /checkout is POST only (modal submits here)
| - redirect view auto-submits to PayU
| - success/failure must accept BOTH GET and POST
| - notify_url is POST only (server-to-server)
|--------------------------------------------------------------------------
*/

Route::post('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');

Route::get('/checkout/redirect', [CheckoutController::class, 'redirectToPayu'])
    ->name('checkout.redirect');

// PayU response endpoints
Route::match(['get', 'post'], '/payment/success', [CheckoutController::class, 'success'])
    ->name('payment.success');

Route::match(['get', 'post'], '/payment/failure', [CheckoutController::class, 'failure'])
    ->name('payment.failure');

// PayU notify_url (server-to-server) — MUST be POST only
Route::post('/payment/notify', [CheckoutController::class, 'notify'])
    ->name('payment.notify');



// Articles
Route::get('/articles', [ArticlesController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticlesController::class, 'show'])->where('slug','[a-z0-9\-]+')->name('articles.show');

// Guides
Route::get('/guides', [GuidesController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuidesController::class, 'show'])->where('slug','[a-z0-9\-]+')->name('guides.show');

// Strategies
Route::get('/strategies', [StrategiesController::class, 'index'])->name('strategies.index');
Route::get('/strategies/{slug}', [StrategiesController::class, 'show'])->where('slug','[a-z0-9\-]+')->name('strategies.show');

// Tools
Route::get('/tools', [ToolsController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool}', [ToolsController::class, 'show'])->where('tool','[a-z0-9\-]+')->name('tools.show');

// Position Size calculator API (server-side verify)
Route::post('/tools/position-size-calc', [ToolsController::class, 'calculatePositionSize'])->name('tools.position_size.calc');

// Risk/Reward calculator API (server-side verify)
Route::post('/tools/risk-reward-calc', [ToolsController::class, 'calculateRiskReward'])->name('tools.risk_reward.calc');

// Pip Value calculator API (server-side verify)
Route::post('/tools/pip-value-calc', [App\Http\Controllers\ToolsController::class, 'calculatePipValue'])->name('tools.pip_value.calc');

// ATR calculator API (server-side verify)
Route::post('/tools/atr-calc', [App\Http\Controllers\ToolsController::class, 'calculateAtr'])->name('tools.atr.calc');

// Compounding calculator API (server-side verify)
Route::post('/tools/compounding-calc', [App\Http\Controllers\ToolsController::class, 'calculateCompounding'])->name('tools.compounding.calc');

// Library
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

// Community
use App\Http\Controllers\CommunityController;
Route::get('/community/whatsapp-room', [CommunityController::class,'whatsappRoom'])->name('community.whatsapp');
Route::get('/community/prime', [CommunityController::class,'prime'])->name('community.prime');
Route::get('/community/success-wall', [CommunityController::class,'successWall'])->name('community.success');
