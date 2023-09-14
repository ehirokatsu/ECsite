<script setup lang="ts">
import {ref, computed} from "vue";

interface Props {
    value: string;
}
defineProps<Props>();

</script>

<template>
    <label >{{ value }}</label>

</template>

<style>

</style>

