<script setup lang="ts">
import {ref, computed} from "vue";

const item1 = ref();
const item2 = ref([]);
const item3 = ref("");
const item1Result = ref("")
const item2Result = ref("")
const item3Result = ref("")

function answer(): void {
    if (item1.value == 1) {
        item1Result.value = "問１：正解";
    } else {
        item1Result.value = "問１：不正解";
    }

    const correctItem2: string[] = ["1", "2"];
    if (isDiffArrays(item2.value, correctItem2)) {
        item2Result.value = "問２：正解";
    } else {
        item2Result.value = "問２：不正解";
    }

    if (item3.value == "iPhone") {
        item3Result.value = "問３：正解";
    } else {
        item3Result.value = "問３：不正解";
    }

}

//２つの配列要素が一致していればtrue、不一致ならfalse
function isDiffArrays(arrayA: string[], arrayB: string[]) {

        //arrayB.indexOf(i)は、arrayB配列に、要素iが存在するインデックス（添え字）を表す。存在しなければ-1を返す。
        //iはarrayAの要素であり、arrayBにiが存在しない場合（arrayB.indexOf(i) == -1がtrueになる場合）、iがnumOnlyA配列に格納される。
        //arrayAの要素を順番にiに入れて判定していき、arrayAのみに存在する要素だけnumOnlyA配列に格納される。
        let numOnlyInA = arrayA.filter(i => arrayB.indexOf(i) == -1);
        let numOnlyInB = arrayB.filter(i => arrayA.indexOf(i) == -1);

        return ((numOnlyInA.length == 0) && (numOnlyInB.length == 0))
    }
</script>

<template>
<h1>Quiz</h1>

<section>
    <p>問１：iPhoneのOSはどれ？</p>
    <input type="radio" value="1" v-model="item1">iOS
    <input type="radio" value="2" v-model="item1">widows
</section>
<br>

<section>
    <p>問２：正しいOSを全て選択して</p>
    <input type="checkbox" value="1" v-model="item2">iOS
    <input type="checkbox" value="2" v-model="item2">iPadOS
    <input type="checkbox" value="3" v-model="item2">karnel
</section>
<br>

<section>
    <p>問3：appleのスマートフォンは何？</p>
    <input type="text" v-model="item3">
</section>

<button v-on:click="answer">回答する</button>
<p>{{ item1Result }}</p>
<p>{{ item2Result }}</p>
<p>{{ item3Result }}</p>
</template>

<style>

</style>

