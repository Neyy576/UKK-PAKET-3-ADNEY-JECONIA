<?php

namespace App\Filament\Resources\Books\Tables;

use App\Models\Loan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('author')->label('Penulis')->searchable(),
                TextColumn::make('publisher')->label('Penerbit')->searchable(),
                TextColumn::make('year')->label('Tahun')->numeric()->sortable(),
                TextColumn::make('stock')->label('Stok')->numeric()->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('pinjam')
                    ->label('Pinjam')
                    ->icon('heroicon-o-book-open')
                    ->requiresConfirmation()
                    ->visible(fn () => auth()->user() && ! auth()->user()->isAdmin())
                    ->disabled(fn ($record) => $record && $record->stock < 1)
                    ->action(function ($record) {
                        if (! $record) {
                            return;
                        }

                        $aktif = Loan::where('user_id', auth()->id())
                            ->where('status', 'dipinjam')
                            ->count();

                        if ($aktif >= 3) {
                            Notification::make()->title('Maksimal pinjam 3 buku')->danger()->send();
                            return;
                        }

                        if ($record->stock < 1) {
                            Notification::make()->title('Stok buku habis')->danger()->send();
                            return;
                        }

                        DB::transaction(function () use ($record) {
                            Loan::create([
                                'user_id' => auth()->id(),
                                'book_id' => $record->id,
                                'loan_date' => now(),
                                'due_date' => now()->addDays(7),
                                'status' => 'dipinjam',
                            ]);

                            $record->decrement('stock');
                        });

                        Notification::make()->title('Buku berhasil dipinjam')->success()->send();
                    }),

                EditAction::make()
                    ->visible(fn () => auth()->user()?->isAdmin() ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isAdmin() ?? false),
                ]),
            ]);
    }
}
