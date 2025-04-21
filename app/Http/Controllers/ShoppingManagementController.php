<?php

namespace App\Http\Controllers;

use App\Http\Actions\Cart\CartAction;

class ShoppingManagementController extends Controller
{
    /**
     * Display status of buying journey
     */
    public function index(CartAction $cartStatus)
    {
        // todo
        //        return ShoppingManagementResource::collection($cartStatus->handle(auth()->user()));
        return $cartStatus->handle(auth()->user());
    }
}
