<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Symfony\Component\HttpFoundation\Response;

class CheckMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get user_id dari request (bisa dari parameter atau header)
        $userId = $request->route('userId') ?? $request->input('user_id') ?? $request->header('X-User-Id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID tidak ditemukan'
            ], 401);
        }

        // Cek apakah user punya membership aktif
        $subscription = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur ini khusus untuk member premium. Silakan upgrade membership Anda.',
                'has_membership' => false,
                'redirect_to' => '/subscriptions'
            ], 403);
        }

        // Tambahkan subscription data ke request untuk digunakan di controller
        $request->merge(['subscription' => $subscription]);

        return $next($request);
    }
}
