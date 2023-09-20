<script setup lang="ts">
import {ref, computed, onMounted } from "vue";
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

const snakeHead = computed(
    (): number => {
        return headPositionRef.value.y * width + headPositionRef.value.x;
    }
);

let speed: number = 1000;
let direction: string = "→";
let fruitPosition = 0;
let fruitPositionRef = ref(fruitPosition);

const forwardSnake = ():void => {
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
}
setInterval(
    (): void => {
        forwardSnake();
    },
    speed
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


</script>

<template>
<div class="w-80">
    <div class="flex flex-wrap">
        <div class="w-8 border bg-cyan-300" v-for="field in fields"
        v-bind:class="{
            background: snakeHead == field - 1,
            fruit: fruitPositionRef == field - 1
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
</style>

