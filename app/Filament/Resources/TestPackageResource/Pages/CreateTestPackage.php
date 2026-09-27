<?php

namespace App\Filament\Resources\TestPackageResource\Pages;

use App\Filament\Resources\TestPackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestPackage extends CreateRecord
{
    protected static string $resource = TestPackageResource::class;
}
