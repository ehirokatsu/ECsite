<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from "vue";

//deleteメソッドをフォームで使用する場合
//delete送信用ダミー
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


</script>

<template>
    <Layout title="カート一覧">

    <h2>カートの一覧です。</h2>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-if="carts">
                    <div class="flex">
                        <button class="m-2">
                            <!--
                            <a href="{{ route('cart.regConfirm') }}">購入する</a>
                            -->
                        </button>
                        <div class="m-2">
                            <!--
                            <form action="{{ route('cart.allDelete') }}" method="post">
                            <button type="submit">
                                <span>カートを空にする</span>
                            </button>
                            </form>
                            -->
                        </div>
                    </div>
                    <div 
                        v-for="cart in props.carts" 
                        :key="cart.product.id"
                    >
                        <div class="flex p-2 rounded-md justify-center">
                            <div class="relative w-40 h-40 mb-3">
                            <img class="absolute inset-0 w-full h-full object-cover" v-bind:src="'/storage/' + cart['product'].image" alt="Product Image">
                            </div>
                            <div class="mr-20">
                                <div class="p-4">
                                    <h2 class="text-lg font-semibold mb-2">{{ cart['product'].name }}</h2>
                                    <p class="text-gray-600 mb-2">¥{{ cart['product'].cost }}</p>
                                </div>
                            </div>
                            <div class="">
                                <div class="flex p-3">
                                    <!--
                                    <form action="{{ route('cart.quantityUpdate', ['id' => $cart['product']['id']]) }}" method="post">
                                        <label for="">個数</label>
                                        <input type="number" name="quantity" class="w-20" value="{{ $cart['quantity'] }}">
                                        
                                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                        
                                        <button type="submit" class=" m-2">
                                        更新する
                                        </button>
                                    </form>
                                    -->
                                    <!--
                                    <form action="{{ route('cart.destroy', ['id' => $cart['product']['id']]) }}" method="post">
                                    <button type="submit" class="m-2">
                                        削除する
                                    </button>
                                    </form>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    カートは空です。
                </div>
            </div>
        </div>
    </div>

    </Layout>

</template>
