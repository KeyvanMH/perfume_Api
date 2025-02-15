<?php

namespace App\Http\Action\Postex;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class PostexService {

    private readonly string $OriginCity ;
    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->OriginCity = env('POST_CITY_ID');
    }

    public function calculateShippingPrice($output) {
        $productValue = $output['price-without-discount'];
        $url = 'https://api.postex.ir/api/v1/shipping-price';
        $apiKey = env('POST_API_KEY');
        $payload = [
            'courier' => [
                'courier_code' => 'IR_POST',
                'service_type' => 'EXPRESS',
                'payment_type' => 'SENDER'
            ],
            'from_city_code' => $this->OriginCity,
            'to_city_code' => $this->user->city_id??null,
            'parcel_properties' => [
                'height' => 100,
                'width' => 100,
                'length' => 100,
                'box_type_id' => $this->boxType($output['products']),
                'total_weight' => 400,
                'total_value' => $productValue
            ],
            'has_collection' => false,
            'has_distribution' => false,
            'value_added_service' => [0]
        ];

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post($url, $payload);

        if (isset($response->json()['isSuccess']) && $response->json()['isSuccess'] && isset($response->json()['data']['servicePrices'][0]['initPrice'])) {
            return $response->json()['data']['servicePrices'][0]['initPrice'];
        }
        return null;
    }


    private function boxType($products) {
        $count = 0;
        foreach($products as $product){
            $count += $product['count'];
        }
        //todo check for suitable box type
        return match ($count) {
            1 => 1,
            2 => 2,
            3 => 3,
            default => 4,
        };
    }

}
