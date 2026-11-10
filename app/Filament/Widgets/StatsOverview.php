<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    // Statistik hanya bisa dilihat admin
    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Anggota', User::where('role', 'siswa')->count())
                ->description('Total anggota terdaftar')
                ->color('info'),

            Stat::make('Jumlah Buku', Book::count())
                ->description('Total koleksi buku')
                ->color('success'),

            Stat::make('Buku Sedang Dipinjam', Loan::where('status', 'dipinjam')->count())
                ->description('Buku belum dikembalikan')
                ->color('warning'),
        ];
    }
}
