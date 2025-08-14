<?php

namespace App\Filament\Resources\FakturResource\Pages;

use App\Filament\Resources\FakturResource;
use App\Models\PenjualanModel;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFaktur extends CreateRecord
{
    protected static string $resource = FakturResource::class;

    protected function afterCreate(): void
    {
        PenjualanModel::create([
            'kode' => $this->record->kode_faktur,
            'tanggal' => $this->record->tanggal_faktur,
            'jumlah' => $this->record->total_final,
            'customer_id' => $this->record->customer_id,
            'faktur_id' => $this->record->id,
            'status' => 0, // Assuming status 1 means active or completed
            'keterangan' => $this->record->ket_faktur,
        ]);
    }
}
