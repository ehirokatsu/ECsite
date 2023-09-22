<script setup lang="ts">
import {ref, computed, onMounted, watch } from "vue";
import Index from "./index.vue";

const width = 10;
const fields = computed(
    (): number => {
        return width * width;
    }
);
let headPosition:{[key:string]:number} = {
    x: 4,
    y: 3,
};
let headPositionRef = ref(headPosition);

let speed: number = 1000;
let direction: string = "→";
let fruitPosition: number = 0;
let fruitPositionRef = ref(fruitPosition);
let snakeLength: number = 2;
let snakeBody: number[] = [];
let snakeBodyRef = ref(snakeBody);//ref([])だと、includesでエラーが発生する
let gameState: boolean = false;
let gameStateRef = ref(gameState);


const frameOutRef = computed(
    () => {
        const x = headPositionRef.value.x;
        const y = headPositionRef.value.y;

        return x < 0 || width <= x || y < 0 || width <= y;
    }
); 

const collidedRef = computed(
    () => {
        return snakeBodyRef.value.includes(snakeHeadRef.value);
    }
);

const gameOverRef = computed(
    () => {
        gameStateRef.value = false;
        return collidedRef.value || frameOutRef.value
    }
);

const snakeHeadRef = computed(
    (): number => {

        if (frameOutRef.value) {
            return -1;
            //console.log("frameout");
        }

        return headPositionRef.value.y * width + headPositionRef.value.x;
    }
);


const scoreRef = computed(
    (): number => {
        return snakeBodyRef.value.length - 1;
    }
);

const forwardSnake = ():void => {

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
    //console.log(snakeHead);

    if (snakeLength == 4) {
        speed = 700;
    }
}
/*
setInterval(
    (): void => {

        if (gameOverRef.value) {
            return;
        }

        forwardSnake();
    },
    speed
);
*/
function moveSnake() {
    if (gameOverRef.value) {
        return;
    }

    forwardSnake();
    setTimeout(moveSnake, speed);
}



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

onMounted(
    () => {
      window.addEventListener('keydown', keydownEvent);
      moveSnake();
      randomFruit();
    }
);

const randomFruit = ():void => {
    fruitPositionRef.value = Math.floor(Math.random() * fields.value);
}

watch(snakeHeadRef,
    ():void => {
        if (snakeHeadRef.value == fruitPositionRef.value) {
            randomFruit();
            snakeLength++;
        }
    }
);

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


</script>

<template>
<div class="w-80">
    <p class="flex justify-center">蛇が食べたフルーツの数は{{ scoreRef }}です。</p>
    <div class="flex flex-wrap">
        <div class="w-8 border bg-cyan-300" v-for="field in fields"
        v-bind:class="{
            background: snakeHeadRef == field - 1,
            fruit: fruitPositionRef == field - 1,
            addBody: snakeBodyRef.includes(field - 1)

        }">
            {{ field - 1 }}

        </div>
    </div>
    <div v-if="gameOverRef.valueOf">
        <p>GAME OVER</p>
        <button  v-if="gameOverRef.valueOf" v-on:click="init()">もう一度プレイする。</button>
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

