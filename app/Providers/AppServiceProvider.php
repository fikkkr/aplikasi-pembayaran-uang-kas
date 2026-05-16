<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admin mah bebas ngapain aja
        Gate::before(function (User $user) {
            if ($user->level === 'admin') {
                return true;
            }
        });

        // --- HAK AKSES DATA MURID ---
        // Siapa yang boleh LIHAT halaman murid? (Guru & Bendahara boleh)
        Gate::define('lihat-murid', function (User $user) {
            return in_array($user->level, ['guru', 'bendahara']);
        });
        // Siapa yang boleh CRUD murid? (Hanya Guru)
        Gate::define('kelola-murid', function (User $user) {
            return $user->level === 'guru';
        });

        // --- HAK AKSES TRANSAKSI KAS ---
        // Siapa yang boleh LIHAT kas mingguan? (Guru & Bendahara boleh)
        Gate::define('lihat-kas', function (User $user) {
            return in_array($user->level, ['guru', 'bendahara']);
        });
        // Siapa yang boleh CRUD transaksi kas/periode/pengeluaran? (Hanya Bendahara)
        Gate::define('kelola-kas', function (User $user) {
            return $user->level === 'bendahara';
        });
    }
}
