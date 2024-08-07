<script setup lang="ts">

import Layout from '@/Pages/Layout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, onMounted  } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import { AxiosError } from 'axios';

const csrfToken = ref('');
const errorMessage = ref("");
const errorResponse = ref({
    name: "",
    cost: "",
    image: "",
});

//FormRequestからのエラーメッセージは次のようにresponseに格納される
//{message: "商品名を入力してください (and 2 more errors)", errors: {name: ["商品名を入力してください"], cost: ["単価を入力してください"], image: ["画像を入力してください"]}}
interface ValidationErrors {
    message: string;
    errors: {
        [key: string]: string[];
    };
}

// CSRFトークンの取得。無くてもできる時、できない時がある。
const fetchCsrfToken = async () => {
    try {
        const response = await axios.get('/api/csrf-token');
        csrfToken.value = response.data.token;
        console.log(csrfToken.value);
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.value;
    } catch (error) {
        console.error("Failed to fetch CSRF token:", error);
        errorMessage.value = "CSRFトークンの取得に失敗しました。";
    }
};

// 初期データの取得
onMounted(() => {
    fetchCsrfToken();
});

//name,costはTextInputでv-modelを使用する場合、NULLだとエラーになるので空文字にする
//imageは、null as File だけだとnullをFile型にキャストしようとしてエラーになる
// | nullを付けることでnullをFile型またはnull型のどちらかにキャストすることになる。
const form = ref({
    name: "",
    cost: "",
    image: null as File | null,
});

//store処理
const submitForm = async () => {

    //エラーメッセージのリセット
    errorMessage.value = "";
    errorResponse.value = {
        name: "",
        cost: "",
        image: "",
    };

    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('cost', form.value.cost);
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    try {

        await axios.post(route('api.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        // 成功時の処理。indexにリダイレクトさせる
        window.location.href = route('vue.ajaxaxios.index');

    } catch (error) {

        //型アサーションでエラー回避
        const axiosError = error as AxiosError;
        if (axiosError.response?.status === 422) {
            // バリデーションエラーの場合
            const responseErrors = axiosError.response.data as ValidationErrors;
            errorMessage.value = "入力エラーがあります。";
            errorResponse.value.name = responseErrors.errors.name ? responseErrors.errors.name[0] : "";
            errorResponse.value.cost = responseErrors.errors.cost ? responseErrors.errors.cost[0] : "";
            errorResponse.value.image = responseErrors.errors.image ? responseErrors.errors.image[0] : "";
            console.log(responseErrors);
        } else {
            console.error('Form submission failed:', axiosError);
            console.error('Error details:', axiosError.response?.data);
        }
    }
};

// プレビュー画像のURLを保持するためのリアクティブな参照を作成
const imageUrl = ref("");

//imageはv-modelが使用できないのでイベントハンドラでformにセットする
const handleImageChange = (event: Event) => {
    //form.image = event.target.files[0];

    //event.targetはnullの可能性があるのでif文判定をする
    const target = event.target as HTMLInputElement;
    if (target && target.files) {
        //右辺はFile型なので、form.imageの初期値をnullにするとエラーになる。
        form.value.image = target.files[0];

        // 選択された画像のURLを生成
        imageUrl.value = URL.createObjectURL(target.files[0]);
    }
    
  //console.log(form.image);
};

</script>

<template>

    <Layout title="商品追加">

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
        <!-- エラーメッセージを表示する部分 -->
        <div v-if="errorMessage" class="error-message">
            {{ errorMessage }}
        </div>
        <form @submit.prevent="submitForm()">
            <div class="">
                <div class="p-4">
                    <InputLabel>商品名:</InputLabel>
                    <TextInput v-model="form.name" />
                    <InputError v-bind:message="errorResponse.name" />
                </div>
                <div class="p-4">
                    <InputLabel>単価:</InputLabel>
                    <TextInput v-model="form.cost" />
                    <InputError v-bind:message="errorResponse.cost" />
                </div>
                <div class="p-4">
                    <InputLabel>商品画像</InputLabel>
                    <input type="file"  @change="handleImageChange">
                    <InputError v-bind:message="errorResponse.image" />
                </div>
                <!-- プレビュー画像を表示 -->
                <div v-if="imageUrl">
                    <img :src="imageUrl" alt="Image preview" style="width: 200px;">
                </div>
                <PrimaryButton type="submit" class="p-4">
                登録する
                </PrimaryButton>
                <Link as="button" class="p-4" v-bind:href="route('vue.ajaxaxios.index')">
                    <SecondaryButton>戻る</SecondaryButton>
                </Link>
            </div>
        </form>

    </div>
    </Layout>
</template>
