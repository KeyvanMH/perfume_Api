<?php

namespace App\Observers;

use App\Models\Factor;
use Illuminate\Support\Facades\DB;

class FactorObserver
{

    /**
     * Handle the Factor "deleted" event.
     */
    public function deleted(Factor $factor): void
    {
        //delete corresponding perfume based factor and delete corresponding amount of perfume from the Perfume::class
        info('magic');
        DB::transaction(function() use ($factor){
            $perfumesBasedFactor = $factor->perfumeBasedFactor;
            //delete perfumesBasedFactor
            $factor->perfumeBasedFactor()->delete();
            //update perfumes stock in Perfume::class
            $this->updatePerfumeQuantity($perfumesBasedFactor);
        });
    }

    /**
     * Handle the Factor "restored" event.
     */
    public function restored(Factor $factor): void
    {
        //
    }
    private function updatePerfumeQuantity($perfumesBasedFactor) {
        foreach ($perfumesBasedFactor as $perfumeBasedFactor){
            if($perfumeBasedFactor->perfume->is_active && !$perfumeBasedFactor->perfume->deleted_at && $perfumeBasedFactor->stock != $perfumeBasedFactor->sold){
                //todo maybe a sanitizer method that double checks if the stock are not below 0
                $perfumeBasedFactor->perfume->quantity -= $perfumeBasedFactor->stock - $perfumeBasedFactor->sold;
                $this->deactivatePerfumeBasedFactor($perfumeBasedFactor);
                $perfumeBasedFactor->perfume->save();
            }
        }
    }

    private function deactivatePerfumeBasedFactor($perfumeBasedFactor):void{
        $perfumeBasedFactor->is_active = 0;
        $perfumeBasedFactor->save();
    }
}
