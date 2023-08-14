<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { formToJSON } from 'axios';

import { ref, onMounted } from "vue";

import { useForm } from '@inertiajs/vue3';

//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });

const nameValue = ref("");
const costValue = ref("");
const imageValue = ref(null);

/*
const form = useForm({
    name: nameValue,
    cost: costValue,
});
*/
const form = useForm({
    name: null,
    cost: null,
});

//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submitForm = () => {

    const formData = new FormData();
    formData.append('name', nameValue.value);
    formData.append('cost', costValue.value);
    //formData.append('image', imageValue.value.files[0]);


    console.log(nameValue.value);
    console.log(costValue.value);
    console.log(form.name.value);
    //form.name.value = nameValue.value;
    //form.cost.value = costValue.value;
    form.post('/');


    //imageはハンドラで以下を呼び出してformにセットすればいける？

};
/*
const handleImageChange = (event) => {
  form.data.image = event.target.files[0];
};

*/


</script>

<template>
    <Head title="Dashboard" />

    <GuestLayout>

        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitForm()">
                        <div class="">
                            <div class="p-4">
                            <span>商品名:
                            </span>
                            <input type="text" v-model="form.name">
                            </div>
                            <div class="p-4">
                            <span>単価:</span>
                            <input type="text" v-model="form.cost">
                            </div>
                            <div class="p-4">
                                <!--
                            <span>商品画像</span>
                            <input type="file" ref="imageValue" @change="handleImageChange" name="image">
                            -->
                            </div>
                            <button type="submit" class="">
                            確認する
                            </button>
                        </div>
                </form>

                </div>
            </div>
        </div>
    </GuestLayout>
</template>
