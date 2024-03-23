<script setup lang="ts">
import Layout from './Layout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';

//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });

//Inertia::renderでproductを受け取る
const props = defineProps({
    product: Object,
});

const form = useForm({
    id: props.product?.id || "",//submitFormでidを使用するためのダミー。これが無いと警告になる
    name: props.product?.name || "",
    cost: props.product?.cost || "",
    image: null as File | null,
});

//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submitForm = (id: number) => {
    //putだと、画像を選択すると、nameとcostがサーバではnullになってしまう
    form.put(route('vue.update', {'id': id}));
};

const handleImageChange = (event: Event) => {

    //event.targetはnullの可能性があるのでif文判定をする
    const target = event.target as HTMLInputElement;
    if (target && target.files) {
        //右辺はFile型なので、form.imageの初期値をnullにするとエラーになる。
        form.image = target.files[0];
    }
};

/*axiosでputしたが、500エラーでできなかった
const formData = ref({
    name: props.product.name,
    cost: props.product.cost,
    image: null,
});
console.log(props.product.cost);
console.log(formData.value.cost);

const submitForm = async (id: number) => {

    axios.put('/vue/' + id, formData.value)
    .then(response => {
    console.log('Update successful:', response.data);
  })
  .catch(error => {
    console.error('Update error:', error);
  });;
}
const handleImageChange = (event) => {
  formData.image = event.target.files[0];
  console.log(formData.image);
};
*/

</script>

<template>
    <Layout title="商品編集"/>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form @submit.prevent="submitForm(form.id)">
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
                <PrimaryButton type="submit" class="">
                確認する
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
