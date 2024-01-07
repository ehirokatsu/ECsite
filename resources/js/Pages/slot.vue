<script setup lang="ts">
import { ref, computed } from "vue"

//スロット用の絵柄
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

//スロットの回転速度（ミリ秒）
const intervalValue: number = 1000;

//絵柄をランダムに取得
const getRandomImage = (): string => images[Math.floor(Math.random() * images.length)]

//delayミリ秒毎にcallback関数を実行する
const createInterval = (callback: () => void, delay: number): number => setInterval(callback, delay)

//絵柄を止める
const clearTimer = (timerId: number): void => clearInterval(timerId)

//画面に表示する絵柄
const imageRefs = ref(new Array(images.length).fill(images[0]))

//STOPボタンを押下した時の絵柄（絵柄が揃ったかの確認用）
const imageSelectedRefs = ref(new Array(images.length).fill(""));

//SPINボタンを押下したか判定しボタンを半透明にする用
const isSpinRunningRef = ref(false)

//STOPボタンを押下したか判定しボタンを半透明にする用
const isStopSelectedRefs = ref(new Array(images.length).fill(true))

//絵柄が揃ったか判定する用
const isSuccessRef = ref(false)

//REPLAYボタンを押下したか判定しボタンを半透明にする用
const isReplayRef = ref(true)

//各スロットのsetIntervalのID格納用。clearTimerで使用する
let spinIntervals: number[] = new Array(images.length).fill(0);

//
const updateImage = (index: number, timerId: number): void => {
    imageRefs.value[index] = getRandomImage();
    if (index === images.length - 1) isComplete();
    if (isStopSelectedRefs.value[index]) clearTimer(timerId);
};

//SPINボタン押下時
const spin = (): void => {

    //既に押下済なら何もしない
    if (isSpinRunningRef.value) return;

    //SPINボタンを半透明にする
    isSpinRunningRef.value = true;

    //STOPボタンを押下可能にする
    isStopSelectedRefs.value.fill(false);

    //images配列の数だけ繰り返し、かつ戻り値を得たいからこの書き方にしている
    spinIntervals = Array.from({ length: images.length }, (_, index) =>
        createInterval(() => updateImage(index, spinIntervals[index]), intervalValue)
    );

}

//STOPボタン押下時
const stop = (index: number): void => {

    //既に押下済なら何もしない
    if (isStopSelectedRefs.value[index]) return;

    //絵柄を止める
    clearTimer(spinIntervals[index]);
    
    //STOPボタンを半透明にする
    isStopSelectedRefs.value[index] = true;

    //現在の絵柄を判定用変数に格納する
    imageSelectedRefs.value[index] = imageRefs.value[index];
    isComplete();
}


const isComplete = (): void => {
    
    //全ての絵柄が同じ
    if (
        imageSelectedRefs.value.every((selected) => selected === imageSelectedRefs.value[0] && selected !== "")

    ) {
        isSuccessRef.value = true;
    }

    //全てのSTOPボタンが押下済（true）ならREPLAYボタンを押下可能にする
    if (isStopSelectedRefs.value.every((selected) => selected)) {
        isReplayRef.value = false;
    }
}

//REPLAYボタン押下時
const replay = (): void => {

    //既に押下済なら何もしない
    if (isReplayRef.value) return;

    //SPINボタンを押下可能にする
    isSpinRunningRef.value = false;

    //STOPボタン、REPLAYボタンを半透明にする
    isStopSelectedRefs.value.fill(true);
    isReplayRef.value = true;

    //絵柄を初期表示にする
    imageRefs.value.fill(images[0]);

    //絵柄選択用配列を初期化
    imageSelectedRefs.value.fill("");

    //絵柄が揃ったかの判定用を初期化
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
