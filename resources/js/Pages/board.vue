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
let idCount = 1;
const boardMapList = new Map<number, board>();
const boardMap = ref(boardMapList);
boardMap.value.set(idCount++, {name: "name1", text: "text1"});
boardMap.value.set(idCount++, {name: "name2", text: "text2"});
boardMap.value.set(idCount++, {name: "name3", text: "text3"});


const create = (): void => {
    console.log("click");
    boardMap.value.set(idCount++, {name: "name", text: text.value});
};


</script>

<template>
<h1>Board</h1>

<p>名前</p>
<input type="text">
<br>
<p>一覧表示</p>
<ul>
    <li
        v-for="[id, board] in boardMap"
        v-bind:key="id">
            id:{{ id }}.名前：{{ board.name }}。テキスト:{{ board.text }}
    </li>
</ul>
<br>

<p>新規作成</p>
<input type="text" v-model="text">
<button v-on:click="create">新規作成</button>
<br>

<p>変更</p>
<br>

<p>削除</p>
<br>

</template>

<style>

</style>

