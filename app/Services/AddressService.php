<?php

namespace App\Services;

use App\Models\AddressCache;
use Faker\Provider\ar_EG\Address;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;

class AddressService
{
    private $addressRepository;

    /**
     * getAddressesByPostcode
     *
     * Fetches addresses from an external service based on the provided postcode.
     * @param string $postcode
     * @return array
     */
    public function getAddressesByPostcode(string $postcode, bool $forceUpdate = false): array
    {
        if ($forceUpdate) {
            AddressCache::where('postcode', $postcode)->delete();
        }

        $cachedAddresses = AddressCache::where('postcode', $postcode)->get();

        if ($cachedAddresses->isNotEmpty()) {
            return $cachedAddresses;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get(env('ADDRESS_SERVICE_URL') . '?postcode=' . $postcode);

        $formattedAddresses = [];

        foreach ($response->json() as $address) {
            $formattedAddresses[] = [
                'uprn' => $address['uprn'],
                'street' => $address['street'],
                'city' => $address['city'],
                'county' => $address['state'],
                'postcode' => $address['postcode'],
            ];

            $addressCache = new AddressCache();
            $addressCache->uprn = $address['uprn'];
            $addressCache->street = $address['street'];
            $addressCache->city = $address['city'];
            $addressCache->county = $address['state'];
            $addressCache->postcode = $address['postcode'];
            $addressCache->save();
        }

        return $formattedAddresses;
    }
}
