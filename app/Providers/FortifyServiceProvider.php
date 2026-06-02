<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom logout response - redirect ke landing page
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ===== VIEW REGISTRATIONS =====

        // Login view
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Register view (uncomment jika menggunakan registration)
        // Fortify::registerView(function () {
        //     return view('auth.register');
        // });

        // Forgot password view
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // Reset password view
        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        // Verify email view (jika menggunakan email verification)
        // Fortify::verifyEmailView(function () {
        //     return view('auth.verify-email');
        // });

        // Confirm password view (untuk protected routes)
        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });

        // Two factor challenge view (jika menggunakan 2FA)
        // Fortify::twoFactorChallengeView(function () {
        //     return view('auth.two-factor-challenge');
        // });

        // ===== AUTHENTICATION LOGIC =====

        // Custom authentication - support login dengan email atau username
        Fortify::authenticateUsing(function (Request $request) {
            // Cari user berdasarkan email atau username
            $user = User::where('email', $request->email)
                ->first();

            // Validasi password dan status user
            if ($user && Hash::check($request->password, $user->password)) {
                // Optional: Check jika user aktif
                // if (!$user->is_active) {
                //     return null;
                // }

                return $user;
            }

            return null;
        });

        // ===== ACTION BINDINGS =====

        // Uncomment jika menggunakan registration
        // Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // ===== RATE LIMITING =====

        // Rate limit untuk login attempts (5 attempts per menit)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );

            return Limit::perMinute(5)->by($throttleKey);
        });

        // Rate limit untuk two-factor authentication
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
