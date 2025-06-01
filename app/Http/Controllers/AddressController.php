<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    /**
     * Handle the incoming request to fetch addresses by postcode.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $addressService = new AddressService();

        $postcode = $request->query('postcode');

        $addresses = $addressService->getAddressesByPostcode($postcode);

        return response()->json($addresses, 200);
    }
}
