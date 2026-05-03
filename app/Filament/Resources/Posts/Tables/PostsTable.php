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
                TextColumn::make("id")
                    ->label("ID")
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("title")
                    ->label("Title")
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make("slug")
                    ->label("Slug")
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make("category.name")
                    ->label("Category")
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make("tags")
                    ->label("Tags")
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(", ", $state) : (string) $state)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("created_at")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                ColorColumn::make("color")
                    ->label("Color")
                    ->toggleable(),
                ImageColumn::make("image")
                    ->label("Image")
                    ->disk("public")
                    ->visibility("public")
                    ->toggleable(),
                IconColumn::make("published")
                    ->label("Published")
                    ->boolean()
                    ->toggleable(),
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
