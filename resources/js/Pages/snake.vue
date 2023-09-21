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

let speed: number = 1000;
let speedRef = ref(speed);
let direction: string = "→";
let fruitPosition: number = 0;
let fruitPositionRef = ref(fruitPosition);
let snakeLength: number = 2;
let snakeLengthRef = ref(snakeLength);
let snakeBody: number[] = [];
let snakeBodyRef = ref(snakeBody);//ref([])だと、includesでエラーが発生する

const forwardSnake = ():void => {

    if (snakeBodyRef.value.length < snakeLengthRef.value) {
        snakeBodyRef.value.push(snakeHeadRef.value);
    }
    if (snakeBodyRef.value.length >= snakeLengthRef.value) {
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

    if (snakeLengthRef.value == 3) {
        speedRef.value = 500;
    }
}
setInterval(
    (): void => {

        if (gameOverRef.value) {
            return;
        }

        forwardSnake();
    },
    speedRef.value
);

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
    }
);

const randomFruit = ():void => {
    fruitPositionRef.value = Math.floor(Math.random() * fields.value);
}

watch(snakeHeadRef,
    ():void => {
        if (snakeHeadRef.value == fruitPositionRef.value) {
            randomFruit();
            snakeLengthRef.value++;
        }
    }
);



</script>

<template>
<div class="w-80">
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

