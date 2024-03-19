<script setup lang="ts">

</script>

<template>
    <div v-if="$page.props.auth.user">ログイン済み</div>
    <div v-else>未ログイン</div>
    <a v-bind:href="route('vue.create')">新規作成</a>
</template>

<style>

</style>
