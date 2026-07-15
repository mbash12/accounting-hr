<script setup>
import { onMounted, reactive } from "vue";
const { $swal } = useNuxtApp();
import {
    getSingleOvertime,
    loading,
    currentUser,
    updateOvertime,
} from "@/deps/service.js";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";

const router = useRouter();
const route = useRoute();
const state = reactive({
    data: null,
});

onMounted(async () => {
    loading();
    const id = route.params.id;
    const data = await getSingleOvertime(id);
    state.data = data;
    loading(false);
});

const cancel = (update = false) => {
    $swal
        .fire({
            title: update
                ? "Apakah anda yakin ingin mengubah?"
                : "Apakah anda yakin ingin membatalkan?",
            text: update
                ? "Pengajuan lembur anda akan diupdate!"
                : "Pengajuan lembur anda akan dibatalkan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: update ? "Ya, Ubah!" : "Ya, Batalkan!",
            cancelButtonText: "Tidak",
        })
        .then((result) => {
            if (result.isConfirmed) {
                updateOvertime(state.data.id, {
                    status: update ? "updating" : "cancelled",
                });
                if (update) {
                    router.push("/overtime?update=" + state.data?.id);
                } else {
                    $swal
                        .fire({
                            icon: "success",
                            title: "Success",
                            text: "Pengajuan lembur anda telah dibatalkan",
                            showConfirmButton: false,
                            timer: 2000,
                        })
                        .then(async () => {
                            router.replace("/overtime/list");
                        });
                }
            }
        });
};
</script>
<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <Header title="Lembur" />
            <div class="flex-1 overflow-auto pb-60px">
                <div class="flex flex-col">
                    <div class="flex flex-col px-6">
                        <div class="flex justify-start items-center py-4 text-sm mt-4 gap-4">
                            <svg
                                width="22"
                                height="24"
                                viewBox="0 0 22 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3.40313 10.0135H13.3787C13.6016 10.0135 13.7819 9.84783 13.7819 9.64319C13.7819 9.43854 13.6016 9.27292 13.3787 9.27292H3.40313C3.18032 9.27292 3 9.43854 3 9.64319C3 9.84783 3.18032 10.0135 3.40313 10.0135Z"
                                    fill="#40B6F4"
                                />
                                <path
                                    d="M16.1324 14.9523C15.9096 14.9523 15.7293 15.1179 15.7293 15.3225V17.867L14.4454 18.9713C14.2829 19.1109 14.2746 19.3452 14.4269 19.4946C14.5061 19.5723 14.6135 19.6117 14.721 19.6117C14.8198 19.6117 14.9186 19.5785 14.9966 19.5115L16.408 18.2977C16.4895 18.2279 16.5355 18.1299 16.5355 18.0276V15.3225C16.5355 15.1179 16.3552 14.9523 16.1324 14.9523Z"
                                    fill="#40B6F4"
                                />
                            </svg>
                            <span class="font-bold text-17px text-[#404040]">Detail Pengajuan Lembur</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Status</span>
                            <Status :status="state.data?.status" />
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="capitalize">{{
                                state.data?.date
                                    ? new Date(state.data.date).toLocaleDateString("id-ID", {
                                          day: "2-digit",
                                          month: "2-digit",
                                          year: "numeric",
                                      })
                                    : "-"
                            }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Jam Mulai</span>
                            <span>{{ state.data?.time_start ?? "-" }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Jam Selesai</span>
                            <span>{{ state.data?.time_end ?? "-" }}</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Durasi</span>
                            <span>{{ state.data?.hours ?? 0 }} Jam</span>
                        </div>
                        <div class="flex justify-between items-center py-4 border-b text-sm font-medium">
                            <span class="text-gray-500">Hari Libur</span>
                            <span class="capitalize">{{ state.data?.is_holiday ? "Ya" : "Tidak" }}</span>
                        </div>
                        <div
                            class="flex flex-col border-b text-sm py-4 gap-2"
                            v-if="state?.data?.reason"
                        >
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Keterangan</span>
                            </div>
                            <div class="text-sm" v-html="state?.data?.reason.replace(/\n/g, '<br/>')"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 w-full gap-4 bg-white flex-col px-4 py-4 border-t flex"
                v-if="
                    state?.data?.employee_id === currentUser.user?.id &&
                    state.data?.status === 'submitted'
                "
            >
                <div class="flex gap-4">
                    <Button block variant="gray" class="text-14px" @click="cancel()">Batal</Button>
                    <Button block variant="red" class="text-14px" @click="cancel(true)">Ubah</Button>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-4 border-t flex flex-col">
                <Button block variant="gray" class="text-14px" @click="router.go(-1)">Kembali</Button>
            </div>
        </div>
    </main>
</template>
