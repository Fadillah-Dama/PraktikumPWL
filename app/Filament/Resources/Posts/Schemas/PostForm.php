<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3) 
            ->components([ 

                Group::make([
                    Section::make("Post Details")
                        ->description("Fill in the details of the post") 
                        ->icon('heroicon-o-document-text') 
                        ->columns(2) 
                        ->schema([
                            TextInput::make("title")
                                ->required()
                                ->validationMessages([
                                    'required'=>'Title wajib diisi'
                                ])
                                ->rules([
                                    'required',
                                    'min:5',
                                    'max:50',
                                ]), 
                            TextInput::make("slug")
                                ->required()
                                ->unique()
                                ->minLength(3)
                                ->validationMessages([
                                    'unique'=>'Slug harus unik dan tidak boleh sama'
                                ]),
                            Select::make("category_id")
                                ->required()
                                ->relationship("category", "name")
                                ->preload()
                                ->searchable(),
                            ColorPicker::make("color"),
                            
                            
                            MarkdownEditor::make("content")
                                ->columnSpanFull(), 
                        ]),
                ])->columnSpan(2), 

                
                Group::make([ 
                    
                    // Section 2 - Image
                    Section::make("Image Upload")
                        ->icon('heroicon-o-photo') 
                        ->schema([
                            FileUpload::make("image")
                                ->required()
                                ->disk("public")
                                ->directory("posts"),
                        ]),

                    // Section 3 - Meta
                    Section::make("Meta Information")
                        ->icon('heroicon-o-tag') 
                        ->schema([
                            TagsInput::make("tags"),
                            Checkbox::make("published"),
                            
                            DateTimePicker::make("published_at")
                                ->columnSpanFull() 
                        ])->columns(2),
                    
                ])->columnSpan(1), 

            ]);
    }
}