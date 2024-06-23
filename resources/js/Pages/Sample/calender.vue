<script setup lang="ts">
import {ref, computed} from "vue";

const now = new Date();
//const nowRef = ref(now);
const year = now.getFullYear();


</script>

<template>
<h1>Calender</h1>
{{ now }}
{{ year }}

</template>

<style>

</style>

