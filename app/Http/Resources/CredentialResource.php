<?php

namespace App\Http\Resources;

use App\Http\Controllers\CredentialController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CredentialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phone_number' => $this->phone_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email'=> $this->email,
            'post_number' => $this->post_number,
            'city' => $this->when($this->city_id,$this->city->name),
            'status' => CredentialController::isCredentialsComplete()?'اطلاعات تکمیل شده است':'اطلاعات نیاز به تکمیل  دارند',
        ];
    }


}
