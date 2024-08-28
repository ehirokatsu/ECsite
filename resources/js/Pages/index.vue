<script setup lang="ts">
import Layout from './Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from "vue";

//deleteメソッドをフォームで使用する場合
//delete送信用ダミー
import { useForm } from '@inertiajs/vue3';
const form = useForm({
});
const submit = (id: number) => {
    if (window.confirm('本当にこの商品を削除しますか？')) {
        // 削除を実行するロジックをここに追加する
        form.delete(route('vue.destroy', {'id': id}));
    }
}

//検索機能 useFormで実装。
const searchForm = useForm({
    search: ''
});
interface Product {
    id: number;
    name: string;
    cost: number;
    image: string;
}

const props = defineProps<{
    products: Product[];
}>();

const searchWord = ref('');
const searchResults = ref<Product[]>([...props.products]);
/* watchでリアルタイムに検索を行う
watch(searchWord, () => {
    searchForm.get(route('vue.search', { query: searchWord.value }), {
        preserveState: true,
        //戻り値はJSONではなくページ。indexと同様にpropsでproductを受け取る。
        onSuccess: (page) => {
            searchResults.value = (page.props.products as Product[]);
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
});
*/
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
                    v-for="product in searchResults" 
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
                                <Link v-bind:href="route('vue.cart.store')" method="post" :data="{ id: product.id }">カートに入れる</Link>
                                
                                <div class="flex p-2">
                                    <div class="p-1">
                                        <!--文字列連結等の式を入れるにはbindが必要-->
                                        <Link v-bind:href="route('vue.edit', {'id': product.id})">
                                        <!--<a v-bind:href="'/vue/' + product.id +'/edit/'" class="" >-->
                                            <PrimaryButton>編集</PrimaryButton>
                                        </Link>
                                    </div>
                                    <div class="p-1">
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

    </Layout>

</template>
