<script setup lang="ts">
import Index from "./index.vue";
import { ref } from "vue"

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

//スロットに表示する画像。HTMLで使用するのでリアクティブにする
let image1: string = images[0]//初期表示は「7」の画像。
const imageRef1 = ref(image1);
let image2: string = images[0]//初期表示は「7」の画像。
const imageRef2 = ref(image2);
let image3: string = images[0]//初期表示は「7」の画像。
const imageRef3 = ref(image3);

//SPINボタンを押下済か判定するフラグ。HTMLで使用するのでリアクティブにする。
let isSpinRunning: boolean = false;
const isSpinRunningRef = ref(isSpinRunning)

let timeoutId1 = 0;
let timeoutId2 = 0;
let timeoutId3 = 0;

//STOPボタンを押下済か判定するフラグ。HTMLで使用するのでリアクティブにする。
let isStopSelected1: boolean = true;
const isStopSelected1Ref = ref(isStopSelected1);
let isStopSelected2: boolean = true;
const isStopSelected2Ref = ref(isStopSelected2);
let isStopSelected3: boolean = true;
const isStopSelected3Ref = ref(isStopSelected3);

//REPLAYボタンを押下済か判定するフラグ。HTMLで使用するのでリアクティブにする。
//初期表示では押下不可のためtrueにしておく
let isReplay: boolean = true;
const isReplayRef = ref(isReplay);

//絵柄が揃ったかの確認用
let image1Selected = "";
let image2Selected = "";
let image3Selected = "";
let isSuccess: boolean = false;
const isSuccessRef = ref(isSuccess);

//SPINボタン押下後に画像を取得する処理
const getRandomImage1 = (): void => {
    imageRef1.value = images[Math.floor(Math.random() * images.length)]
}
const getRandomImage2 = (): void => {
    imageRef2.value = images[Math.floor(Math.random() * images.length)]
}
const getRandomImage3 = (): void => {
    imageRef3.value = images[Math.floor(Math.random() * images.length)]
}

//SPINボタン押下時の処理
const spin = (): void => {

    //SPINボタンを押下済みなら、2度押ししても何もしない
    if (isSpinRunningRef.value) {
        return;
    }

    //SPINボタンが押下されたのでボタンを半透明にするフラグを立てる
    isSpinRunningRef.value = true;

    //STOPボタンを押下可能にする
    isStopSelected1Ref.value = false;
    isStopSelected2Ref.value = false;
    isStopSelected3Ref.value = false;

    timeoutId1 = setInterval(() => {
        getRandomImage1();
    }, 1000);
    timeoutId2 = setInterval(() => {
        getRandomImage2();
    }, 1000);
    timeoutId3 = setInterval(() => {
        getRandomImage3();
    }, 1000);
}

const stop1 = (): void => {

    //押下済なら2度押ししても何もしない
    if (isStopSelected1Ref.value) {
        return;
    }

    clearInterval(timeoutId1);
    isStopSelected1Ref.value = true;
    image1Selected = imageRef1.value;
    isComplete();
    
}
const stop2 = (): void => {

    //押下済なら2度押ししても何もしない
    if (isStopSelected2Ref.value) {
        return;
    }

    clearInterval(timeoutId2);
    isStopSelected2Ref.value = true;
    image2Selected = imageRef2.value;
    isComplete();
}
const stop3 = (): void => {

    //押下済なら2度押ししても何もしない
    if (isStopSelected3Ref.value) {
        return;
    }

    clearInterval(timeoutId3);
    isStopSelected3Ref.value = true;
    image3Selected = imageRef3.value;
    isComplete();
}

//全てのSTOPボタンを押下したか判定する
const isComplete = (): boolean => {

    //絵柄が揃ったか確認する
    if (image1Selected == image2Selected
        && image2Selected == image3Selected
        && image1Selected != ""
        && image2Selected != ""
        && image3Selected != "") {
            isSuccessRef.value = true;

    }

    if (isStopSelected1Ref.value == true
        && isStopSelected2Ref.value == true
        && isStopSelected3Ref.value == true) {

            //全て押下したらREPLAYボタンを押下可能にする
            isReplayRef.value = false;
            return true;
    }

    return false;
}

// Replayボタン押下時の処理
const replay = (): void => {

    //押下済なら2度押ししても何もしない
    if (isReplayRef.value) {
        return;
    }

    //SPIN,STOPボタンを押下可能に、REPLAYボタンを押下不可にする
    isSpinRunningRef.value = false;
    isStopSelected1Ref.value = true;
    isStopSelected2Ref.value = true;
    isStopSelected3Ref.value = true;
    isReplayRef.value = true;

    //スロットの絵柄を初期表示にする
    imageRef1.value = images[0];
    imageRef2.value = images[0];
    imageRef3.value = images[0];

    //絵柄確認用の変数を初期化
    isSuccessRef.value = false;
    image1Selected = "";
    image2Selected = "";
    image3Selected = "";

}

</script>

<template>
<div class="main">
    <section class="panel">
        <img v-bind:src="imageRef1">
        <div class="stop" v-bind:class="{inactive: isStopSelected1Ref}" v-on:click="stop1">STOP</div>
    </section>
    <section class="panel">
        <img v-bind:src="imageRef2">
        <div class="stop" v-bind:class="{inactive: isStopSelected2Ref}" v-on:click="stop2">STOP</div>
    </section>

    <section class="panel">
        <img v-bind:src="imageRef3">
        <div class="stop" v-bind:class="{inactive: isStopSelected3Ref}" v-on:click="stop3">STOP</div>
    </section>
    </div>
    <!--isRunningRefがtrueならinactiveクラスを付与する-->
    <div class="spin" v-bind:class="{inactive: isSpinRunningRef}" v-on:click="spin">SPIN</div>
    <div class="replay" v-bind:class="{inactive: isReplayRef}" v-on:click="replay">REPLAY</div>
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
