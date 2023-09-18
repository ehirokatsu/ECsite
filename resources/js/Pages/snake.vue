<script setup lang="ts">
import {ref, computed} from "vue";
import Index from "./index.vue";

const width = 10;
const fields = computed(
    (): number => {
        return width * width;
    }
);

</script>

<template>
<div class="flex flex-wrap">
    <div class="w-30 h-10 border" v-for="field in fields">
        {{ field - 1 }}
        <div v-if="field%10==0">
            <br>
        </div>
    </div>
</div>
</template>

<style>

</style>

