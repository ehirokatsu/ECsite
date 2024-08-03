<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from "vue";

//Inertia::renderでproductsを受け取る
const props = defineProps({
    products: Object,
});

//検索キー用
const searchWord = ref("");

</script>

<template>
    <Layout title="商品一覧">

    <h2>商品の一覧です。</h2>
    <!--フラッシュメッセージ表示-->
    <div v-if="$page.props.flash?.message" class="bg-green-200 p-2 m-1">
        {{ $page.props.flash.message }}
    </div>
    <!--検索-->
    <div>   
        <InputLabel>検索</InputLabel>
        <TextInput v-model="searchWord"></TextInput>
        <!--Linkでボタン押下でGetリクエストを送信する-->
        <Link v-bind:href="route('vue.search', {query: searchWord})">検索</Link>
    </div>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div 
                    v-for="product in props.products" 
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
                                        <!--文字列連結等の式を入れるにはbindが必要-->
                                        <Link v-bind:href="route('vue.ajaxlink.edit', {'id': product.id})">
                                            <PrimaryButton>編集</PrimaryButton>
                                        </Link>
                                    </div>
                                    <div class="p-1">
                                        <!--ユーザが入力するデータは無いのでLinkを使用-->
                                        <!--Link使用時の確認ダイアログの表示方法は不明-->
                                        <Link v-bind:href="route('vue.destroy', {'id': product.id})" as="button" method="delete" preserve-scroll>
                                            <DangerButton type="submit">削除</DangerButton>
                                        </Link>
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
