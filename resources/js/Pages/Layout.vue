<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';

defineProps({ title: String })

/*
//ログアウトはPOSTメソッドを使用する
const form = useForm({});
const logout = (): void => {
    form.post(route('logout'));
}
*/
</script>

<template>
    <Head :title="title" />

    <header>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    MyECサイト
                </h1>
                <div v-if="$page.props.auth.user">
                    ようこそ {{ $page.props.auth.user.name }} さま
                </div>
                <div v-else>
                    ようこそ ゲスト さま
                </div>
            </div>
            <nav class="flex justify-between">
                <div class="flex">
                    <NavLink v-bind:href="route('vue.index')">トップ</NavLink>
                    <NavLink v-bind:href="route('cart.index')">カート</NavLink>
                    <NavLink v-bind:href="route('contact.index')">問合せ</NavLink>
                    <NavLink v-bind:href="route('vue.index')">注文履歴</NavLink>
                    <NavLink v-bind:href="route('vue.index')">アカウント編集</NavLink>
                    <NavLink v-bind:href="route('vue.create')">商品追加</NavLink>
                </div>
                <div class="flex justify-end">
                    <NavLink v-bind:href="route('index')">Bladeトップ</NavLink>
                    <NavLink v-bind:href="route('vue.index')">Vueトップ</NavLink>
                    <NavLink v-bind:href="route('vue.ajax.index')">VueAjaxトップ</NavLink>
                </div>
                <div v-if="$page.props.auth.user">
                    <NavLink v-bind:href="route('logout')" method="post" as="button">ログアウト</NavLink>
                    <!--<button v-on:click="logout">ログアウト</button>-->
                </div>
                <div v-else>
                    <NavLink v-bind:href="route('register')">登録</NavLink>
                    <NavLink v-bind:href="route('login')">ログイン</NavLink>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <slot />  
    </main>
    <footer class="flex justify-center">
        <small>&copy; 2024 EC Site</small>
    </footer>

</template>

<style>

</style>
