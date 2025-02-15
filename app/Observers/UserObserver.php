<?php

namespace App\Observers;

use App\Models\Factor;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //todo send sign up sms
        //todo maybe change index column of DB?????
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //todo
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void {
        //todo maybe user queue????
        $this->deleteFactor($user);
    }
    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        // cascade DB , not possible to active this function
    }
    private function deleteFactor($user):void {
        $factors = Factor::where('user_id', '=', $user->id)->get();
        foreach ($factors as $factor) {
            $factor->delete();
        }
    }
}
