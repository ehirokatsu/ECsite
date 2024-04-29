<script setup lang="ts">
import Layout from './Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Link } from '@inertiajs/vue3';


//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });

//Inertia::renderでproductsを受け取る
defineProps({
    products: Object,
});

/*
//axiosを使用する場合
import axios from 'axios';
import { ref, onMounted } from "vue";
const csrfToken = ref('');

//GETでCSRFトークン取得で、Blade経由なしで取得可能
onMounted(async () => {
    const response = await axios.get('/api/csrf-token');
    csrfToken.value = response.data.token;
    console.log(csrfToken.value);
});

function deleteButton(id: number): void {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value; // CSRFトークンをヘッダーにセット
    axios.delete(route('destroy', id));
}
*/


//deleteメソッドをフォームで使用する場合
//delete送信用ダミー
import { useForm } from '@inertiajs/vue3';
const form = useForm({
    
});
/*
//CSRFなしでも削除できた
const submit = (id: number) => {
    ///axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value; // CSRFトークンをヘッダーにセット
    //form.delete('/' + id);
    //下記だと、URLが/id?id=となってしまう
    //form.delete(route('destroy', ['id', id]));
    form.delete(route('vue.destroy', {'id': id}));
};
*/
const submit = (id: number) => {
    if (window.confirm('本当にこの商品を削除しますか？')) {
        // 削除を実行するロジックをここに追加する
        form.delete(route('vue.destroy', {'id': id}));
    }
}

import { usePage } from '@inertiajs/vue3'
// ページプロパティの取得
const { props } = usePage()

// フラッシュメッセージの取得
const message = props.flash?.message
console.log(message)



</script>

<template>
    <Layout title="商品一覧"/>

    <div v-if="props.flash?.message" class="bg-green-200 p-2 m-1">
                {{ props.flash.message }}

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
                            <img class="absolute inset-0 w-full h-full object-cover" v-bind:src="'/storage/' + product.image" alt="">
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
                                        <Link v-bind:href="route('vue.edit', {'id': product.id})">
                                        <!--<a v-bind:href="'/vue/' + product.id +'/edit/'" class="" >-->
                                            <PrimaryButton>編集</PrimaryButton>
                                        </Link>
                                    </div>
                                    <div class="p-1">
                                        <!--ユーザが入力するデータは無いのでLinkを使用
                                        <Link v-bind:href="route('vue.destroy', {'id': product.id})" as="button" method="delete">
                                            削除<DangerButton type="submit">削除</DangerButton>
                                        </Link>
                                        -->
                                        <!--フォームを使用する場合-->
                                        <form @submit.prevent="submit(product.id)">
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

</template>
