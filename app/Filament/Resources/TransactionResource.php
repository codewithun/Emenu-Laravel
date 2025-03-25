<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Models\Product;
use Doctrine\DBAL\Query\From;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Numeric;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Toko')
                    ->relationship('user', 'name')
                    ->required()
                    ->reactive()
                    ->hidden(fn() => Auth::user()->role === 'user'),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Transaksi')
                    ->default('TRX-' . date('Ymd') . '-' . rand(1000, 9999))
                    ->readOnly()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Customer')
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Nomer HP Customer')
                    ->required(),
                Forms\Components\TextInput::make('table_number')
                    ->label('Nomor Meja')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'midtrans' => 'Midtrans',

                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Tertunda',
                        'success' => 'Berhasil',
                        'failed' => 'Gagal',

                    ])
                    ->required(),
                Forms\Components\Repeater::make('transactionDetails')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->options(function (callable $get) {
                                if (Auth::user()->role === 'admin') {
                                    return Product::all()->mapWithKeys(function ($product) {
                                        return [$product->id => "$product->name (Rp " . number_format($product->price) . ")"];
                                    });
                                }
                                return Product::where('user_id', Auth::user()->id)->get()->mapWithKeys(function ($product) {
                                    return [$product->id => "$product->name (Rp " . number_format($product->price) . ")"];
                                });
                            })
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->default(1)
                            ->minValue(1)
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('note'),
                    ])->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::updateTotalPrice($get, $set);
                    })
                    ->reorderable(false),
                Forms\Components\TextInput::make('total_price')
                    ->readOnly()
                    ->required(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Toko')
                    ->hidden(fn() => Auth::user()->role === 'user'),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Transaksi'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Customer'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomer HP Customer'),
                Tables\Columns\TextColumn::make('table_number')
                    ->label('Nomor Meja'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(function (string $state) {
                        return 'Rp ' . number_format($state);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pembayaran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d-m-Y H:i:s'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Toko')
                    ->hidden(fn() => Auth::user()->role === 'user'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function updateTotalPrice(Get $get, Set $set)
    {
        $selectedProduct = collect($get('transactionDetails'))->filter(fn($item) => !empty($item['product_id']));
        $prices = Product::find($selectedProduct->pluck('product_id'))->pluck('price', 'id');
        $total = $selectedProduct->reduce(function ($total, $product) use ($prices) {
            return $total + ($prices[$product['product_id']] * $product['quantity']);
        }, 0);

        $set('total_price', (string) $total);
    }
}
