<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

//Inertia::renderでproductを受け取る
const props = defineProps({
    product: Object,
});

//Linkでフォーム送信する場合
import { Link } from '@inertiajs/vue3';
const tmp = {
    id: props.product?.id || "",
    name: props.product?.name || "",
    cost: props.product?.cost || "",
    image: null as File | null,
    //image: props.product?.image || null,
};

const imageName = props.product?.image || null;

// プレビュー画像のURLを保持するためのリアクティブな参照を作成
// 初期値は、現在の商品画像
const imageUrl = ref("/storage/" + imageName);

const handleImageChange = (event: Event) => {

    //event.targetはnullの可能性があるのでif文判定をする
    const target = event.target as HTMLInputElement;
    if (target && target.files) {
        //右辺はFile型なので、form.imageの初期値をnullにするとエラーになる。
        tmp.image = target.files[0];

        // 選択された画像のURLを生成
        imageUrl.value = URL.createObjectURL(target.files[0]);
    }
};


</script>

<template>
    <Layout title="商品編集">

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="">
                <div class="p-4">
                    <InputLabel>商品名:</InputLabel>
                    <TextInput v-model="tmp.name" />
                    <InputError v-bind:message="$page.props.errors.name" />
                </div>
                <div class="p-4">
                    <InputLabel>単価:</InputLabel>
                    <TextInput v-model="tmp.cost" />
                    <InputError v-bind:message="$page.props.errors.cost" />
                </div>
                <div class="p-4">
                    <InputLabel>商品画像</InputLabel>
                    <input type="file"  @change="handleImageChange">
                    <InputError v-bind:message="$page.props.errors.image" />
                </div>
                <!-- プレビュー画像を表示 -->
                <div v-if="imageUrl">
                    <img :src="imageUrl" alt="Image preview" style="width: 200px;">
                </div>
                
                <!--Linkでputだと、テキストのみ可能。ファイル送信すると、request全てがNULLになってしまう。-->
                <Link v-bind:href="route('vue.ajaxlink.update', {'id': tmp.id})" method="put" v-bind:data="tmp">更新</Link>

                <Link as="button" class="p-4" v-bind:href="route('vue.ajaxlink.index')">
                    <SecondaryButton>戻る</SecondaryButton>
                </Link>
            </div>
    </div>
    </Layout>
</template>
