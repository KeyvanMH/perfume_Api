<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Http\Actions\Cart\CartAction;
use App\Http\Const\DefaultConst;
use App\Http\Services\Shetabit\ShetabitService;
use App\Models\User;
use App\Traits\HasUserCompletedInfo;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class BankGatewayRequestController extends Controller
{
    use HasUserCompletedInfo;

    /**
     * Display a listing of the resource.
     */
    public function index(CartAction $cartStatus, ShetabitService $shetabitService)
    {
        $user = auth()->user();
        $this->validateUser($user);
        $usersCartStatus = $cartStatus->handle($user);
        if (
            $usersCartStatus['shipping-price'] == null ||
            $usersCartStatus['shipping-price'] == 0 ||
            $usersCartStatus['total-price-to-pay'] == 0 ||
            $usersCartStatus['total-price-to-pay'] == null

        ) {
            return response()->json(['message' => DefaultConst::FAIL], 500);
        }
        $shetabitService->pay($user, $usersCartStatus);

        return response()->json(['bankGateway' => $shetabitService->bankGatewayUrl ?? null]);
    }

    public function verify(Request $request, ShetabitService $shetabitService)
    {
        $shetabitService->verify($request->query('Authority'), $request->query('Status') == 'OK');

        return response()->json(['reference id ' => $shetabitService->getReferenceId()]);

    }

    private function validateUser(User|Authenticatable $user)
    {
        // todo check that user is online and has cart :)
        if (! $this->hasAddress($user)) {
            throw new ErrorException('امکان خرید برای کاربر غیر فعال است.');
        }
    }
}
