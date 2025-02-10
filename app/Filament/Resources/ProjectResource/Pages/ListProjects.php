<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource\Widgets\ProjectOverview;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProjectResource;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
    protected ?string $heading = "Liste des projets";

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    protected function getHeaderWidgets(): array
    {
        return [
            // ProjectOverview::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Tous'),
            'A faire' => Tab::make()->query(fn($query) => $query->where('status', 'todo')),
            'En cours' => Tab::make()->query(fn($query) => $query->where('status', 'in_progress')),
            'Terminé' => Tab::make()->query(fn($query) => $query->where('status', 'done')),
            'Annulé' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
