<?php

namespace App\Filament\Resources\SubscriberResource\Pages;

use App\Filament\Resources\SubscriberResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Konnco\FilamentImport\Actions\ImportField;
use Konnco\FilamentImport\Actions\ImportAction;

class ManageSubscribers extends ManageRecords
{
    protected static string $resource = SubscriberResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->modalWidth('md'),
            ImportAction::make()
                ->fields([
                    ImportField::make('name')
                            ->rules('required|min:5|max:255'),
                    ImportField::make('email')
                            ->rules('required|email:rfc,dns|unique:subscribers,email')
                ])->modalWidth('md')
        ];
    }
}
