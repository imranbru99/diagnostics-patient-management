<?php

namespace App\Filament\Resources\ReagentResource\Pages;

use App\Filament\Resources\ReagentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReagent extends EditRecord
{
    protected static string $resource = ReagentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
