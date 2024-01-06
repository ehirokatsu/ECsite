<script setup lang="ts">
import { ref, computed } from "vue"

let images: string[] = [
    "/storage/images/ebi.png",
    "/storage/images/pen.gif",
    "/storage/images/siro.jpg",
    /*
    "/storage/images/seven.png",
    "/storage/images/bell.png",
    "/storage/images/cherry.png",
    */
]

const getRandomImage = (): string => images[Math.floor(Math.random() * images.length)]

const createInterval = (callback: () => void, delay: number): number => setInterval(callback, delay)
const clearTimer = (timerId: number): void => clearInterval(timerId)

const imageRefs = ref(images.map(() => images[0]))
const imageSelectedRefs = ref(images.map(() => ""))
const isSpinRunningRef = ref(false)
const isStopSelectedRefs = ref([true, true, true])
const isSuccessRef = ref(false)
const isReplayRef = ref(true)

let spinInterval1: number = 0;
let spinInterval2: number = 0;
let spinInterval3: number = 0;

const updateImage = (index: number, timerId: number): void => {
    imageRefs.value[index] = getRandomImage();
    if (index === 2) isComplete();
    if (isStopSelectedRefs.value[index]) clearTimer(timerId);
};

const spin = (): void => {
    if (isSpinRunningRef.value) return;

    isSpinRunningRef.value = true;
    isStopSelectedRefs.value = [false, false, false];

    spinInterval1 = createInterval(() => updateImage(0, spinInterval1), 1000);
    spinInterval2 = createInterval(() => updateImage(1, spinInterval2), 1000);
    spinInterval3 = createInterval(() => updateImage(2, spinInterval3), 1000);
}

const stop = (index: number): void => {
    if (isStopSelectedRefs.value[index]) return;

    const timerId = index === 0 ? spinInterval1 : index === 1 ? spinInterval2 : spinInterval3;
    clearTimer(timerId);
    
    isStopSelectedRefs.value[index] = true;
    imageSelectedRefs.value[index] = imageRefs.value[index];
    isComplete();
}

const isComplete = (): void => {
    console.log(imageSelectedRefs.value);
    if (
        imageSelectedRefs.value[0] === imageSelectedRefs.value[1]
        && imageSelectedRefs.value[1] === imageSelectedRefs.value[2]
        && imageSelectedRefs.value[0] !== ""
        && imageSelectedRefs.value[1] !== ""
        && imageSelectedRefs.value[2] !== ""
    ) {
        isSuccessRef.value = true;
    }

    if (isStopSelectedRefs.value.every((selected) => selected)) {
        isReplayRef.value = false;
    }
}

const replay = (): void => {
    if (isReplayRef.value) return;

    isSpinRunningRef.value = false;
    isStopSelectedRefs.value = [true, true, true];
    isReplayRef.value = true;

    imageRefs.value = images.map(() => images[0]);
    imageSelectedRefs.value = images.map(() => "");
    isSuccessRef.value = false;
}

</script>

<template>
<div class="main">
    <section class="panel" v-for="(image, index) in imageRefs" :key="index">
        <img :src="image">
        <div class="stop" :class="{ inactive: isStopSelectedRefs[index] }" @click="() => stop(index)">STOP</div>
    </section>
</div>
<div class="spin" :class="{ inactive: isSpinRunningRef }" @click="spin">SPIN</div>
<div class="replay" :class="{ inactive: isReplayRef }" @click="replay">REPLAY</div>
<div class="result" v-if="isSuccessRef">
    おめでとう！
</div>
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
        margin:0  auto;
    }
    .replay {
        cursor: pointer;
        width: 260px;
        height: 36px;
        background: #79d063;
        box-shadow: 0 4px 0 #03ab28;
        border-radius: 18px;
        line-height: 36px;
        text-align: center;
        color: #fff;
        user-select: none;
        margin:10px  auto;
    }
    .unmatched {
        opacity: 0.5;
    }
    .inactive {
        opacity: 0.5;
    }
    .result {
        text-align: center;
    }
</style>
