<?php

namespace App\Http\Middleware;

use App\Http\Const\DefaultConst;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class ExtendCartTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        foreach (DefaultConst::PRODUCT_TYPE as $productType) {
            $cart = Redis::hgetall("cart:product_type=$productType&user_id=$user->id");
            if (! is_array($cart) || empty($cart)) {
                continue;
            }
            foreach ($cart as $productId => $quantity) {
                Redis::expire("cart:product_type=$productType&user_id=$user->id", 30 * 60);
            }
        }
        if (Redis::exists("online_user:$user->id")) {
            Redis::expire("online_user:$user->id", 30 * 60);

            return $next($request);
        }
        Redis::setex("online_user:$user->id", 30 * 60, now());

        return $next($request);
    }
}
