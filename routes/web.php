<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

use App\Http\Controllers\{
    HelpdeskController,
    NewsController,
    TagsController,
    CategoryController,
    HomeController,
    FaqController,
    SurveyController,
    AnnouncementController,
    ProfileController,
    WistleBlowingController,
    Auth\LogoutController
};
use App\Http\Controllers\LanguageController;

    Route::get('/set-locale/{lang}', [LanguageController::class, 'switchLang'])->name('set-locale');
    
    
    /*
    |--------------------------------------------------------------------------
    | WEB ROUTES — USER + FILAMENT ADMIN
    |--------------------------------------------------------------------------
    | - User login: /login (Fortify)
    | - Filament admin login: /admin/login
    | - Locale bisa diubah via /set-locale/{lang}
    | - Logout user & admin tidak bentrok
    */
    
    
    // =====================================
    // 🏠 HALAMAN LANDINGPAGE (Guest)
    // =====================================
    Route::get('/', fn() => view('landingpage'))->name('landingpage');
    Route::get('/loginpage', fn() => view('loginpage'))->name('loginpage');
    
    
    
    // =====================================
    // 🔒 ROUTE HANYA UNTUK USER LOGIN
    // =====================================
    Route::middleware(['auth'])->group(function () {
    
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements/{id}/mark-read', [AnnouncementController::class, 'markAsRead'])->name('announcements.mark-read');
    
        // Ganti bahasa manual (fallback)
        Route::get('/language/{locale}', function ($locale) {
            if (in_array($locale, ['en', 'id'])) {
                session(['locale' => $locale]);
                App::setLocale($locale);
            }
            return redirect()->back();
        })->name('language.switch');
    
        // Halaman utama setelah login
        Route::get('/home', [HomeController::class, 'index'])->name('home');
    
        // Video unggulan
        Route::get('/featured-video', [NewsController::class, 'featuredvideo'])->name('featured.video');
    
        // Edit profil user
        // Route::get('/edit-profile', fn() => view('livewire.edit-profile'))->name('edit-profile');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('edit-profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // FAQ
        Route::controller(FaqController::class)->group(function () {
            Route::get('/faq', 'index')->name('faq');
            Route::get('/faq/search', 'search')->name('faq.search');
        });
    
        // Helpdesk
        Route::controller(HelpdeskController::class)->group(function () {
            Route::get('/helpdesk', 'index')->name('helpdesk.index');
            Route::post('/helpdesk', 'store')->name('helpdesk.store');
        });
    
        // News
        Route::prefix('news')->controller(NewsController::class)->group(function () {
            Route::get('/', 'index')->name('news.index');
            Route::get('/search', 'search')->name('news.search');
            Route::get('/{slug}', 'show')->name('news.show');
            Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('news.category.show');
            Route::get('/tags/{slug}', [TagsController::class, 'index'])->name('news.tags.index');
        });
        
        Route::get('/popup-image/{filename}', function ($filename) {
            $path = storage_path('app/public/popup-news/' . $filename);
            if (!file_exists($path)) abort(404);
            return response()->file($path);
        })->name('popup.image');
    
        // Survei & Saran
        Route::get('/surveidansaran', [SurveyController::class, 'index'])->name('surveidansaran');
    
        Route::prefix('whistle-blowing')->controller(WistleBlowingController::class)->group(function () {
            Route::get('/', 'index')->name('whistle-blowing.index');
            Route::post('/', 'store')->name('whistle-blowing.store');
            Route::get('/storage-link', function() {
                Artisan::call('storage:link');
                return Artisan::output();
            });
            Route::get('/{id}/download-zip', 'downloadZip')
                ->name('wistle-blowing.download-zip');
        });
    
        // Logout custom controller
        Route::post('/logout', LogoutController::class)
            ->middleware('auth')
            ->name('logout');
    });
    Route::get('/reset-password/{token}', function (Request $request, $token) {
        return view('auth.reset-password', [
            'request' => $request,
            'token' => $token,
        ]);
    })->middleware('guest')->name('password.reset');
