<script setup>
import { reactive, onUnmounted, onMounted } from "vue";
import { Icon } from "@iconify/vue";
import { store } from "/deps/service.js";
import Segment from "/components/Segment.vue";
import Button from "/components/Button.vue";
import { useRouter, useRoute } from "vue-router";
const router = useRouter();
const route = useRoute();
const state = reactive({
    tab: 0,
    showSkip:false,
});
const tabs = [
    { value: 0, text: "Android" },
    { value: 1, text: "iOS" },
];
let deferredPrompt = null;
const installPWA = () => {
    console.log("Installing PWA");
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === "accepted") {
                store.installed = true;
                router.replace("/auth");
            } else {
                console.log("User not accepted the installation");
            }
            deferredPrompt = null;
        });
    }
};

const handleBeforeInstallPrompt = (event) => {
    event.preventDefault();
    deferredPrompt = event;
};

const skipInstall = () => {
    localStorage.setItem("skipInstall", true);
    router.replace("/");
};

onMounted(() => {
    window.addEventListener("beforeinstallprompt", handleBeforeInstallPrompt);
    const skip = route.query.skip;
    if(skip){
        state.showSkip = true;
    }
});

onUnmounted(() => {
    window.removeEventListener(
        "beforeinstallprompt",
        handleBeforeInstallPrompt
    );
});
</script>

<template>
    <main>
        <div class="w-full h-full flex flex-col bg-white">
            <div class="p-4 bg-[#F10A13] text-white text-sm">
                <span class="font-bold">Installasi</span>
            </div>
            <div
                class="flex-1 gap-1 flex flex-col items-center justify-center p-4"
                v-show="state.tab === 0"
            >
                <Icon
                    icon="ant-design:android-filled"
                    width="6rem"
                    height="6rem"
                    class="text-red-500"
                />

                <strong class="my-3">Cara install aplikasi di Android</strong>
                <span class="text-center text-sm text-gray-500 px-8"
                    >Pastikan pakai browser Google Chrome</span
                >
                <span class="text-center text-sm text-gray-500 px-8"
                    >Klik tombol dibawah untuk menginstall aplikasi ke handphone
                    kamu</span
                >
                <span class="text-center text-sm text-gray-500 px-8"
                    >Kemudian buka ikon aplikasi dari handphone</span
                >
                <Button
                    variant="red"
                    class="text-14px px-12 mt-4"
                    @click="installPWA"
                    >Install Sekarang</Button
                >
                <Button
                    variant="red"
                    class="text-14px px-12 mt-4"
                    @click="skipInstall"
                    >Tanpa Install</Button
                >
                <span></span>
            </div>
            <div
                class="flex-1 gap-4 flex flex-col items-center justify-center"
                v-show="state.tab === 1"
            >
                <Icon
                    icon="ant-design:apple-filled"
                    width="6rem"
                    height="6rem"
                    class="text-red-500"
                />
                <div>
                    <strong class="text-center w-full block">Cara install aplikasi di iOS</strong>
                    <ol class="text-sm mt-4 gap-2 flex flex-col">
                        <li class="flex items-center gap-1">
                            <Icon
                                icon="oui:share"
                                width="1.2rem"
                                height="1.2rem"
                                class="text-blue-500"
                            />
                            <span class="text-gray-600">
                                Klik ikon share di Google Chrome / Safari</span
                            >
                        </li>
                        <li class="flex items-center gap-1">
                            <Icon
                                icon="fluent:add-square-20-regular"
                                width="1.2rem"
                                height="1.2rem"
                            />
                            <span class="text-gray-600"
                                >Pilih "Add to Home Screen"</span
                            >
                        </li>
                        <li class="flex items-center gap-1">
                            <Icon
                                icon="fluent:add-square-20-regular"
                                width="1.2rem"
                                height="1.2rem"
                            />
                            <span class="text-gray-600"
                                >Klik tombol
                                <span class="text-blue-500 font-medium"
                                    >Add</span
                                ></span
                            >
                        </li>
                        <li class="flex items-center gap-1">
                            <Icon
                                icon="fluent:add-square-20-regular"
                                width="1.2rem"
                                height="1.2rem"
                            />
                            <span class="text-gray-600"
                                >Kemudian buka ikon aplikasi dari handphone</span
                            >
                        </li>
                    </ol>
                    <span class="block h-11"></span>
                </div>
            </div>
            <div class="p-4">
                <Segment :options="tabs" v-model="state.tab" />
            </div>
        </div>
    </main>
</template>
