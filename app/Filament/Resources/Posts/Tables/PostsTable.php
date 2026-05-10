<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
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
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->listWithLineBreaks()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                ColorColumn::make('color')
                    ->label('Color')
                    ->toggleable(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->visibility('public')
                    ->toggleable(),
                IconColumn::make('published')
                    ->label('Published')
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['created_at'] ?? null,
                            fn ($query, $date) => $query->whereDate('created_at', $date),
                        );
                    }),
                SelectFilter::make('category_id')
                    ->label('Select Category')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->icon(Heroicon::OutlinedPencilSquare),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon(Heroicon::OutlinedTrash),
                ReplicateAction::make()
                    ->label('Replicate')
                    ->icon(Heroicon::OutlinedSquare2Stack),
                Action::make('status')
                    ->label('Change Status')
                    ->icon(fn (Post $record): Heroicon => $record->published ? Heroicon::OutlinedArrowPathRoundedSquare : Heroicon::OutlinedCheckCircle)
                    ->color(fn (Post $record): string => $record->published ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->schema([
                        Checkbox::make('published')
                            ->label('Published')
                            ->default(fn (Post $record): bool => $record->published),
                    ])
                    ->action(function (Post $record, array $data): void {
                        $record->update([
                            'published' => (bool) ($data['published'] ?? false),
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
