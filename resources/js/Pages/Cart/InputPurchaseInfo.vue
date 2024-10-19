<script setup lang="ts">
import Layout from '@/Pages/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { computed, ref, onMounted } from "vue";
import { useForm } from '@inertiajs/vue3';
import type { User, Cart } from '@/types/index.d.ts';

import { usePage } from '@inertiajs/vue3';




// Inertia.js でページ情報を取得
const { props } = usePage<{ auth: { user: User }; carts: Cart[] }>();
const user = computed(() => props.auth?.user || null);
const carts = computed(() => props.carts || []);

</script>

<template>
    <Layout title="購入情報">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">購入者情報を入力してください</h2>

        </div>
    </Layout>
</template>