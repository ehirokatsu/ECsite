<script setup lang="ts">
import {ref, computed} from "vue";

interface Props {
    id: number;
    name: string;
    email: string;
    points: number;
    note?: string;
}

interface Emits {
    (event: "update:points", points: number): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

//const localPoints = ref(props.points);

const localNote = computed(
    (): string => {
        let localNote = props.note;
        if (localNote == undefined) {
            localNote = "--";
        }
        return localNote;
    }
);
/*
const pointUp = (): void => {
    emit("incrementPoint", props.id);
    //localPoints.value++;
}
*/
const onInput = (event: Event): void => {
    const element = event.target as HTMLInputElement;
    const inputPoints = Number(element.value);
    emit("update:points", inputPoints);
}

</script>

<template>
<section>
    <h4>{{ name }}さんの情報</h4>
    <dl>
        <dt>ID</dt>
        <dd>{{ id }}</dd>
        <dt>メールアドレス</dt>
        <dd>{{ email }}</dd>
        <dt>保有ポイント</dt>
        <dd>
            <input type="number" v-bind:value="points" v-on:input="onInput">
        </dd>
        <dt>備考</dt>
        <dd>{{ localNote }}</dd>
    </dl>
</section>

</template>

<style>

</style>

