<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';

import { useForm } from '@inertiajs/vue3';

//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });

//Inertia::renderでproductsを受け取る
defineProps({
    products: Object,
});
//delete送信用ダミー
const form = useForm({
    
});

/*
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
//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submit = (id: number) => {
    ///axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value; // CSRFトークンをヘッダーにセット
    //form.delete('/' + id);
    //下記だと、URLが/id?id=となってしまう
    //form.delete(route('destroy', ['id', id]));
    form.delete(route('destroy', {'id': id}));
};

import {ref} from "vue";
let tmp: string = "初期値";
let tmpRef= ref(0);

const onButton1 = () => {
    tmpRef.value = tmpRef.value + 1;
}

const onButton2 = () => {
    tmpRef.value = tmpRef.value - 1;
}
</script>

<template>
    <Head title="Dashboard" />

    <GuestLayout>

        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <input type="text" v-model="tmpRef">
                    {{ tmpRef }}
                    <button v-on:click="onButton1()">+++</button>
                    <button v-on:click="onButton2()">---</button>
                    <div v-if="$page.props.auth.user">ログイン済み</div>
                    <div v-else>未ログイン</div>
                    <a v-bind:href="route('vue.create')">新規作成</a>
                    <div 
                        v-for="product in products" 
                        :key="product.id"
                    >
                        <div class="relative w-84 h-64 mb-3">
                            <img class="absolute inset-0 w-full h-full object-cover" v-bind:src="'/storage/' + product.image" alt="">
                        </div>
                        <span>{{ product.name }}</span>
                        <span>{{ product.cost }}</span>
                        <!--文字列連結等の式を入れるにはbindが必要-->
                        <a v-bind:href="route('vue.edit', {'id': product.id})">
                        <!--<a v-bind:href="'/vue/' + product.id +'/edit/'" class="" >-->
                            <button>編集</button>
                        </a>
                        <form @submit.prevent="submit(product.id)">
                            <button type="submit">削除</button>
                        </form>
                    </div>
                    

                </div>
            </div>
        </div>
    </GuestLayout>
</template>