<script setup lang="ts">
import {ref, computed} from "vue";
import Index from "./index.vue";

const tmp = "123";
const name = ref("test");

const now = new Date();
const nowStr = now.toLocaleTimeString();
let timeStr = nowStr;
const timeStrRef = ref(nowStr);

function changeTime(): void {
    const newTime = new Date();
    const newTimeStr = newTime.toLocaleTimeString();
    timeStr = newTimeStr;
    timeStrRef.value = newTimeStr;
}
setInterval(changeTime, 1000);


let radius = 1;
const radiusRef = ref(radius);
let result = 1;
const resultRef = ref(result);
const PI = 3.14;

const resultComputed = computed(
        (): number => {
            return radiusRef.value * radiusRef.value * PI;
            //以下だと更新されない
            //return radius * radius * PI;
    }
);


setInterval(
    (): void => {
        radius = Math.round(Math.random() * 10);
        radiusRef.value = radius;

        result = radius * radius * PI;
        resultRef.value = radius * radius * PI;
    },
    1000
);


const url = ref("https://www.amazon.co.jp/");
const url2 = "https://www.amazon.co.jp/";

const randValue = ref("未押下"); 
const onButtonClick = (): void => {
    const rand = Math.round(Math.random() * 10);
    randValue.value = String(rand);
}

const mouseEnter = (): void => {
    randValue.value = "Enter";
}

const inputTextarea = ref("テキストエリアへの入力。¥n");
const memberType = ref(1);
const memberTypeSelect = ref(1);
const isAgreed = ref(false);
const isAgreed01 = ref(0);
const selectedOS = ref([]);
const selectedOSSelect = ref([]);

//v-for用の文字列型配列
const cocktailListInit: string[] = [
    "ホワイトレディ",
    "ブルーハワイ",
    "ニューヨーク",
];
const cocktailList = ref(cocktailListInit);

//v-for用の連装配列
const cocktailListInit2: {[key: number]: string;} = {
    2345: "ホワイトレディ",
    4412: "ブルーハワイ",
    6792: "ニューヨーク",
};
const cocktailList2 = ref(cocktailListInit2);

//連想配列にさらにオブジェクトリテラルを使用する場合
const cocktailListInit3: {[key: number]: {[key: number]: string};} = {
    2345: {1: "ホワイトレディ", 2: "ホワイトレディ"},
    4412: {1: "ホワイトレディ"},
    6792: {1: "ホワイトレディ"},
};

//v-for用のmap
//<>にキーと値の型を指定する
const cocktailListInitMap = new Map<number, string>();
cocktailListInitMap.set(2345, "ホワイトレディ");
cocktailListInitMap.set(4412, "ブルーハワイ");
cocktailListInitMap.set(6792, "ニューヨーク");
const cocktailListMap = ref(cocktailListInitMap);

interface Cocktail {
    id: number;
    name: string;
    price: number;
}

const cocktailDataListInit: Cocktail[] = [
    {id: 2345, name: "ホワイトレディ", price: 1200},
    {id: 4412, name: "ブルーハワイ", price: 1500},
    {id: 6792, name: "ニューヨーク", price: 1100},
];
const cocktailDataList = ref(cocktailDataListInit);

const cocktail1500 = computed(
    (): Map<number, Cocktail> => {
        const newList = new Map<number, Cocktail>();
        cocktailDataList.value.forEach(
            (value: Cocktail, key: number): void => {
                if (value.price == 1500) {
                    newList.set(key, value);
                }
            }
        );
        return newList;
    }
);

</script>

<template>
<h1>{{ name }}</h1>
<h2>{{ tmp }}</h2>
<p>現在時刻：{{ timeStr }}</p>
<p>現在時刻(Ref):{{ timeStrRef }}</p>
<p>半径{{ radius }}の円の面積は円周率{{ PI }}で計算すると{{ result }}。{{ resultRef }}。{{ resultComputed }}</p>
<p><a v-bind:href="url">サイト</a></p>
<p><button v-on:click="onButtonClick">ボタン</button></p>
<p>{{ randValue }}</p>
<h1 v-on:mouseenter="mouseEnter">マウスエンター</h1>

<!--v-modelの中身がテキスト部分に表示される-->
<textarea v-model="inputTextarea" cols="30" rows="10"></textarea>
<br>

<!--ラジオボタンを選択するとv-modelにvalueの値が格納される-->
<section>
    <label for=""><input type="radio" name="memberType" value="1" v-model="memberType">通常会員</label>
    <label for=""><input type="radio" name="memberType" value="2" v-model="memberType">特別会員</label>
    <label for=""><input type="radio" name="memberType" value="3" v-model="memberType">優良会員</label>
    <br>
    <p>選択されたラジオボタン:{{ memberType }}</p>
</section>
<br>

<!--valueの値がv-modelに格納される-->
<section>
    <select v-model="memberTypeSelect">
        <option value="1">通常会員</option>
        <option value="2">特別会員</option>
        <option value="3">優良会員</option>
    </select>
    <p>選択されたリストボタン:{{ memberTypeSelect }}</p>
</section>
<br>

<!--チェックしたらv-modelにtrueが入る-->
<section>
    <label for=""><input type="checkbox" v-model="isAgreed">同意する</label>
    <p>同意の結果：{{ isAgreed }}</p>
</section>

<!--チェックしたらv-modelに1が入る。チェックを外したら0が入る-->
<section>
    <label for=""><input type="checkbox" v-model="isAgreed01" true-value="1" false-value="0">同意する</label>
    <p>同意の結果：{{ isAgreed01 }}</p>
</section>

<!--チェックしたらvalueの値がv-modelに入る。配列型にする必要あり。配列型でなければ、1つチェックしたら全てにチェックが入ってしまう-->
<section>
    <label for=""><input type="checkbox" v-model="selectedOS" value="1">macOS</label>
    <label for=""><input type="checkbox" v-model="selectedOS" value="2">macOS</label>
    <label for=""><input type="checkbox" v-model="selectedOS" value="3">macOS</label>
    <label for=""><input type="checkbox" v-model="selectedOS" value="4">macOS</label>
    <label for=""><input type="checkbox" v-model="selectedOS" value="5">macOS</label>
    <p>選択されたOS：{{ selectedOS }}</p>
</section>
<br>

<section>
    <select v-model="selectedOSSelect" multiple>
        <option value="1">通常会員</option>
        <option value="2">特別会員</option>
        <option value="3">優良会員</option>
        <option value="4">特別会員</option>
        <option value="5">優良会員</option>
    </select>
    <P>選択されたOS：{{ selectedOSSelect }}</P>
</section>
<br>

<!--v-forのサンプル-->
<section>
    <ul>
        <!--各要素を格納する変数、インデックス（配列添え字）を格納する変数の順番-->
        <li
        v-for="(cocktailName, index) in cocktailList"
        v-bind:key="cocktailName">
            {{ cocktailName }}(インデックス：{{ index }})

        </li>
    </ul>
</section>
<br>
<section>
    <ul>
        <!--各要素を格納する変数、各要素のキー、インデックス（配列添え字）を格納する変数の順番-->
        <li
            v-for="(cocktailName, id, index) in cocktailList2"
            v-bind:key="'cocktailListWithIdx' + id">
                {{ index + 1 }}: IDが{{ id }}のカクテルは{{ cocktailName }}
        </li>
    </ul>
</section>
<br>

<section>
    <ul>
        <!--各要素のキー、各要素を格納する変数の順番(↑とは逆になっている)-->
        <li
            v-for="[id, cocktailName] in cocktailListMap"
            v-bind:key="id">
            IDが{{ id }}のカクテルは{{ cocktailName }}
        </li>
    </ul>
</section>
<br>

<section>
    値段が1500円のカクテルリスト
    <ul>
        <li
            v-for="[id, cocktailItem] in cocktail1500"
            v-bind:key="'cocktail1500' + id">
                {{ cocktailItem.name }}の値段は{{ cocktailItem.price }}円。

        </li>
    </ul>
</section>
</template>

<style>

</style>

