<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';


//apiでproductを取得する方法
//let products = ref([]);
//axios.get("/product").then(response => { products.value = response.data });

//Inertia::renderでproductを受け取る
const props = defineProps({
    product: Object,
});

const form = useForm({
    name: props.product.name,
    cost: props.product.cost,
    image: null,
});


//CSRFなしでも削除できた
//indexにリダイレクトしても表示が更新されない
const submitForm = (id: number) => {

    //putだと、画像を選択すると、nameとcostがサーバではnullになってしまう
    form.post(route('vue.update', {'id': id}));
};

const handleImageChange = (event) => {
  form.image = event.target.files[0];
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
