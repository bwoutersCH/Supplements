<?php

use App\Http\Controllers\Admin\ActiveController;
use App\Http\Controllers\Admin\ConversionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoseController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\GoalController as AdminGoalController;
use App\Http\Controllers\Admin\SponsoredEntryController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SupplementController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemapIndex']);
Route::get('/sitemap-{lang}.xml', [SeoController::class, 'sitemapLang']);

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/actives', [ActiveController::class, 'index'])->name('admin.actives');
    Route::get('/forms', [FormController::class, 'index'])->name('admin.forms');
    Route::get('/goals', [AdminGoalController::class, 'index'])->name('admin.goals');
    Route::get('/doses', [DoseController::class, 'index'])->name('admin.doses');
    Route::get('/conversions', [ConversionController::class, 'index'])->name('admin.conversions');
    Route::get('/sponsored', [SponsoredEntryController::class, 'index'])->name('admin.sponsored');
});

Route::group([
    'prefix' => '{lang}',
    'where' => ['lang' => 'nl|en|de'],
    'middleware' => ['setLocale'],
], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/supplements', [SupplementController::class, 'index'])->name('supplements.index');
    Route::get('/supplement/{activeSlug}', [SupplementController::class, 'show'])->name('supplements.show');
    Route::get('/compare/{activeSlug}', [CompareController::class, 'show'])->name('compare.show');
    Route::get('/products/{productKey}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/goals/{goalSlug}', [GoalController::class, 'show'])->name('goals.show');

    Route::get('/about', [StaticPageController::class, 'about'])->name('about');
    Route::get('/disclaimer', [StaticPageController::class, 'disclaimer'])->name('disclaimer');
    Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('privacy');
    Route::get('/how-we-compare', [StaticPageController::class, 'howWeCompare'])->name('how-we-compare');
});
