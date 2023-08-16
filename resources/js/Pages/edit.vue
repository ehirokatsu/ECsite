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

const props = defineProps({
    product: Object,
});

const imageValue = ref(null);

const form = useForm({
    name: props.product.name,
    cost: props.product.cost,
    image: null,
});


//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submitForm = (id: number) => {

    //formData.append('image', imageValue.value.files[0]);
    console.log(form.name);
    console.log(form.cost);
    console.log(form.image);

    //putだと、画像を選択すると、nameとcostがサーバではnullになってしまう
    form.post('/vue/' + id);


    //imageはハンドラで以下を呼び出してformにセットすればいける？

};

const handleImageChange = (event) => {
  form.image = event.target.files[0];
  console.log(form.image);
};




</script>

<template>
    <Head title="Dashboard" />

    <GuestLayout>

        <div class="py-12">
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submitForm(product.id)">
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
                                
                            <span>商品画像</span>
                            <input type="file"  @change="handleImageChange">
                            
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
