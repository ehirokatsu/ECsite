<script setup lang="ts">
import Index from "./index.vue";
import { ref } from "vue"

let images: string[] = [
    "/storage/images/seven.png",
    "/storage/images/bell.png",
    "/storage/images/cherry.png",
]

//スロットに表示する画像。HTMLで使用するのでリアクティブにする
let image1: string = images[0]//初期表示は「7」の画像。
const imageRef1 = ref(image1);
let image2: string = images[0]//初期表示は「7」の画像。
const imageRef2 = ref(image2);
let image3: string = images[0]//初期表示は「7」の画像。
const imageRef3 = ref(image3);

//SPINボタンを押下済か判定するフラグ。HTMLで使用するのでリアクティブにする。
let isRunning: boolean = false;
const isRunningRef = ref(isRunning)

const getRandomImage1 = (): void => {
    imageRef1.value = images[Math.floor(Math.random() * images.length)]
}
const getRandomImage2 = (): void => {
    imageRef2.value = images[Math.floor(Math.random() * images.length)]
}
const getRandomImage3 = (): void => {
    imageRef3.value = images[Math.floor(Math.random() * images.length)]
}
let timeoutId1 = 0;
let timeoutId2 = 0;
let timeoutId3 = 0;

let isSelected1: boolean = false;
const isSelected1Ref = ref(isSelected1);
let isSelected2: boolean = false;
const isSelected2Ref = ref(isSelected2);
let isSelected3: boolean = false;
const isSelected3Ref = ref(isSelected3);

const spin = (): void => {

    //SPINボタンが押下されたのでボタンを半透明にするフラグを立てる
    isRunningRef.value = true;

    timeoutId1 = setInterval(() => {
        getRandomImage1();
    }, 10);
    timeoutId2 = setInterval(() => {
        getRandomImage2();
    }, 10);
    timeoutId3 = setInterval(() => {
        getRandomImage3();
    }, 10);
}

const stop1 = (): void => {
    clearInterval(timeoutId1);
    isSelected1Ref.value = true;
}
const stop2 = (): void => {
    clearInterval(timeoutId2);
    isSelected2Ref.value = true;
}
const stop3 = (): void => {
    clearInterval(timeoutId3);
    isSelected3Ref.value = true;
}

</script>

<template>
<div class="main">
    <section class="panel">
        <img v-bind:src="imageRef1">
        <div class="stop" v-bind:class="{inactive: isSelected1Ref}" v-on:click="stop1">STOP</div>
    </section>
    <section class="panel">
        <img v-bind:src="imageRef2">
        <div class="stop" v-bind:class="{inactive: isSelected2Ref}" v-on:click="stop2">STOP</div>
    </section>

    <section class="panel">
        <img v-bind:src="imageRef3">
        <div class="stop" v-bind:class="{inactive: isSelected3Ref}" v-on:click="stop3">STOP</div>
    </section>
    </div>
    <!--isRunningRefがtrueならinactiveクラスを付与する-->
    <div class="spin" v-bind:class="{inactive: isRunningRef}" v-on:click="spin">SPIN</div>
</template>

<style>
    body {
        background: #bdc3c7;
        font-size: 16px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        margin-top: 100px;
    }

    .main {
        width: 320px;
        background: #ecf0f1;
        padding: 20px;
        border: 4px solid #fff;
        border-radius: 12px;
        margin: 16px auto;
        display: flex;
        justify-content: space-between;
    }

    .panel img {
        width: 90px;
        height: 110px;
        margin-bottom: 4px;
    }

    .stop {
        cursor: pointer;
        width: 90px;
        height: 32px;
        background: #ef454a;
        box-shadow: 0 4px 0 #d1483e;
        border-radius: 16px;
        line-height: 32px;
        text-align: center;
        font-size: 14px;
        color: #fff;
        user-select: none;
    }
    .spin {
        cursor: pointer;
        width: 280px;
        height: 36px;
        background: #3498DB;
        box-shadow: 0 4px 0 #2880B9;
        border-radius: 18px;
        line-height: 36px;
        text-align: center;
        color: #fff;
        user-select: none;
        margin: 0 auto;
    }

    .unmatched {
        opacity: 0.5;
    }
    .inactive {
        opacity: 0.5;
    }
</style>

