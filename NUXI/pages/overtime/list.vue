<script setup>
import { onMounted, reactive, computed } from "vue";
import { getOvertimes, loading } from "@/deps/service.js";
import { Icon } from "@iconify/vue";

const router = useRouter();

const state = reactive({
    data: [],
    page: 1,
    results: 0,
    appliedFilter: "submitted",
    filters: [
        { value: "submitted", text: "Diajukan" },
        { value: "approved", text: "Disetujui" },
        { value: "rejected", text: "Ditolak" },
    ],
});

const totalPages = computed(() => Math.max(1, Math.ceil(state.results / 20)));

const fetchData = async () => {
    loading();
    const result = await getOvertimes({
        status: state.appliedFilter,
        page: state.page,
    });
    state.data = result?.records ?? [];
    state.results = result?.results ?? 0;
    loading(false);
};

const switchFilter = (val) => {
    state.appliedFilter = val;
    state.page = 1;
    fetchData();
};

const prevPage = () => {
    if (state.page > 1) {
        state.page--;
        fetchData();
    }
};

const nextPage = () => {
    if (state.page < totalPages.value) {
        state.page++;
        fetchData();
    }
};

onMounted(() => {
    fetchData();
});
</script>
<template>
    <main class="bg-[#F5F7FA] min-h-screen pb-60px">
        <div class="flex flex-col">
            <Header title="Riwayat Lembur" />
            <div class="px-4 pt-4">
                <Segment :options="state.filters" :modelValue="state.appliedFilter" @update:modelValue="switchFilter" />
            </div>
            <div v-if="state.data.length === 0" class="flex flex-col items-center justify-center py-20 gap-4">
                <svg width="120" height="100" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <g transform="translate(24 31.67)">
                            <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse>
                            <path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path>
                            <path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                            <path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path>
                            <path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path>
                        </g>
                    </g>
                </svg>
                <span class="text-sm text-gray-400 font-bold">Tidak ada pengajuan lembur</span>
            </div>
            <div v-else class="px-4 pt-2 flex flex-col gap-2">
                <button
                    v-for="item in state.data"
                    :key="item.id"
                    class="bg-white rounded-xl p-4 shadow-sm flex flex-col gap-3 border border-gray-100"
                    @click="router.push(`/overtime/${item.id}`)"
                >
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">
                            {{ new Date(item.date).toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" }) }}
                        </span>
                        <Status :status="item.status" />
                    </div>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <Icon icon="solar:clock-square-bold-duotone" width="14" height="14" class="text-blue-500" />
                            <span v-if="item.time_start && item.time_end">{{ item.time_start }} - {{ item.time_end }} ({{ item.hours }} Jam)</span>
                            <span v-else>{{ item.hours }} Jam</span>
                        </div>
                        <div class="flex items-center gap-1" v-if="item.is_holiday">
                            <Icon icon="solar:calendar-mark-bold-duotone" width="14" height="14" class="text-red-500" />
                            <span class="text-red-500">Hari Libur</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500" v-if="item.reason">
                        <span>{{ item.reason.substring(0, 60) }}{{ item.reason.length > 60 ? '...' : '' }}</span>
                    </div>
                </button>
            </div>
            <div v-if="state.data.length > 0" class="flex justify-center items-center gap-4 py-4">
                <button
                    class="text-xs bg-white px-4 py-2 rounded shadow-sm disabled:opacity-40"
                    :disabled="state.page <= 1"
                    @click="prevPage"
                >
                    Sebelumnya
                </button>
                <span class="text-xs font-bold">{{ state.page }} / {{ totalPages }}</span>
                <button
                    class="text-xs bg-white px-4 py-2 rounded shadow-sm disabled:opacity-40"
                    :disabled="state.page >= totalPages"
                    @click="nextPage"
                >
                    Berikutnya
                </button>
            </div>
        </div>
        <button
            class="fixed right-[1rem] bottom-[5rem] z-50 rounded-full text-[#40B6F4] flex items-center justify-center shadow-lg overflow-hidden bg-white"
            style="bottom: calc(5rem + env(safe-area-inset-bottom));"
            @click="router.push('/overtime')"
        >
            <Icon
                icon="solar:add-circle-bold"
                width="3rem"
                height="3rem"
                class="scale-115"
            />
        </button>
    </main>
</template>
