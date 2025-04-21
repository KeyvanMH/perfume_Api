<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait HasUserCompletedInfo
{
    // todo email , phone_number , post_number
    protected function hasAddress(User|Authenticatable $user)
    {
        return (bool) $user->city_id && (bool) $user->post_number;
    }
}
