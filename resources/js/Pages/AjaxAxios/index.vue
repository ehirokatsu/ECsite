<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, onMounted } from "vue";

interface Product {
    id: number;
    name: string;
    cost: number;
    image: string;
}

const products = ref<Product[]>([]);
const csrfToken = ref('');
const searchWord = ref("");
const errorMessage = ref("");

// APIでproductを取得する方法
const fetchProducts = async () => {
    try {
        const response = await axios.get(route('api.index'));
        products.value = response.data;
    } catch (error) {
        console.error("Failed to fetch products:", error);
        errorMessage.value = "商品の取得に失敗しました。";
    }
};

// CSRFトークンの取得。無くてもstoreできた。（セッションに保存されているから？）
const fetchCsrfToken = async () => {
    try {
        const response = await axios.get('/api/csrf-token');
        csrfToken.value = response.data.token;
        //axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value;
    } catch (error) {
        console.error("Failed to fetch CSRF token:", error);
        errorMessage.value = "CSRFトークンの取得に失敗しました。";
    }
};

// 初期データの取得
onMounted(() => {
    fetchProducts();
    fetchCsrfToken();
});

const deleteButton = async (id: number) => {
    if (window.confirm('本当にこの商品を削除しますか？')) {
        try {
            await axios.delete(route('api.delete', id));
            products.value = products.value.filter(product => product.id !== id);
        } catch (error) {
            console.error("Delete failed:", error);
            errorMessage.value = "商品の削除に失敗しました。";
        }
    }
};

const ExecSearch = async () => {
    try {
        const response = await axios.get(route('api.search'), {
            params: {
                query: searchWord.value
            }
        });
        products.value = response.data;
    } catch (error) {
        console.error("Search failed:", error);
        errorMessage.value = "検索に失敗しました。";
    }
};
</script>

<template>
    <Layout title="商品一覧">
        <h2>商品の一覧です。</h2>
        <!--フラッシュメッセージ表示-->
        <div v-if="$page.props.flash?.message" class="bg-green-200 p-2 m-1">
            {{ $page.props.flash.message }}
        </div>
        <!--エラーメッセージ表示-->
        <div v-if="errorMessage" class="bg-red-200 p-2 m-1">
            {{ errorMessage }}
        </div>
        <!--検索-->
        <div>   
            <InputLabel>検索</InputLabel>
            <TextInput v-model="searchWord"></TextInput>
            <button v-on:click="ExecSearch()">実行</button>
        </div>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div 
                        v-for="product in products" 
                        :key="product.id"
                    >
                        <div class="border border-gray-300 p-4 rounded-md">
                            <!--商品画像-->
                            <div class="relative w-84 h-64 mb-3">
                                <img class="absolute inset-0 w-full h-full object-cover" v-bind:src="'/storage/' + product.image" alt="Product Image">
                            </div>
                            <!--説明箇所-->
                            <div class="flex justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold mb-2">{{ product.name }}</h2>
                                    <p class="text-gray-600 mb-2">¥{{ product.cost }}</p>
                                </div>
                                <div class="">
                                    カートに入れる
                                    <div class="flex p-2">
                                        <div class="p-1">
                                            <Link v-bind:href="route('vue.ajaxaxios.edit', {'id': product.id})">
                                                <PrimaryButton>編集</PrimaryButton>
                                            </Link>
                                        </div>
                                        <div class="p-1">
                                            <form @submit.prevent="deleteButton(product.id)">
                                                <DangerButton type="submit">削除</DangerButton>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
