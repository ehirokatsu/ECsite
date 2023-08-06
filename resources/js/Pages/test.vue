<script setup lang="ts">
import {ref} from "vue";

const tmp = "123";
const name = ref("test");

const now = new Date();
const nowStr = now.toLocaleTimeString();
let timeStr = nowStr;
const timeStrRef = ref(nowStr);

function changeTime(): void {
    const newTime = new Date();
    const newTimeStr = newTime.toLocaleTimeString();
    timeStr = newTimeStr;
    timeStrRef.value = newTimeStr;
}
setInterval(changeTime, 1000);


let radius = 1;
let result = 1;
const resultRef = ref(result);
const PI = 3.14;


function changeRadius(): void {
    radius = Math.round(Math.random() * 10);
    result = radius * radius * PI;
    resultRef.value = radius * radius * PI;
}
setInterval(changeRadius, 1000);




</script>

<template>
<h1>{{ name }}</h1>
<h2>{{ tmp }}</h2>
<p>現在時刻：{{ timeStr }}</p>
<p>現在時刻(Ref):{{ timeStrRef }}</p>
<p>半径{{ radius }}の円の面積は円周率{{ PI }}で計算すると{{ result }}。{{ resultRef }}</p>
</template>

<style>

</style>

