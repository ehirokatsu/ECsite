<script setup lang="ts">
import {ref, computed} from "vue";
import Index from "./index.vue";

//数値型二次元配列の初期化。-1：未選択、1：⚪︎プレイヤー、2：×プレイヤー
let states: number[][] = [
    [-1, -1, -1],
    [-1, -1, -1],
    [-1, -1, -1],
]
let statesRef = ref(states);

let playerId = 1;
let playerIdRef = ref(playerId);

//⚪︎プレイヤー：1、×プレイヤー：2として管理する
let playerIds: {[key: number]:string} = {1: "⚪︎",2: "×"};

const init = (): void => {
    statesRef.value = [
        [-1, -1, -1],
        [-1, -1, -1],
        [-1, -1, -1],
    ];
    playerIdRef.value = 1;
}

//マス目選択時の処理
const onSelect = (rowsIndex: number, colsIndex: number): void => {

    //クリックしたマスが選択済み
    if (statesRef.value[rowsIndex][colsIndex] != -1) {

        alert("そのマスはすでに選択されています。");

    // クリックしたマスが未選択
    } else {

        //プレイヤーIDをマス目に格納する
        statesRef.value[rowsIndex][colsIndex] = playerIdRef.value;

        //プレイヤーを交代する
        playerIdRef.value = (playerIdRef.value == 1) ? 2 : 1;

        //勝敗判定を行う
        let winnerId = getWinnerId();

        //勝敗が決定した場合
        if (winnerId != -1) {
            alert(playerIds[winnerId] + "さんの勝ちです。");

            //マス目などを初期化する
            init();

        //引き分けの場合
        } else if (isDraw()) {

            alert("引き分けです");

            //マス目などを初期化する
            init();

        }
    }
}

//勝敗判定
const getWinnerId = (): number => {

    //縦横の判定
    for (let i = 0; i < 3; i++) {

        //横3つを1行ずつ判定する
        let row = statesRef.value[i];
        if (isStatesFilled(row)) {
            return row[0];
        }

        //縦3つを1列ずつ判定する
        let col = [statesRef.value[0][i],statesRef.value[1][i], statesRef.value[2][i]];
        if (isStatesFilled(col)) {
            //return statesRef.value[0][i];
            return col[0];
        }
    }

    //左斜めを判定する
    let skew1 = [statesRef.value[0][0], statesRef.value[1][1], statesRef.value[2][2]];
    if (isStatesFilled(skew1)) {
        //return statesRef.value[0][0];
        return skew1[0];
    }

    //右斜めを判定する
    let skew2 = [statesRef.value[0][2], statesRef.value[1][1], statesRef.value[2][0]];
    if (isStatesFilled(skew2)) {
        //return statesRef.value[0][2];
        return skew1[0];
    }
    
    return -1;
}

//1次元配列の各要素が全て等しいか判定
const isStatesFilled = (states: number[]):boolean => {
    return(
        states[0] != -1 &&
        states[0] == states[1] &&
        states[1] == states[2]
    );
    
}

//引き分けか判定
const isDraw = (): boolean => {
    for (let i in statesRef.value) {
        let row = statesRef.value[i];
        for (let j in row) {
            let stateTmp = row[j];

            //1マスでも未選択があれば引き分けではないと判定する
            if (stateTmp == -1) {
                return false;
            }
        }
    }
    return true;
}

</script>

<template>
<div class="flex justify-center">
    <div v-if="playerIdRef==1">⚪︎プレイヤーの番です。</div>
    <div v-if="playerIdRef==2">×プレイヤーの番です。</div>
</div>
<div class="flex justify-center">
    <table class="border">
        <tr class="border" v-for="(row, rowsIndex) in statesRef">
            <td class="border w-40 h-40 text-xl" v-for="(state, colsIndex) in row"
                v-on:click="onSelect(rowsIndex, colsIndex)">
                <div v-if="state==1">⚪</div>
                <div v-if="state==2">×</div>
                <div v-if="state==-1"></div>
            </td>
        </tr>
    </table>
</div>

</template>

<style>

</style>

