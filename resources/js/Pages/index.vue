<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { formToJSON } from 'axios';

import { ref } from "vue";

import { useForm } from '@inertiajs/vue3';

let products = ref([]);



axios.get("/product").then(response => { products.value = response.data });

import { defineComponent } from 'vue'

defineProps({
    test: Number,
});
/*
const form = useForm();

function deleteButton(id: number): void {
    form.delete(route('destroy', id));
}
*/
export const {
  props: {
    id: {
      type: Number,
      //required: true
    }
  },
  methods: {
    async deleteItem(id) {
      try {
        await axios.delete(route('destroy', id));
        // 削除成功時の処理
      } catch (error) {
        // 削除失敗時の処理
      }
    }
  }
}

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
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
