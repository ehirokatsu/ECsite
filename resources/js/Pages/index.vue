<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

import { ref } from "vue";

let products = ref([]);



axios.get("/product").then(response => { products.value = response.data });

import { defineComponent } from 'vue'

defineComponent({
  props: {
    test: Number,
  },
})

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
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
