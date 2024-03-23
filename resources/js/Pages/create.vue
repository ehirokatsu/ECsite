<script setup lang="ts">

import Layout from './Layout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });


/*
const tmp = {
    name: "",
    cost: "",
    image: null as File | null,
};
*/

//useFormを使用する方法
//name,costはTextInputでv-modelを使用する場合、NULLだとエラーになるので空文字にする
//imageは、null as File だけだとnullをFile型にキャストしようとしてエラーになる
// | nullを付けることでnullをFile型またはnull型のどちらかにキャストすることになる。
const form = useForm({
    name: "",
    cost: "",
    image: null as File | null,
});
//CSRFなしでも削除できた
const submitForm = () => {
    form.post(route('vue.store'));
};


//imageはv-modelが使用できないのでイベントハンドラでformにセットする
const handleImageChange = (event: Event) => {
    //form.image = event.target.files[0];

    //event.targetはnullの可能性があるのでif文判定をする
    const target = event.target as HTMLInputElement;
    if (target && target.files) {
        //右辺はFile型なので、form.imageの初期値をnullにするとエラーになる。
        form.image = target.files[0];
        //tmp.image = target.files[0];
    }
    
  //console.log(form.image);
};

</script>

<template>

    <Layout title="商品追加"/>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form @submit.prevent="submitForm()">
                    <div class="">
                        <div class="p-4">
                            <InputLabel>商品名:</InputLabel>
                            <TextInput v-model="form.name" />
                            <InputError v-bind:message="$page.props.errors.name" />
                        </div>
                        <div class="p-4">
                            <InputLabel>単価:</InputLabel>
                            <TextInput v-model="form.cost" />
                            <InputError v-bind:message="$page.props.errors.cost" />
                        </div>
                        <div class="p-4">
                            <InputLabel>商品画像</InputLabel>
                            <input type="file"  @change="handleImageChange">
                            <InputError v-bind:message="$page.props.errors.image" />
                        </div>
                        <!--
                        <Link v-bind:href="route('vue.store')" method="post" v-bind:data="tmp">確認</Link>
                        -->
                        <PrimaryButton type="submit" class="">
                        確認する
                        </PrimaryButton>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
