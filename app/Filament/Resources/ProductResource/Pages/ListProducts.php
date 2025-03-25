<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\Subscription;
use Filament\Actions;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Infolists\Components\Actions as InfolistsComponentsActions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::user()->role === 'admin') {
            return [
                Actions\CreateAction::make(),
            ];
        }

        $subscription = Subscription::where('user_id', Auth::user()->id)
            ->where('is_active', true)
            ->where('end_date', '>=', now())
            ->first()
            ->latest();

        $countProduct = Product::where('user_id', Auth::user()->id)->count();

        return [
            Actions\Action::make('alert')
                ->color('danger')
                ->label('You have reached the maximum product limit. Please upgrade your subscription to create more products.')
                ->visible($countProduct >= 5 && !$subscription)
                ->icon('heroicon-s-exclamation-triangle')
                ->visible(!$subscription && $countProduct >= 5),
            Actions\CreateAction::make(),

        ];
    }
}
