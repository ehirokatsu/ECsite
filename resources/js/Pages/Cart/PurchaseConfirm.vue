<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref, onMounted } from "vue";
import { useForm } from '@inertiajs/vue3';
import type { User, Cart } from '@/types/index.d.ts';

import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    inputUser: User
}>();

// Inertia.js でページ情報を取得
const page = usePage<{ auth: { user: User }; carts: Cart[] }>();

//
const user = props.inputUser || page.props.auth.user;

const form = useForm({
    inputUser: {
        name: user.name,
        email: user.email,
        postal_code: user.postal_code,
        address_1: user.address_1,
        address_2: user.address_2,
        address_3: user.address_3,
        phone_number: user.phone_number,
    },
});

const submit = () => {
    form.post(route('vue.cart.purchaseComplete'));
};
const submit2 = () => {
    form.post(route('vue.cart.correctPurchaseInfo'));
};


</script>

<template>
    <Layout title="購入情報">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form @submit.prevent="submit">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">購入者情報は以下でよろしいですか？</h2>
                <div class="mt-4">{{ form.inputUser.name }}</div>
                <div class="mt-4">{{ user.email }}</div>
                <div class="mt-4">{{ user.postal_code }}</div>
                <div class="mt-4">{{ user.address_1 }}</div>
                <div class="mt-4">{{ user.address_2 }}</div>
                <div class="mt-4">{{ user.address_3 }}</div>
                <div class="mt-4">{{ user.phone_number }}</div>
                <div class="space-y-6">
                    <div 
                        v-for="(cart, index) in page.props.carts" 
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
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    確定
                </PrimaryButton>
            </form>
            <form @submit.prevent="submit2">
                <DangerButton>修正</DangerButton> 
            </form>
        </div>
    </Layout>
</template>