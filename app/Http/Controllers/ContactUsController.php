<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
/**
 * @OA\Post(
 * *      path="/api/contact-us",
 * *      summary="فرم برای سفارش های خاص (ارتباط با ما)",
 * *     @OA\RequestBody(
 * *          @OA\MediaType(
 * *              mediaType="application/json",
 * *              @OA\Schema(
 * *                  @OA\Property(
 * *                      property="phone_number",
 * *                      type="string"
 * *                  ),
 * *                  @OA\Property(
 * *                      property="description",
 * *                      type="string"
 * *                  ),
 *                    @OA\Property(
 * *                      property="email",
 * *                      type="string",
 *                        description="nullable"
 * *                  ),
 * *                  example={"description": "سوال مورد نظر", "phone_number": "09331574190 ","email": "email@gmail.com "}
 * *              )
 * *          )
 * *      ),
 * *      @OA\Response(
 * *           response=200,
 * *           description="OK"
 * *           ),
 * *       )
 * *  )
 **/
class ContactUsController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsRequest $request)
    {
        ContactUs::Create([
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email')??NULL,
            'description' => $request->input('description')
        ]);
        return response()->json(['response' => 'ok']);
    }


}
