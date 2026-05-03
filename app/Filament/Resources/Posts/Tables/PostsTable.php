<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort("created_at", "desc")
            ->columns([
                //
                TextColumn::make("title")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("slug")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("category.name")
                    ->sortable()
                    ->searchable(),
                TextColumn::make("created_at")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable(),
                ColorColumn::make("color"),
                ImageColumn::make("image")
                    ->disk("public")
                    ->visibility("public"),
                IconColumn::make("published")->boolean(),
            ])
            ->filters([
                Filter::make("created_at")
                    ->label("Creation Date")
                    ->schema([
                        DatePicker::make("created_at")
                            ->label("Select Date"),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data["created_at"] ?? null,
                            fn ($query, $date) => $query->whereDate("created_at", $date),
                        );
                    }),
                SelectFilter::make("category_id")
                    ->label("Select Category")
                    ->relationship("category", "name")
                    ->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
