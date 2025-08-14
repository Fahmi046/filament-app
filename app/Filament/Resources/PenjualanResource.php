<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use App\Models\PenjualanModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class PenjualanResource extends Resource
{
    protected static ?string $model = PenjualanModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static ?string $navigationGroup = 'Faktur';

    public static ?string $label = 'Laporan Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->Label('Tanggal')
                    ->date('d F Y'),
                TextColumn::make('kode')
                    ->sortable()
                    ->searchable()
                    ->Label('Kode Faktur'),
                TextColumn::make('jumlah')
                    ->sortable()
                    ->searchable()
                    ->Label('Jumlah'),
                TextColumn::make('customer.nama_customer')
                    ->sortable()
                    ->searchable()
                    ->Label('Nama Customer'),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->Label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        '0' => 'warning',
                        '1' => 'info',
                    })
                    ->formatStateUsing(fn(PenjualanModel $record): string => $record->state == 0 ? 'Belum Lunas' : 'Lunas'),
            ])
            ->emptyStateHeading('Tidak ada data penjualan')
            ->emptyStateDescription('Silakan buat faktur penjualan untuk melihat laporan penjualan.')
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Buat Faktur')
                    ->url(fn() => route('filament.admin.resources.fakturs.create'))
                    ->icon('heroicon-o-plus')
                    ->button(),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
