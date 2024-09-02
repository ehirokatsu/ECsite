<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from "vue";
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

const props = defineProps<{
    carts: Cart[];
}>();

const quantities = ref(props.carts.map(cart => cart.quantity));

// 数量更新用の関数
function updateQuantity(index: number) {
    const cart = props.carts[index];
    const form = useForm({
        quantity: quantities.value[index]
    });

    form.put(route('vue.cart.quantityUpdate', {
        id: cart.product.id,
    }),{ preserveScroll: true });
}

</script>

<template>
    <Layout title="カート一覧">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">カートの一覧</h2>
            <div v-if="carts.length" class="space-y-6">
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
                        <div class="flex items-center space-x-3">
                            <select 
                                v-model="quantities[index]" 
                                v-on:change="updateQuantity(index)" 
                                class="form-select block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="n in 10" :value="n">{{ n }}</option>
                            </select>
                            <PrimaryButton @click="updateQuantity(index)">更新</PrimaryButton>
                            <Link 
                                v-bind:href="route('vue.cart.destroy', { id: cart.product.id })" 
                                as="button" 
                                method="delete"
                                preserve-scroll
                                class="text-red-600 hover:text-red-800 ml-4"
                            >
                                削除
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center text-gray-600">
                カートは空です。
            </div>
        </div>
    </Layout>
</template>