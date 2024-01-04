<script setup lang="ts">
import Index from "./index.vue";
import { ref } from "vue"

let images: string[] = [
    "/storage/images/seven.png",
    "/storage/images/bell.png",
    "/storage/images/cherry.png",
]
let image: string = images[0]
const imageRef = ref(image);

const getRandomImage = (): void => {
    imageRef.value = images[Math.floor(Math.random() * images.length)]
}

const spin = (): void => {

    setTimeout(() => {
        getRandomImage();
        console.log(image);
        spin();
    }, 10);
}
</script>

<template>
<div class="main">
    <section class="panel">
        <img v-bind:src="imageRef">
        <div class="stop">STOP</div>
    </section>
    <section class="panel">
        <img v-bind:src="imageRef">
        <div class="stop">STOP</div>
    </section>

    <section class="panel">
        <img v-bind:src="imageRef">
        <div class="stop">STOP</div>
    </section>
    </div>
    <div class="spin" v-on:click="spin">SPIN</div>
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
        width: 300px;
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

