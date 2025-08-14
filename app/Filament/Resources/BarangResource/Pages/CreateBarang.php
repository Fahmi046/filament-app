<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Mockery\Matcher\Not;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Berhasil dibuat')
            ->success()
            ->body('Data barang berhasil dibuat')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('warning')
            ->duration(2000);
    }
}
