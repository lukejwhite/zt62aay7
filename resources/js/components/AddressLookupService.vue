<script setup lang="ts">
import { ref, watch } from 'vue';
import axios from 'axios';

const postcode = ref('');
const addresses = ref([]);

const fetchAddresses = async (postcode: string) => {
    const response = await axios.get(`/api/addresses?postcode=${postcode}`);
    console.log(response);

    const validAddresses = ref([]);

    response.data.forEach((address: any) => {
        if (address.uprn) {
            validAddresses.value.push({
                street: address.street,
                city: address.city,
                county: address.county,
                postcode: address.postcode,
            });
        }
    });

    addresses.value = validAddresses.value;
};

watch (postcode, () => {
    addresses.value = [];
})
</script>

<template>
    <div class="p-3">
        <h1 class="text-2xl font-bold mb-4">Address Lookup Service</h1>
        <p class="mb-4">This component provides an interface for looking up addresses.</p>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            <label
                for="address"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
            >
                Enter Postcode:
            </label>
            <input
                v-model="postcode"
                type="text"
                id="address"
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"
                placeholder="B3 3AG"
            />
            <button
                @click="fetchAddresses(postcode)"
                class="mt-3 bg-gray-600 text-white px-4 py-2 rounded-lg"
                :class="{
                    'hover:bg-blue-700 bg-blue-500 dark:hover:bg-blue-600': postcode !== '',
                }"
            >
                Lookup Address
            </button>

            <div class="mt-4">
                <h2 class="text-lg font-semibold mb-2">Addresses:</h2>
                <p v-if="addresses.length === 0" class="text-gray-500">No addresses found.</p>
                <p v-else class="text-gray-500">Please see the list below:</p>
                <div
                    v-for="(address, index) in addresses"
                    :key="index"
                    class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg mb-2"
                >
                    <p class="text-gray-800 dark:text-gray-200">
                        {{ address.street }},
                        {{ address.city }},
                        {{ address.county }},
                        {{ address.postcode }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>