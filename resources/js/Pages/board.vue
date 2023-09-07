<script setup lang="ts">
import {ref, computed} from "vue";
import Index from "./index.vue";

const id = ref(1); 
const name = ref("testName");
const text = ref("");

interface board {
    //id: number
    name: string,
    text: string,
}

const boardMap = new Map<number, board>();
boardMap.set(1, {"name1", "text1"});


</script>

<template>
<h1>Board</h1>

<p>名前</p>
<input type="text">
<br>
<p>一覧表示</p>
<br>

<p>新規作成</p>
<br>

<p>変更</p>
<br>

<p>削除</p>
<br>

</template>

<style>

</style>

