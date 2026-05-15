<script setup>
import { reactive, watch, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import {
    currentUser,
    getNationalHolidays,
    submitOvertime,
    getSingleOvertime,
    loading,
    updateOvertime,
} from "@/deps/service.js";
const { $swal } = useNuxtApp();

const route = useRoute();
const router = useRouter();
const state = reactive({
    holidays: [],
    form: {
        id: null,
        date: null,
        time_start: null,
        time_end: null,
        reason: null,
    },
    is_holiday: false,
});

const getForm = async () => {
    loading();
    const res = await getSingleOvertime(state.form.id);
    state.form.date = new Date(res.date);
    const startStr = res.date;
    state.form.time_start = null;
    state.form.time_end = null;
    state.form.reason = res.reason;
    state.is_holiday = res.is_holiday;
    loading(false);
};

const validate = () => {
    if (!state.form.date) {
        $swal.fire({ icon: "error", title: "Oops...", text: "Tanggal harus diisi" });
        return false;
    }
    if (!state.form.time_start || !state.form.time_end) {
        $swal.fire({ icon: "error", title: "Oops...", text: "Jam mulai dan selesai harus diisi" });
        return false;
    }
    const startMins = Number(state.form.time_start.hours) * 60 + Number(state.form.time_start.minutes);
    const endMins = Number(state.form.time_end.hours) * 60 + Number(state.form.time_end.minutes);
    if (startMins >= endMins) {
        $swal.fire({ icon: "error", title: "Oops...", text: "Jam mulai harus lebih kecil dari jam selesai" });
        return false;
    }
    return true;
};

const checkHoliday = (date) => {
    if (!date || !state.holidays.length) return false;
    const d = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, "0")}-${date.getDate().toString().padStart(2, "0")}`;
    return state.holidays.some((h) => h.date === d);
};

watch(
    () => state.form.date,
    (date) => {
        state.is_holiday = checkHoliday(date);
    }
);

const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validate()) return;

    loading();
    const date = new Date(state.form.date);
    const startH = Number(state.form.time_start.hours) + Number(state.form.time_start.minutes) / 60;
    const endH = Number(state.form.time_end.hours) + Number(state.form.time_end.minutes) / 60;
    const rawDuration = endH - startH;
    const hours = Math.floor(rawDuration * 2) / 2;

    const dateStr = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, "0")}-${date.getDate().toString().padStart(2, "0")}`;

    const data = {
        date: dateStr,
        hours,
        reason: state.form.reason ?? null,
        is_holiday: state.is_holiday,
        status: "submitted",
    };

    if (state.form.id) {
        await updateOvertime(state.form.id, data);
    } else {
        await submitOvertime(data);
    }
    loading(false);
    $swal.fire({
        icon: "success",
        title: "Success",
        text: "Pengajuan lembur anda telah dikirim",
        showConfirmButton: false,
        timer: 2000,
    }).then(async () => {
        router.push("/home");
    });
};

onMounted(async () => {
    state.holidays = await getNationalHolidays();
    if (route?.query?.update) {
        state.form.id = route.query.update;
        getForm();
    }
});
</script>
<template>
    <main>
        <form @submit="handleSubmit" class="w-full h-full flex flex-col bg-white">
            <Header :title="'Pengajuan Lembur'" />
            <div class="flex-1 overflow-auto pb-60px">
                <div class="flex flex-col gap-4 p-4">
                    <InputDate
                        label="Tanggal"
                        placeholder="Masukkan Tanggal"
                        v-model="state.form.date"
                        required
                    />
                    <InputTime
                        label="Jam Mulai"
                        placeholder="Masukkan Jam Mulai"
                        v-model="state.form.time_start"
                        required
                    />
                    <InputTime
                        label="Jam Selesai"
                        placeholder="Masukkan Jam Selesai"
                        v-model="state.form.time_end"
                        required
                    />
                    <InputLongText
                        label="Keterangan"
                        placeholder="Masukkan keterangan (opsional)"
                        v-model="state.form.reason"
                    />
                    <div
                        v-if="state.is_holiday"
                        class="text-xs text-amber-600 bg-amber-50 px-3 py-2 rounded-lg"
                    >
                        Terdeteksi hari libur nasional
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-2 border-t flex">
                <Button block variant="gray" class="text-14px" @click="router.go(-1)"
                    >Kembali</Button
                >
                <Button block variant="red" type="submit" class="text-14px">Ajukan</Button>
            </div>
        </form>
    </main>
</template>
