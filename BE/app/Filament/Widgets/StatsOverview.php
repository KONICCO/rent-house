<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Listing;
use App\Models\Transaction;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    /**
     * Fungsi untuk menghitung persentase perubahan dari nilai sebelumnya ke nilai saat ini.
     * Jika nilai sebelumnya 0 dan ada perubahan, maka dianggap kenaikan 100%.
     */
    private function getPercentage(float $from, float $to): float
    {
        if ($from == 0) {
            return $to > 0 ? 100 : 0; // Jika bulan lalu 0 transaksi, dan bulan ini ada transaksi, kenaikan dianggap 100%
        }

        return (($to - $from) / $from) * 100; // Rumus perubahan persentase
    }

    protected function getStats(): array
{
    // Ambil bulan dan tahun saat ini serta bulan sebelumnya
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    $prevMonth = Carbon::now()->subMonth()->month;
    $prevYear = Carbon::now()->subMonth()->year;

    // Mengambil jumlah listing baru dalam bulan ini
    $newListing = Listing::whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->count();

    // Menggunakan Eloquent untuk mengambil data dalam satu query per bulan
    $newTransaction = Transaction::where('status', 'approved')
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->get(['id', 'total_price']); // Ambil hanya field yang diperlukan

    $prevTransaction = Transaction::where('status', 'approved')
        ->whereMonth('created_at', $prevMonth)
        ->whereYear('created_at', $prevYear)
        ->get(['id', 'total_price']); // Ambil hanya field yang diperlukan

    // Menghitung jumlah transaksi dan total revenue dari hasil query Eloquent
    $newTransactionCount = $newTransaction->count();
    $newRevenue = $newTransaction->sum('total_price');
    $prevTransactionCount = $prevTransaction->count();
    $prevRevenue = $prevTransaction->sum('total_price');

    // Menghitung perubahan persentase jumlah transaksi dan revenue
    $transactionPercentage = $this->getPercentage($prevTransactionCount, $newTransactionCount);
    $revenuePercentage = $this->getPercentage($prevRevenue, $newRevenue);

    return [
        // Statistik jumlah listing baru dalam bulan ini
        Stat::make('New listings of the month', $newListing),

        // Statistik jumlah transaksi bulan ini dan perubahan dari bulan sebelumnya
        Stat::make('Transactions of the month', $newTransactionCount)
            ->description($transactionPercentage > 0 ? "{$transactionPercentage}% increased" : "{$transactionPercentage}% decrease")
            ->descriptionIcon($transactionPercentage > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
            ->color($transactionPercentage > 0 ? 'success' : 'danger'),

        // Statistik total revenue bulan ini dan perubahan dari bulan sebelumnya
        Stat::make('Revenue of the month', Number::currency($newRevenue, 'USD'))
            ->description($revenuePercentage > 0 ? "{$revenuePercentage}% increased" : "{$revenuePercentage}% decrease")
            ->descriptionIcon($revenuePercentage > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
            ->color($revenuePercentage > 0 ? 'success' : 'danger'),
    ];
}

}
