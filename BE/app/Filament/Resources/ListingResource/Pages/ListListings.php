<?php

namespace App\Filament\Resources\ListingResource\Pages;

use Filament\Actions;
use App\Models\Transaction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ListingResource;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListListings extends ListRecords
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    /**
     * Get all of the transaction for the ListListings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'listing_id', 'id');
    }
}
