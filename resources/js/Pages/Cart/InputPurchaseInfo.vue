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
import type { User } from '@/types/index.d.ts';

const props = defineProps<{
    inputUser: User
}>();

const user = props?.inputUser;
console.log(user);

//初期値をコントローラから受け取った入力値
const form = useForm({
    inputUser: {
        name: user?.name || '',
        email: user?.email || '',
        postal_code: user?.postal_code || '',
        address_1: user?.address_1 || '',
        address_2: user?.address_2 || '',
        address_3: user?.address_3 || '',
        phone_number: user?.phone_number || '',
    },
});

const submit = () => {
    form.post(route('vue.cart.confirmPurchaseInfo'));
};

</script>

<template>
    <Layout title="購入情報">
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">購入者情報を入力してください</h2>
            <form @submit.prevent="submit">
                
                <div>
                    <InputLabel for="name" value="名前" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="email" value="メールアドレス" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="postal_code" value="郵便番号" />

                    <TextInput
                        id="postal_code"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.postal_code"
                        required
                        autofocus
                        autocomplete="postal_code"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="address_1" value="住所1" />

                    <TextInput
                        id="address_1"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.address_1"
                        required
                        autofocus
                        autocomplete="address_1"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="address_2" value="住所2" />

                    <TextInput
                        id="address_2"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.address_2"
                        required
                        autofocus
                        autocomplete="address_2"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="address_3" value="住所3" />

                    <TextInput
                        id="address_3"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.address_3"
                        required
                        autofocus
                        autocomplete="address_3"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <div>
                    <InputLabel for="phone_number" value="電話番号" />

                    <TextInput
                        id="phone_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inputUser.phone_number"
                        required
                        autofocus
                        autocomplete="phone_number"
                    />

                    <InputError class="mt-2" :message="form.errors.inputUser" />
                </div>
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    確定
                </PrimaryButton>
            </form>
        </div>
    </Layout>
</template>