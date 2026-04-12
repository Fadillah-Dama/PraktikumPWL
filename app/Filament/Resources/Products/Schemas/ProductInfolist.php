<?php

namespace App\Filament\Resources\Products\Schemas;

use BladeUI\Icons\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Tabs::make('Product Information')
                ->tabs([
                    Tab::make('Product Details')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                        TextEntry::make('name')
                            ->label('Product Name')
                            ->weight('bold')
                            ->color('primary'),
                        TextEntry::make('id')
                            ->label('Product ID'),
                        TextEntry::make('sku')
                            ->label('SKU')
                            ->badge()
                            ->color('warning'),
                        TextEntry::make('description')
                            ->label('Description'),
                        TextEntry::make('created_at')
                            ->label('Product Creation Date')
                            ->date('d M Y')
                            ->color('info'),
                        ]),

                    Tab::make('Product Price & Stock')
                        ->icon('heroicon-o-currency-dollar')
                        ->badge(fn ($record) => $record->stock)
                         ->schema([
                            TextEntry::make('price')
                                ->label('Product Price')
                                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                                ->weight('bold')
                                ->color('primary')
                                ->icon('heroicon-o-currency-dollar'),
                            TextEntry::make('stock')
                                ->label('Stock Quantity')
                                ->icon('heroicon-o-archive-box')
                        ]),
                    
                    Tab::make('Image & Status')
                        ->icon('heroicon-o-photo')
                        ->badgeColor('info')
                        ->schema([
                            ImageEntry::make('image')
                                ->label('Product Image')
                                ->disk('public'),
                            IconEntry::make('is_active')
                                ->label('Active Status')
                                ->boolean(),
                            IconEntry::make('is_featured')
                                ->label('Featured Product')
                                ->boolean(),  
                        ]),

                ])
                    ->columnSpanFull()
                    ->vertical(),

            ]); 
    }
}
    