<?php

namespace App\Traits;

use App\Http\Const\DefaultConst;
use Illuminate\Support\Facades\Redis;

trait ReserveProductManagement
{
    public function getReservedProduct($productId, $productType)
    {
        $onlineUsers = $this->getOnlineUsers();
        $ReservedProduct = 0;
        if (empty($onlineUsers)) {
            return $ReservedProduct;
        }
        foreach ($onlineUsers as $onlineUser) {
            if (preg_match('/online_user:(\d+)/', $onlineUser, $matches)) {
                $userId = $matches[1];
                $cart = Redis::hgetall("cart:product_type=$productType&user_id=$userId");
                if (! is_array($cart) || empty($cart)) {
                    continue;
                }
                foreach ($cart as $cartProductId => $cartQuantity) {
                    if ($cartProductId == $productId) {
                        $ReservedProduct += $cartQuantity;
                    }
                }
            }
        }

        return $ReservedProduct;
    }

    public function getOnlineUsers()
    {
        return Redis::keys('online_user:*');
    }

    public function getUserCartProducts($user)
    {
        $output = [];
        foreach (DefaultConst::PRODUCT_TYPE as $productType) {
            if (Redis::exists("cart:product_type=$productType&user_id=$user->id")) {
                $output[$productType] = Redis::hgetall("cart:product_type=$productType&user_id=$user->id");
            }
        }

        return $output;
    }
}
