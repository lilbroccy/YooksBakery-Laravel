<?php

namespace App\Observers;

use App\Models\Produk;
use Carbon\Carbon;


class ProductStockObserver
{
    public function updating(Produk $produk)
    {
        if ($produk->tanggal_produksi && Carbon::now()->diffInDays($produk->tanggal_produksi) > 5) {
            $produk->stock_produk = 0;
        }
    }
}
