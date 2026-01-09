<?php

// app/Http/Controllers/Api/EggPriceController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EggPrice;
use Illuminate\Http\Request;

class EggPriceController extends Controller
{
    // GET /api/egg-prices?plan_name=Paket%20Hemat&days=7
    public function history(Request $request)
    {
        $planName = $request->query('plan_name');
        $days     = (int) $request->query('days', 7);

        if (!$planName) {
            return response()->json([
                'success' => false,
                'message' => 'plan_name is required',
                'data'    => [],
            ], 400);
        }

        $query = EggPrice::where('plan_name', $planName)
            ->orderBy('date', 'asc');

        if ($days > 0) {
            $query->where('date', '>=', now()->subDays($days)->toDateString());
        }

        $data = $query->get(['date', 'price_per_kg']);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }
}