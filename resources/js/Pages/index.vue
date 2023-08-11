<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { formToJSON } from 'axios';

import { ref, onMounted } from "vue";

import { useForm } from '@inertiajs/vue3';

let products = ref([]);



axios.get("/product").then(response => { products.value = response.data });

import { defineComponent } from 'vue'

defineProps({
    test: Number,
});

const form = useForm({
    
});
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

//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submit = (id: number) => {
    ///axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value; // CSRFトークンをヘッダーにセット
    form.delete('/6');
};

</script>

<template>
    <Head title="Dashboard" />

    <GuestLayout>

        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                    <p>{{ test }}</p>
                    <ul>
                        <li 
                            v-for="product in products" 
                            :key="product.id"
                        >{{ product.name }}</li>
                    </ul>
                    <button @click="deleteButton(1)">削除</button>
                    <form @submit.prevent="submit(1)">
                        <button type="submit">削除2</button>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
