import { createApp } from "vue";
import { createPinia } from "pinia";
import Examble from "./components/Examble.vue";
import { useCounterStore } from "./store/CounterStore";
const pinia = createPinia();
const config = {
    name: "App",
    components: {
        examble: Examble,
    },
};

const app = createApp(config);
app.use(pinia);

app.mount("#app");
