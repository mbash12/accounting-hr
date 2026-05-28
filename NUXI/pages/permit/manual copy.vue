<script setup>
import { onMounted, reactive, watch } from "vue";
import { Icon } from "@iconify/vue";
import Segment from "@/components/Segment.vue";
import Button from "@/components/Button.vue";
import InputLongText from "@/components/InputLongText.vue";
import { submitManual, currentUser,  api } from "@/deps/service.js";
import dayjs from "dayjs";

const state = reactive({
    appliedFilter: "in",
    tab: 0,
    form: {
        dates:null,
        type: "in",
        description: null,
    },
    current_time: new Date(),
    history: null,
    tabs: [
        { value: 0, text: "Catat" },
        { value: 1, text: "Riwayat" },
    ],
    filters: [
        { value: "in", text: "Masuk" },
        { value: "out", text: "Pulang" },
    ],
});
watch(
    () => state.appliedFilter,
    (val) => {
        state.form.type = val;
    }
);
const getHistory = async () => {
    const res = await api("get_dinas_clocks", {
        route: `?filter=user_id,eq,${currentUser.user?.id}&page=1&order=date,desc`,
    });
    state.history = res.records;
};
onMounted(() => {
    // console.log(dayjs().format());
    // setInterval(() => {
    //     state.current_time = new Date();
    // }, 1000);
    // getHistory();
});
const submit = async () => {
    const date = dayjs(state.current_time).format("YYYY-MM-DD");
    const datetime = dayjs(state.current_time).format("YYYY-MM-DD HH:mm:ss");
    const data = {
        type: state.form.type,
        datetime: datetime,
        date: date,
        user_id: currentUser.user?.id,
        note: state.form.description,
        status: "submitted",
    };
    try {
        let res = await submitManual(data);
        Swal({
            icon: "success",
            title: "Success",
            text: "Pengajuan anda telah dikirim",
            showConfirmButton: false,
            timer: 2000,
        }).then(async () => {
            $router.push("/home");
        });
    } catch (error) {
        const msg = error?.data?.message || error?.message || "Gagal mengirim. Silakan coba lagi.";
        Swal({
            icon: "error",
            title: "Gagal",
            text: msg,
        });
    }
};
</script>
<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <Header title="Dinas" />
            <div class="px-2 py-1 border-b">
                <Segment :options="state.tabs" v-model="state.tab" />
            </div>

            <div class="flex flex-col gap-4" v-if="state.tab == 0">
                <div class="px-2 py-1 border-b">
                    <Segment
                        :options="state.filters"
                        v-model="state.appliedFilter"
                    />
                </div>

                <div class="flex-1 overflow-auto pb-60px">
                    <div class="flex flex-col gap-2 p-4">
                        <div class="flex py-3 justify-between items-center">
                            <span class="text-sm text-gray-600"
                                >Waktu saat ini</span
                            >
                            <span class="text-sm">
                                {{
                                    state.current_time.toLocaleDateString(
                                        "id-ID",
                                        {
                                            day: "2-digit",
                                            month: "2-digit",
                                            year: "numeric",
                                        }
                                    )
                                }}
                                {{
                                    state.current_time.toLocaleTimeString([], {
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        second: "2-digit",
                                    })
                                }}
                            </span>
                        </div>
                        <InputLongText
                            label="Keterangan"
                            v-model="state.form.description"
                            placeholder="Ketik keterangan"
                        />
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-2 border-t flex"
                
            >
                <Button block variant="gray" class="text-14px" @click="$router.go(-1)"
                    >Kembali</Button
                >
                <Button v-if="state.tab == 0" block variant="red" class="text-14px" @click="submit()"
                    >Simpan</Button
                >
            </div>
            <div class="flex flex-col" v-if="state.tab == 1">
                <div
                    class="flex justify-between p-4 border-b"
                    v-for="h in Array(10)"
                    >
                    <!-- v-for="h in state.history" -->
                    <div class="flex flex-col">
                        <div class="flex justify-between">
                            <span class="text-sm font-bold text-gray-500">Masuk</span>
                        </div>
                        <div
                            class="flex gap-3 items-center text-xs text-gray-500"
                        >
                            <span>Catatan</span>
                        </div>
                    </div>

                    <div
                        class="flex flex-col items-end gap-1 text-xs text-gray-400"
                    >
                        <span>28 Jan 2022 10:00</span>
                        <span class="capitalize">Disetujui</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
