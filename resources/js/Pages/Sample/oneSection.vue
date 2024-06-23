<script setup lang="ts">
import {ref, computed} from "vue";

interface Props {
    rand: number;
}

//createNewRandという型のevent変数に、onCreateNewRandが格納される。
interface Emits {
    (event: "createNewRand"): void;
}

defineProps<Props>();

const emit = defineEmits<Emits>();

const onNewRandButtonClick = (): void => {
    emit("createNewRand");
}


</script>

<template>
<section>
    <p>子コンポーネントで乱数を表示：{{ rand }}</p>
    <button v-on:click="onNewRandButtonClick">新たな乱数を発生</button>
</section>

</template>

<style>

</style>

