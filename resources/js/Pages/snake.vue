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
<div class="w-80">
    <div class="flex flex-wrap">
        <div class="w-8 border bg-cyan-300" v-for="field in fields">
            {{ field - 1 }}
            <br v-if="field==10">
        </div>
    </div>
</div>

</template>

<style>

</style>

