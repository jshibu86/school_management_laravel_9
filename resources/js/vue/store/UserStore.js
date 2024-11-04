import { defineStore } from "pinia";

export const useUserStore = defineStore("user", {
    state: () => ({
        token: 0,
        user: "VIJU J K",
    }),
});
