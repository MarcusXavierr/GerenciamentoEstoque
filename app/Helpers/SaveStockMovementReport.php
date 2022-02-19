<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;

class SaveStockMovementReport
{
    /**
     * Save data in a database table that stores information about the stock movement
     */
    public function saveReportOfMovementOnDB(string $SKU, int $stock_id, int $quantity, bool $isApi): bool
    {
        $success = true;
        try {
            DB::table('stock_movements')->insert([
                "product_id" => $SKU,
                "stock_id" => $stock_id,
                "is_api" => $isApi,
                "products_movemented" => $quantity,
                "created_at" => now(),
                "updated_at" => now()
            ]);
        } catch (Exception) {
            $success = false;
        }

        return $success;
    }
}
