<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Header from './Header.vue';
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

</script>

<template>
    <Head title="Dashboard" />

    <Header></Header>
<!--
    <GuestLayout>
    -->

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
    </div>
        <!--
    </GuestLayout>
-->
</template>
