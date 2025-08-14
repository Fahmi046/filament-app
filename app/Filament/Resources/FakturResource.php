<?php

namespace App\Filament\Resources;

use \App\Models\Customer;
use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\CustomerModel;
use App\Models\Faktur;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;

use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use function Laravel\Prompts\select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FakturResource extends Resource
{
    protected static ?string $model = Faktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Faktur';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_faktur')
                    ->columnSpan(2),
                DatePicker::make('tanggal_faktur')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ]),
                Select::make('customer_id')
                    ->reactive()
                    ->relationship('customer', 'nama_customer')
                    ->columnSpan([
                        'default' => 2,
                        'md' => 1,
                        'lg' => 1,
                        'xl' => 1,
                    ])
                    ->afterStateUpdated(function (callable $set, $state) {
                        $customer = CustomerModel::find($state);
                        if ($customer) {
                            $set('kode_customer', $customer->kode_customer);
                        } else {
                            $set('kode_customer', null);
                        }
                    }),
                TextInput::make('kode_customer')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan(2),
                Repeater::make('details')
                    ->relationship()
                    ->schema([
                        select::make('barang_id')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $barang = \App\Models\Barang::find($state);
                                if ($barang) {
                                    $set('nama_barang', $barang->nama_barang);
                                    $set('harga', $barang->harga_barang);
                                } else {
                                    $set('nama_barang', null);
                                    $set('harga', 0);
                                }
                            })
                            ->relationship('barang', 'nama_barang')->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 2,
                            ]),
                        TextInput::make('nama_barang')
                            ->disabled()
                            ->dehydrated()
                            ->label('Nama Barang')->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make('harga')
                            ->disabled()
                            ->dehydrated()
                            ->prefix('Rp ')
                            ->default(0)
                            ->label('Harga')
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make('qty')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, $get) {
                                $tampungharga = $get('harga');
                                $set('hasil_qty', intval($state * $tampungharga));
                            })
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make('hasil_qty')
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->label('Hasil Qty')->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make('diskon')
                            ->label('Diskon (%)')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $hasil_qty = $get('hasil_qty');
                                $diskon = $hasil_qty * ($state / 100);
                                $hasil = $hasil_qty - $diskon;
                                $set('subtotal', intval($hasil));
                            })
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->default(0)
                            ->label('Sub Total')
                            ->columnSpan([
                                'default' => 2,
                                'md' => 1,
                                'lg' => 1,
                                'xl' => 1,
                            ]),
                    ])
                    ->live()
                    ->columnSpan(2),
                Textarea::make('ket_faktur')
                    ->columnSpan(2),
                TextInput::make('total')
                    ->columnSpan([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 1,
                    ])
                    ->placeholder(function (Set $set, Get $get) {
                        $detail = collect($get('details'))->pluck('subtotal')->sum();
                        if ($detail == null) {
                            $set('total', 0);
                        } else {
                            $set('total', $detail);
                        }
                    }),
                TextInput::make('nominal_charge')
                    ->columnSpan([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 1,
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $total = $get('total');
                        $charge = $total * ($state / 100);
                        $hasil = $total + $charge;

                        $set('total_final', $hasil);
                        $set('charge', $charge);
                    }),
                TextInput::make('charge')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan(2),
                TextInput::make('total_final')
                    ->disabled()
                    ->dehydrated()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_faktur'),
                TextColumn::make('tanggal_faktur'),
                TextColumn::make('kode_customer'),
                TextColumn::make('customer.nama_customer')
                    ->label('Customer'),
                TextColumn::make('ket_faktur'),
                TextColumn::make('total')
                    ->formatStateUsing(fn(faktur $record): string => 'Rp' . number_format($record->total, 0, '.', '.')),
                TextColumn::make('nominal_charge'),
                TextColumn::make('charge'),
                TextColumn::make('total_final')
                    ->formatStateUsing(fn(faktur $record): string => 'Rp' . number_format($record->total_final, 0, '.', '.')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
