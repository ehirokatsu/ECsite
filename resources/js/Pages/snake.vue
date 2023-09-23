<script setup lang="ts">
import {ref, computed, onMounted, watch } from "vue";

//画面サイズの横幅
const width = 10;

//蛇の頭の初期位置
const headPosition:{[key:string]:number} = {
    x: 4,
    y: 3,
};
const headPositionRef = ref(headPosition);

//蛇が動くスピード（msec）
let speed: number = 1000;

//蛇が動く向きの初期値
let direction: string = "→";

//餌の初期位置
const fruitPosition: number = 0;
const fruitPositionRef = ref(fruitPosition);

//蛇の長さ（頭を含める）
let snakeLength: number = 2;

//蛇の身体の位置
const snakeBody: number[] = [];
const snakeBodyRef = ref(snakeBody);//ref([])だと、includesでエラーが発生する

//蛇が動ける範囲
const fieldsRef = computed(
    (): number => {
        return width * width;
    }
);

//蛇の位置がフレームアウトしたかを判定する
const frameOutRef = computed(
    () => {
        const x = headPositionRef.value.x;
        const y = headPositionRef.value.y;

        return x < 0 || width <= x || y < 0 || width <= y;
    }
); 

//蛇が自分の身体を食べたかを判定する
const collidedRef = computed(
    () => {
        return snakeBodyRef.value.includes(snakeHeadRef.value);
    }
);

//ゲームオーバーを判定する
const gameOverRef = computed(
    () => {
        return collidedRef.value || frameOutRef.value
    }
);

//蛇の頭の位置をフィールドに当てはめる
const snakeHeadRef = computed(
    (): number => {

        if (frameOutRef.value) {
            return -1;
        }

        return headPositionRef.value.y * width + headPositionRef.value.x;
    }
);

//蛇が食べた餌の数
const scoreRef = computed(
    (): number => {
        return snakeBodyRef.value.length - 1;
    }
);

//蛇を動かす処理
const forwardSnake = ():void => {

    //蛇の身体を長さに応じて増やす
    if (snakeBodyRef.value.length < snakeLength) {
        snakeBodyRef.value.push(snakeHeadRef.value);
    }
    if (snakeBodyRef.value.length >= snakeLength) {
        snakeBodyRef.value.shift();
    }

    switch (direction) {
        case "→": headPositionRef.value.x++;
        break;
        case "←": headPositionRef.value.x--;
        break;
        case "↑": headPositionRef.value.y--;
        break;
        case "↓": headPositionRef.value.y++;
        break;
    }

    //蛇の長さが4以上になったらスピードを上げる
    if (snakeLength == 4) {
        speed = 700;
    }
}

//蛇を動かす処理をspeed毎に行う
const moveSnake = (): void => {
    if (gameOverRef.value) {
        return;
    }

    forwardSnake();
    setTimeout(moveSnake, speed);
}

//キーダウンイベントハンドラ
const keydownEvent = (e: KeyboardEvent): void => {
    //console.log(e.key);
    switch (e.key) {
        case "ArrowUp": direction = "↑";
        break;
        case "ArrowDown": direction = "↓";
        break;
        case "ArrowLeft": direction = "←";
        break;
        case "ArrowRight": direction = "→";
        break;
    }
}

//餌の位置をランダムに配置する
const randomFruit = ():void => {
    fruitPositionRef.value = Math.floor(Math.random() * fieldsRef.value);
}

//ゲーム再スタート時の初期化処理
const init = (): void => {
    headPositionRef.value.x = 3;
    headPositionRef.value.y = 4;
    snakeLength = 2;
    snakeBodyRef.value = [];
    speed = 1000;
    direction = "→";
    moveSnake();
    randomFruit();
}

//蛇の頭の位置が更新されるごとに行う処理
watch(snakeHeadRef,
    ():void => {

        //蛇が餌を食べた場合
        if (snakeHeadRef.value == fruitPositionRef.value) {

            //餌の位置を更新する
            randomFruit();

            //蛇の長さを増やす
            snakeLength++;
        }
    }
);

//WEBページがマウントされたときに行う初期化処理
onMounted(
    () => {
      window.addEventListener('keydown', keydownEvent);
      moveSnake();
      randomFruit();
    }
);

</script>

<template>
<div class="w-80">

    <p class="flex justify-center">蛇が食べたフルーツの数は{{ scoreRef }}です。</p>

    <div class="flex flex-wrap">

        <div class="w-8 border bg-cyan-300"
        v-for="field in fieldsRef"
        v-bind:key="field"
        v-bind:class="{
            background: snakeHeadRef == field - 1,
            fruit: fruitPositionRef == field - 1,
            addBody: snakeBodyRef.includes(field - 1)

        }">
            {{ field - 1 }}

        </div>
    </div>
    <div v-if="gameOverRef" class="flex flex-col items-center">
        <p>GAME OVER</p>
        <button v-on:click="init()">もう一度プレイする。</button>
    </div>
</div>

</template>

<style>
    .background{
        background-color:gold;
    }
    .fruit{
        background-color: pink;
    }
    .addBody{
        background-color: black;
    }

</style>

