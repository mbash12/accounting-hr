<script setup>
import { reactive, watch, onMounted } from "vue";
import { Icon } from "@iconify/vue";
import { useRoute, useRouter } from "vue-router";
import {
    currentUser,
    getLeaveQuota,
    getNationalHolidays,
    submitForm,
    datetodate,
    getSingleForm,
    loading,
    updateForm,
    notify,
} from "@/deps/service.js";
import { getData, temp, leave_types } from "@/deps/store.js";
const { $swal } = useNuxtApp()
import dayjs from "dayjs";

const route = useRoute();
const router = useRouter();
const state = reactive({
    quota: 0,
    holidays: null,
    form: {
        id: null,
        type: "absence",
        subtype: null,
        date_range: null,
        date: null,
        time_start: null,
        time_end: null,
        time: null,
        description: null,
        reason: null,
        image: null,
    },
    appliedFilter: "absence",
    filters: [
        { value: "absence", text: "Tidak Hadir" },
        { value: "attend", text: "Hadir Khusus" },
    ],
});
const getForm = async () => {
    loading();
    const res = await getSingleForm(state.form.id);
    state.appliedFilter = res?.type;
    state.form.type = res?.type;
    state.form.subtype = {
        value: res?.sub_type,
        text: leave_types[res?.sub_type],
    };
    state.form.description = res?.description;
    state.form.reason = res?.reason;
    state.form.image = res?.image;
    state.first_approver = res?.first_approver?.id;
    state.second_approver = res?.second_approver?.id;

    state.form.date_range = [
        new Date(datetodate(res?.start)),
        new Date(datetodate(res?.end)),
    ];
    state.form.date = new Date(datetodate(res?.start));

    // state.form.time_range = [
    //     datetodate(res?.start, true),
    //     datetodate(res?.end, true),
    // ];

    state.form.time_start = datetodate(res?.start, true);
    state.form.time_end = datetodate(res?.end, true);

    loading(false);
};
const types1 = {
    attend: [
        {
            group: "Hadir Khusus",
            list: [
                { value: "overtime", text: "Lembur" },
                // { value: "wfh", text: "Kerja Dari Rumah" },
                { value: "holiday", text: "Kerja Hari Libur" },
                { value: "halfday", text: "Izin Setengah Hari" },
                { value: "late", text: "Izin Masuk Telat" },
                { value: "early", text: "Izin Pulang Cepat" },
            ],
        },
    ],
    absence: [
        { group: "Cuti", list: [{ value: "annual", text: "Cuti Tahunan" }] },
        {
            group: "Cuti Khusus",
            list: [
                { value: "marry", text: "Cuti Menikah" },
                { value: "kids_marry", text: "Cuti Menikahkan Anak" },
                { value: "khitan", text: "Cuti Khitan/Baptis Anak" },
                { value: "family_death", text: "Cuti Keluarga Inti (Suami, Istri, Orangtua, Mertua, dan Anak) Meninggal" },
                // {
                //     value: "inhouse_death",
                //     text: "Cuti Keluarga Serumah Meninggal",
                // },
                { value: "maternity", text: "Cuti Melahirkan" },
                { value: "maternity_husband", text: "Cuti Istri Melahirkan" },
                { value: "maternity_death", text: "Cuti Keguguran" },
            ],
        },
        {
            group: "Izin",
            list: [
                { value: "others", text: "Izin" },
                { value: "nodn_sick", text: "Sakit Tanpa Surat (hanya 1 hari)" },
                { value: "sick", text: "Sakit Dengan Surat (lebih dari 1 hari)" },
                { value: "force_majure", text: "Bencana Alam" },
                { value: "sudden", text: "Izin Mendadak" },
            ],
        },
    ],
};
const types2 = {
    attend: [
        {
            group: "Hadir Khusus",
            list: [
                { value: "overtime", text: "Lembur" },
                { value: "wfh", text: "Kerja Dari Rumah" },
                { value: "holiday", text: "Kerja Hari Libur" },
                { value: "halfday", text: "Izin Setengah Hari" },
                { value: "late", text: "Izin Masuk Telat" },
                { value: "early", text: "Izin Pulang Cepat" },
            ],
        },
    ],
    absence: [
        { group: "Cuti", list: [{ value: "annual", text: "Cuti Tahunan" }] },
        {
            group: "Cuti Khusus",
            list: [
                { value: "marry", text: "Cuti Menikah" },
                { value: "kids_marry", text: "Cuti Menikahkan Anak" },
                { value: "khitan", text: "Cuti Khitan/Baptis Anak" },
                { value: "family_death", text: "Cuti Keluarga Inti (Suami, Istri, Orangtua, Mertua, dan Anak) Meninggal" },
                // {
                //     value: "inhouse_death",
                //     text: "Cuti Keluarga Serumah Meninggal",
                // },
                { value: "maternity", text: "Cuti Melahirkan" },
                { value: "maternity_death", text: "Cuti Keguguran" },
            ],
        },
        {
            group: "Izin",
            list: [
                { value: "others", text: "Izin" },
                { value: "nodn_sick", text: "Sakit Tanpa Surat" },
                { value: "sick", text: "Sakit Dengan Surat" },
                { value: "force_majure", text: "Bencana Alam" },
                { value: "sudden", text: "Izin Mendadak" },
            ],
        },
    ],
};
const types = currentUser?.user?.department_id?.id === 1 ? types2 : types1;

watch(
    () => state.appliedFilter,
    (val) => {
        if (state.form.id === null) {
            state.form.type = val;
            state.form.subtype = null;
        }
    }
);

function countWeekendDays(startDate, endDate) {
    let count = 0;
    let currentDate = new Date(startDate);

    while (currentDate <= endDate) {
        const dayOfWeek = currentDate.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            // Sunday (0) or Saturday (6)
            count++;
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return count;
}
const validate = () => {
    const overtime = ["overtime"].includes(state.form.subtype?.value)
    if (!overtime) {
        return true;   
    }
    const lastThursday = new Date();
    lastThursday.setDate(lastThursday.getDate() - ((lastThursday.getDay() + 2) % 7));
    lastThursday.setHours(0, 0, 0, 0);

    const today = new Date();
    const currentTime = today.getHours() * 60 + today.getMinutes();

    if (state.date < lastThursday) {
        return false;
    }

    if (state.date > lastThursday && today.getDay() === 4 && currentTime >= 660) {
        return false;
    }

    return true;
    
}

const validateStartEnd = () => {
    if(state.form.subtype?.value !== "overtime"){
        return true
    }
    if (!state.form.time_start || !state.form.time_end) {
        return false;
    }
    if (state.form.time_start > state.form.time_end) {
        return false;
    }
    return true
}
const handleSubmit = async (e) => {
    e.preventDefault();
    if(!validate()){
        $swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Tanggal dan jam lembur tidak valid",
        })
        return
    }

    
    if(!validateStartEnd()){
        $swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Jam mulai dan selesai tidak valid",
        })
        return
    }
    loading();
    const data = {};
    data.user_id = currentUser.user?.id;
    data.status = "submitted";
    data.type = state.form.type;
    if (!state.form.id) {
        data.first_approver = currentUser.user?.department_id?.supervisor_id;
        data.sub_type = state.form.subtype?.value;
        if (data.sub_type == "overtime") {
            data.second_approver = currentUser.user?.department_id?.hrd_id;
        } else {
            data.second_approver =
                currentUser.user?.department_id?.manager_id ??
                currentUser.user?.department_id?.hrd_id;
        }
    } else {
        data.first_approval = null;
        data.second_approval = null;
        data.first_approved = null;
        data.second_approved = null;
    }
    if (state.form.image) {
        data.attachment = state.form.image;
    }
    if (
        [
            "marry",
            "kids_marry",
            "khitan",
            "family_death",
            "inhouse_death",
            "maternity",
            "maternity_death",
            "force_majure",
            "absent",
            "nodn_sick",
            "sick",
            "annual",
            "wfh",
            "holiday",
            "sudden",
            "others",
        ].includes(state.form.subtype?.value)
    ) {
        if (!state.form.date_range[1]) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tanggal Selesai tidak boleh kosong",
            });
            loading(false);
            return;
        }
        const start = new Date(state.form.date_range[0]);
        const end = new Date(state.form.date_range[1]);

        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);

        data.start = `${start.getFullYear()}-${(start.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${start
            .getDate()
            .toString()
            .padStart(2, "0")} 00:00:00`;
        data.end = `${end.getFullYear()}-${(end.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${end
            .getDate()
            .toString()
            .padStart(2, "0")} 00:00:00`;
        data.duration_um = "days";

        const oneDay = 24 * 60 * 60 * 1000;
        const totalDays = Math.floor((end - start) / oneDay) + 1;
        let hdc = 0;
        let wec = 0;
        if (state.form.subtype?.value !== "holiday") {
            // Count holidays that fall on weekdays only
            hdc = state.holidays.filter((holiday) => {
                const holidayDate = new Date(holiday.date);
                holidayDate.setHours(0, 0, 0, 0);
                const dayOfWeek = holidayDate.getDay();
                return holidayDate >= start && 
                       holidayDate <= end && 
                       dayOfWeek !== 0 && 
                       dayOfWeek !== 6;
            }).length;
            wec = countWeekendDays(start, end);
        }

        // Subtract weekends and holidays from duration
        data.duration = totalDays - wec - hdc;

        data.description = state.form.description;
    }
    if (["wfh"].includes(state.form.subtype?.value)) {
        if (!state.form.date_range[1]) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Tanggal Selesai tidak boleh kosong",
            });
            loading(false);
            return;
        }
        data.reason = state.form.reason;
    }

    if (["overtime"].includes(state.form.subtype?.value)) {
        if (!state.form.time_start) {
            $swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Jam Selesai tidak boleh kosong",
            });
            loading(false);
            return;
        }
        const date = new Date(state.form.date);
        const start = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date
            .getDate()
            .toString()
            .padStart(2, "0")} ${state.form.time_start.hours
            .toString()
            .padStart(2, "0")}:${state.form.time_start.minutes
            .toString()
            .padStart(2, "0")}:00`;
        const end = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date
            .getDate()
            .toString()
            .padStart(2, "0")} ${state.form.time_end.hours
            .toString()
            .padStart(2, "0")}:${state.form.time_end.minutes
            .toString()
            .padStart(2, "0")}:00`;
        data.start = start;
        data.end = end;
        data.description = state.form.description;
        data.duration_um = currentUser.user?.overtime_term;
        let starttime =
            Number(state.form.time_start.hours) +
            Number(state.form.time_start.minutes) / 60;
        let endtime =
            Number(state.form.time_end.hours) +
            Number(state.form.time_end.minutes) / 60;
        let rawDuration = endtime - starttime;
        let roundedDuration = Math.floor(rawDuration * 2) / 2;
        data.duration =
            currentUser.user?.overtime_term == "hours" ? roundedDuration : 1;
    }
    if (["early", "late"].includes(state.form.subtype?.value)) {
        const date = new Date(state.form.date);
        const start = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date
            .getDate()
            .toString()
            .padStart(2, "0")} ${state.form.time_start.hours
            .toString()
            .padStart(2, "0")}:${state.form.time_start.minutes
            .toString()
            .padStart(2, "0")}:00`;
        const end = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date
            .getDate()
            .toString()
            .padStart(2, "0")} ${state.form.time_start.hours
            .toString()
            .padStart(2, "0")}:${state.form.time_start.minutes
            .toString()
            .padStart(2, "0")}:00`;
        data.start = start;
        data.end = end;
        data.description = state.form.description;
        data.duration_um = "hours";
        data.duration = 0;
    }

    if (["halfday"].includes(state.form.subtype?.value)) {
        const date = new Date(state.form.date);
        data.description = state.form.description;
        data.duration_um = "days";
        data.duration = 1;
        data.start = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date.getDate().toString()} 00:00:00`;
        data.end = `${date.getFullYear()}-${(date.getMonth() + 1)
            .toString()
            .padStart(2, "0")}-${date.getDate().toString()} 00:00:00`;
    }

    if (
        currentUser?.user?.department_id?.supervisor_id == currentUser?.user?.id
    ) {
        data.first_approval = "approved";
        data.first_approved = datetodate(new Date());
    }

    if (state.form.id) {
        await updateForm(state.form.id, data);
        await notify({
            user_id: state.first_approver,
            type: state.appliedFilter,
            notif_type: "permit",
            title: "Pengajuan Diperbarui",
            content: `Pengajuan ${state.form.subtype?.text} telah diajukan kembali oleh ${currentUser.user?.fullname} pada tanggal ${dayjs().format(
                "DD/MM/YYYY HH:mm"
            )} dari ${data.start} sampai ${data.end}`,
            payload: state.form.id,
        });
    } else {
        const id = await submitForm(data);
        if (currentUser?.user?.id !== state.first_approver) {
            await notify({
                user_id: data.first_approver,
                type: state.appliedFilter,
                notif_type: "permit",
                title: "Pengajuan Diajukan",
                content: `Pengajuan ${state.form.subtype?.text} telah dikirim oleh ${currentUser.user?.fullname} pada tanggal ${dayjs().format(
                    "DD/MM/YYYY"
                )} dari ${data.start} sampai ${data.end}`,
                payload: id,
            });
        }else{
            await notify({
                user_id: state.second_approvaler,
                type: state.appliedFilter,
                notif_type: "permit",
                title: "Pengajuan Diajukan",
                content: `Pengajuan ${state.form.subtype?.text} telah dikirim oleh ${currentUser.user?.fullname} pada tanggal ${dayjs().format(
                    "DD/MM/YYYY HH:mm"
                )} dari ${data.start} sampai ${data.end}`,
                payload: id,
            });
        }
    }
    loading(false);
    $swal.fire({
        icon: "success",
        title: "Success",
        text: "Pengajuan anda telah dikirim",
        showConfirmButton: false,
        timer: 2000,
    }).then(async () => {
        router.push("/home");
    });
};

onMounted(async () => {
    const holiday = await getNationalHolidays();
    const quota = await getLeaveQuota(currentUser.user?.id);
    state.quota = quota?.balance ?? 0;
    state.holidays = holiday;
    if (route?.query?.update) {
        state.form.id = route.query.update;
        getForm();
    }
});
</script>
<template>
    <main>
        <form
            @submit="handleSubmit"
            class="w-full h-full flex flex-col bg-white"
        >
            <Header :title="'Pengajuan Izin'" />
            <div class="px-2 py-1 border-b">
                <Segment
                    :options="state.filters"
                    v-model="state.appliedFilter"
                    :disabled="state.form.id !== null"
                />
            </div>
            <div class="flex-1 overflow-auto pb-60px">
                <div class="flex flex-col gap-4 p-4">
                    <InputLeave
                        :options="types[state.appliedFilter]"
                        v-model="state.form.subtype"
                        label="Jenis Pengajuan"
                        placeholder="Pilih Jenis Pengajuan"
                        :disabled="state.form.id !== null"
                        required
                    />

                    <div
                        class="flex py-3 justify-between items-center"
                        v-if="state.form.subtype?.value == 'annual'"
                    >
                        <span class="text-sm text-gray-600">Sisa Cuti</span>
                        <span class="text-sm">{{ state.quota }} Hari</span>
                    </div>
                    <div class="flex flex-col gap-4" v-if="state.form.subtype">
                        <InputDateRange
                            label="Tanggal (Mulai - Selesai)"
                            placeholder="Masukkan Tanggal"
                            v-if="
                                (state.form.type === 'absence' &&
                                    state.form.subtype?.value !== 'early' &&
                                    state.form.subtype?.value !== 'late') ||
                                state.form.subtype?.value === 'wfh' ||
                                state.form.subtype?.value === 'holiday'
                            "
                            v-model="state.form.date_range"
                            required
                        />
                        <InputDate
                            label="Tanggal"
                            placeholder="Masukkan Tanggal"
                            v-if="
                                state.form.subtype?.value === 'overtime' ||
                                state.form.subtype?.value === 'early' ||
                                state.form.subtype?.value === 'late' ||
                                state.form.subtype?.value === 'halfday'
                            "
                            v-model="state.form.date"
                            required
                        />
                        <InputTime
                            label="Jam Mulai"
                            placeholder="Masukkan Jam"
                            v-if="state.form.subtype?.value === 'overtime'"
                            v-model="state.form.time_start"
                            required
                        />
                        <InputTime
                            label="Jam Selesai"
                            placeholder="Masukkan Jam"
                            v-if="state.form.subtype?.value === 'overtime'"
                            v-model="state.form.time_end"
                            required
                        />
                        <InputTime
                            label="Jam"
                            placeholder="Masukkan Jam"
                            v-if="
                                state.form.subtype?.value === 'early' ||
                                state.form.subtype?.value === 'late'
                            "
                            v-model="state.form.time_start"
                            required
                        />
                        <InputLongText
                            label="Keterangan"
                            placeholder="Masukkan keterangan"
                            v-if="
                                state.form.type === 'absence' ||
                                state.form.subtype?.value === 'early' ||
                                state.form.subtype?.value === 'late' ||
                                state.form.subtype?.value === 'halfday'
                            "
                            v-model="state.form.description"
                            required
                        />
                        <InputLongText
                            label="Alasan"
                            placeholder="Masukkan alasan"
                            v-if="state.form.subtype?.value === 'wfh'"
                            v-model="state.form.reason"
                            required
                        />
                        <InputLongText
                            label="Jenis Pekerjaan"
                            placeholder="Masukkan jenis pekerjaan"
                            v-if="
                                state.form.type === 'attend' &&
                                state.form.subtype?.value !== 'early' &&
                                state.form.subtype?.value !== 'late' &&
                                state.form.subtype?.value !== 'halfday'
                            "
                            v-model="state.form.description"
                            required
                        />
                        <InputImage
                            label="Lampiran"
                            v-if="state.form.subtype.value === 'sick'"
                            required
                            v-model="state.form.image"
                        />
                    </div>
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-2 border-t flex"
            >
                <Button
                    block
                    variant="gray"
                    class="text-14px"
                    @click="router.go(-1)"
                    >Kembali</Button
                >
                <Button block variant="red" type="submit" class="text-14px"
                    >Ajukan</Button
                >
            </div>
        </form>
    </main>
</template>
