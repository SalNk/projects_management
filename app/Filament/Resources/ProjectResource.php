<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Resources\ProjectResource\RelationManagers;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Main';
    protected static ?string $navigationLabel = 'Projets';
    protected static ?string $navigationBadgeTooltip = 'Projets à faire';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'todo')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->options([
                                'website' => 'Site web',
                                'app' => 'Application mobile',
                                'api' => 'API',
                                'website_and_app' => 'Site web & application mobile',
                                'website_and_api' => 'Site web & API',
                                'app_and_api' => 'Application mobile & API',
                                'website_app_and_api' => 'Site web, application mobile & API',
                            ])
                            ->placeholder('Sélectionner le type de projet')
                            ->label('Type de projet'),
                        TextInput::make('name')
                            ->label('Titre')
                            ->placeholder('Titre du projet'),
                        Select::make('user_id')
                            ->label('Tâche attribuée à')
                            ->columnSpanFull()
                            ->relationship('user', 'name')
                    ]),
                Section::make('Structure')
                    ->schema([
                        Textarea::make('sections')
                            ->placeholder("Veillez spécifier les sections de l'application"),
                        Textarea::make('menus')
                            ->placeholder("Veillez spécifier les différents menus de l'application"),
                    ]),
                Section::make('Attachments')
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->imageEditor()
                            ->downloadable(),
                        FileUpload::make('file_path')
                            ->label('Fichier détaillant le projet')
                            ->downloadable(),
                        FileUpload::make('template')
                            ->downloadable(),


                        // SpatieMediaLibraryFileUpload::make('media')
                        //     ->collection('images')
                        //     ->multiple()
                        //     ->maxFiles(2)
                        //     ->hiddenLabel()
                        //     ->label('Images à inclure'),
                    ]),
                Section::make('')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'todo' => 'A faire',
                                'in_progress' => 'En cours',
                                'done' => 'Terminé',
                                'cancelled' => 'Annulé',
                            ])
                            ->default('todo'),
                        Textarea::make('note')
                            ->rows(10)
                            ->autosize(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo'),
                TextColumn::make('type'),
                TextColumn::make('name')
                    ->label('Titre'),
                TextColumn::make('status')
                    ->label('Statut')
                    ->formatStateUsing(function ($state) {
                        $translations = [
                            'todo' => 'A faire',
                            'in_progress' => 'En cours',
                            'done' => 'Terminé',
                            'cancelled' => 'Annulé',
                        ];
                        return $translations[$state] ?? $state;
                    })
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'todo' => 'info',
                        'in_progress' => 'primary',
                        'done' => 'success',
                        'cancelled' => 'danger'
                    }),
                TextColumn::make('user.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
