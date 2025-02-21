<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;

class WaitingTransactions extends BaseWidget
{
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ...
                Transaction::query()->whereStatus('waiting')
            )
            ->columns([
                // ...
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('listing.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->numeric()
                    ->sortable()->money('USD'),
                Tables\Columns\TextColumn::make('total_day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fee')
                    ->numeric()
                    ->sortable()->money('USD'),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable()->money('USD')->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state) => match ($state) {
                    'waiting' => 'info',
                    'approved' => 'success',
                    'canceled' => 'danger',
                }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('Approve')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action( function (Transaction $transaction) {
                            Transaction::find($transaction->id)->update(['status' => 'approved']);
                            Notification::make()->success()->title('Transaction Approved')->body('Transaction has been approve')->icon('heroicon-o-check')->send();
                        })
                    ->hidden(condition: fn(Transaction $transaction) => $transaction->status !== 'waiting')
            ]);
    }
}
