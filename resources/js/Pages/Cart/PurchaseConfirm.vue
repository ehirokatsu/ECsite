<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref } from "vue";
import { useForm } from '@inertiajs/vue3';

interface Product {
    id: number;
    name: string;
    cost: number;
    image: string;
}
interface Cart {
    product: Product;
    quantity: number;
}

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    postal_code: string;
    address_1: string;
    address_2: string;
    address_3: string;
    phone_number: string;
}

/*
const props2 = defineProps<{
    carts: Cart[];
    //user: User; 
}>();
*/
import { usePage } from '@inertiajs/vue3';

// Inertia.js でページ情報を取得
const { props } = usePage<{ auth: { user: User }; carts: Cart[] }>();
const user = computed(() => props.auth?.user || null);
const carts = computed(() => props.carts || []);

</script>

<template>
    <Layout title="購入情報">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">購入者情報は以下でよろしいですか？</h2>
            {{ user.name }}
            {{ user.postal_code }}
            <div class="space-y-6">
                <div 
                    v-for="(cart, index) in carts" 
                    :key="cart.product.id"
                    class="flex items-center bg-gray-100 rounded-lg shadow-sm p-4"
                >
                    <div class="relative w-32 h-32">
                        <img 
                            class="rounded-md object-cover" 
                            v-bind:src="'/storage/' + cart.product.image" 
                            alt="Product Image"
                        >
                    </div>
                    <div class="ml-6 flex-1">
                        <h2 class="text-lg font-semibold mb-1 text-gray-800">{{ cart.product.name }}</h2>
                        <p class="text-gray-600 mb-3">¥{{ cart.product.cost }}</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>