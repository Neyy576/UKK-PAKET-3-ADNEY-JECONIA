<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\Loan;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Anggota')
                    ->searchable(),

                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable(),

                TextColumn::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->date(),

                TextColumn::make('due_date')
                    ->label('Batas Pengembalian')
                    ->date(),

                TextColumn::make('returned_at')
                    ->label('Tanggal Dikembalikan')
                    ->date(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === 'dipinjam' ? 'warning' : 'success'
                    ),

                TextColumn::make('fine')
                    ->label('Denda')
                    ->numeric(),
            ])
            ->recordActions([
                Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Pengembalian Buku')
                    ->modalDescription('Apakah kamu yakin ingin mengembalikan buku ini?')
                    ->visible(fn (Loan $record): bool =>
                        auth()->user()?->role === 'siswa'
                        && $record->status === 'dipinjam'
                    )
                    ->action(function (Loan $record): void {

                        DB::transaction(function () use ($record) {

                            $loan = Loan::whereKey($record->id)
                                ->lockForUpdate()
                                ->first();

                            if (! $loan || $loan->status !== 'dipinjam') {
                                return;
                            }

                            $loan->update([
                                'status' => 'dikembalikan',
                                'returned_at' => now()->toDateString(),
                            ]);

                            $loan->book()->increment('stock');
                        });

                        Notification::make()
                            ->title('Buku berhasil dikembalikan')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
