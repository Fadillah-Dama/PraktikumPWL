<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort("created_at", "desc")
            ->columns([
                //
                TextColumn::make("title")->sortable(),
                TextColumn::make("slug")->sortable(),
                TextColumn::make("category.name")->sortable(),
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
                //
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
