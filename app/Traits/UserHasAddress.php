<?php

namespace App\Traits;

use App\Models\User;

trait UserHasAddress {
    protected function hasAddress(User $user){
        return (bool)$user->city_id && (bool)$user->post_number;
    }
}
