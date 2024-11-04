<template>
    <p>Examble new new ssss</p>
    <button @click="log">{{ msg }}</button>
</template>

<script setup>
import { onMounted } from "vue";
import { useCounterStore } from "../store/CounterStore";

const counterStore = useCounterStore();
// variable
const msg = "Hello!";

// functions
const getDummyjson = async () => {
    await axios
        .get("https://jsonplaceholder.typicode.com/todos/1")
        .then((response) => console.log(response.data))
        .catch((Error) => console.log(Error));
};

onMounted(async () => {
    await getDummyjson();
});
</script>
