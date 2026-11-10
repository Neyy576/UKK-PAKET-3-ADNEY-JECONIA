<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'novel' => 'Novel',
                        'komik' => 'Komik',
                        'majalah' => 'Majalah',
                        'pelajaran' => 'Buku Pelajaran',
                    ])
                    ->required(),
                TextInput::make('author')
                    ->label('Penulis')
                    ->required(),
                TextInput::make('publisher')
                    ->label('Penerbit')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->required(),
                TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->required(),
            ]);
    }
}