<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';

const form = useForm({});

const logout = (): void => {
    form.post(route('logout'));
}

</script>

<template>
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            MyECサイト
        </h2>
        <div v-if="$page.props.auth.user">
            ようこそ {{ $page.props.auth.user.name }} さま
        </div>
        <div v-else>
            <h2>ようこそ ゲスト さま</h2>
        </div>
    </div>
    <div class="flex justify-between">
        <div class="flex">
            <NavLink v-bind:href="route('vue.index')">トップ</NavLink>
            <NavLink v-bind:href="route('cart.index')">カート</NavLink>
            <NavLink v-bind:href="route('contact.index')">問合せ</NavLink>
            <NavLink v-bind:href="route('vue.index')">注文履歴</NavLink>
            <NavLink v-bind:href="route('vue.index')">アカウント編集</NavLink>
            <NavLink v-bind:href="route('vue.create')">商品追加</NavLink>
        </div>
        <div class="flex justify-end">
        </div>
        <div v-if="$page.props.auth.user">
            <button v-on:click="logout">ログアウト</button>
        </div>
        <div v-else>
            <NavLink v-bind:href="route('register')">登録</NavLink>
            <NavLink v-bind:href="route('login')">ログイン</NavLink>
        </div>
    </div>
</template>

<style>

</style>
