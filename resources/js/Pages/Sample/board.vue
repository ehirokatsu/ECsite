<script setup lang="ts">
import {ref, computed} from "vue";

//投稿者
const name = ref("testName");

//新規投稿用
const createText = ref("");

//更新対象の投稿ID
const updateId = ref(1); 

//更新投稿用
const updateText = ref(""); 

//削除対象の投稿ID
const deleteId = ref(1); 

interface board {
    name: string,//投稿者
    text: string,//投稿内容
}

//投稿用ID
let idCount = 1;

const boardMapList = new Map<number, board>();
const boardMap = ref(boardMapList);

//idCount++でカウントアップする。処理実行後に1が加算される。
boardMap.value.set(idCount++, {name: "name1", text: "text1"});
boardMap.value.set(idCount++, {name: "name2", text: "text2"});
boardMap.value.set(idCount++, {name: "name3", text: "text3"});

//新規投稿ボタン押下
const createPost = (): void => {
    boardMap.value.set(idCount++, {name: name.value, text: createText.value});
};
//console.log(boardMap.value.keys());
//console.log(boardMapList.keys());

//更新ボタン押下
const updatePost = (): void => {
    //更新したい値のキーと、更新後の値を設定する
    boardMap.value.set(updateId.value, {name: name.value, text: updateText.value});
};

//削除ボタン押下
const deletePost = (): void => {
    boardMap.value.delete(deleteId.value);
};



</script>

<template>
<h1>Board</h1>

<p>名前</p>
<input type="text" v-model="name">
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
<input type="text" v-model="createText">
<button v-on:click="createPost">新規作成</button>
<br>

<p>変更</p>
<input type="number" v-model="updateId">
<input type="text" v-model="updateText">
<button v-on:click="updatePost">更新</button>
<br>

<p>削除</p>
<input type="number" v-model="deleteId">
<button v-on:click="deletePost">削除</button>
<br>

</template>

<style>

</style>

