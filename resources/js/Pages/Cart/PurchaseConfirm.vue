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
    postal_code: string;
    address_1: string;
    address_2: string;
    address_3: string;
    phone_number: string;
}

const props = defineProps<{
    carts: Cart[];
    user: User; 
}>();


</script>

<template>
    <Layout title="カート一覧">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">購入者情報は以下でよろしいですか？</h2>
 
        </div>
    </Layout>
</template>