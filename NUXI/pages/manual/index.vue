<script setup>
import { onMounted, reactive, watch } from "vue";
import { Icon } from "@iconify/vue";
import Segment from "@/components/Segment.vue";
import Button from "@/components/Button.vue";
import InputLongText1 from "@/components/InputLongText1.vue";
import { useRouter } from "vue-router";
import { currentUser, getDinas } from "@/deps/service.js";
import dayjs from "dayjs";
const { $swal } = useNuxtApp()

const router = useRouter();
const state = reactive({
    appliedFilter: "in",
    tab: 0,
    results: 0,
    page:1,
    form: {
        dates: null,
        type: "in",
        description: null,
        latitude: null,
        longitude: null,
    },
    current_time: new Date(),
    historyRaw: [],
    history: [],
    show_history:false,
    history_loading:false
});

const toTime = (datetime) => {
    if (!datetime) return "-";
    return new Date(datetime).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const mapHistoryByDay = (records) => {
    const grouped = {};
    records.forEach((record) => {
        const key = record.date ?? dayjs(record.datetime).format("YYYY-MM-DD");
        if (!grouped[key]) {
            grouped[key] = {
                date: key,
                checkIn: null,
                checkOut: null,
                noteIn: null,
                noteOut: null,
            };
        }

        if (record.type === "in") {
            grouped[key].checkIn = record.datetime;
            grouped[key].noteIn = record.note ?? grouped[key].noteIn;
        } else if (record.type === "out") {
            grouped[key].checkOut = record.datetime;
            grouped[key].noteOut = record.note ?? grouped[key].noteOut;
        }
    });

    return Object.values(grouped).sort(
        (a, b) => new Date(b.date).getTime() - new Date(a.date).getTime()
    );
};

const refreshGroupedHistory = () => {
    state.history = mapHistoryByDay(state.historyRaw);
};

const getTodayHistory = () => {
    const today = dayjs().format("YYYY-MM-DD");
    return state.history.find((item) => item.date === today);
};

const toRadians = (value) => (Number(value) * Math.PI) / 180;

const calculateDistanceMeters = (latA, lngA, latB, lngB) => {
    const earthRadiusMeters = 6371000;
    const dLat = toRadians(latB - latA);
    const dLng = toRadians(lngB - lngA);
    const lat1 = toRadians(latA);
    const lat2 = toRadians(latB);
    const hav =
        Math.sin(dLat / 2) ** 2 +
        Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLng / 2) ** 2;
    return earthRadiusMeters * (2 * Math.atan2(Math.sqrt(hav), Math.sqrt(1 - hav)));
};

const evaluateAttendanceSpot = (latitude, longitude) => {
    const spots = Array.isArray(currentUser.user?.attendance_spots)
        ? currentUser.user.attendance_spots
        : [];
    if (!spots.length) return { allowed: true, nearest: null };

    const evaluations = spots
        .map((spot) => {
            const distance = calculateDistanceMeters(
                Number(latitude),
                Number(longitude),
                Number(spot.latitude),
                Number(spot.longitude)
            );
            const radius = Number(spot.radius_meters);
            return {
                spot,
                distance,
                radius,
                inside: distance <= radius,
            };
        })
        .sort((a, b) => a.distance - b.distance);

    const nearest = evaluations[0] ?? null;
    const inside = evaluations.some((item) => item.inside);
    return { allowed: inside, nearest };
};

const canCheckIn = () => {
    const today = getTodayHistory();
    return !today?.checkIn;
};

const canCheckOut = () => {
    const today = getTodayHistory();
    return Boolean(today?.checkIn) && !today?.checkOut;
};

const getDayState = (item) => {
    if (item?.checkIn && item?.checkOut) return "Lengkap";
    if (item?.checkIn && !item?.checkOut) return "Belum Check Out";
    return "Belum Check In";
};
const displayedHistory = () => {
    return state.show_history ? state.history : state.history.slice(0, 3);
};
const toggleHistory = () => {
    state.show_history = !state.show_history;
};
const historyToggleText = () => {
    return state.show_history ? "Tampilkan Ringkas" : "Lihat Semua";
};

const getDeviceLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                state.form.latitude = position.coords.latitude;
                state.form.longitude = position.coords.longitude;
            },
            (error) => {
                if (error.code === 1) {
                    $swal.fire({
                        icon: "warning",
                        title: "Izinkan Akses Lokasi",
                        html: `
                            <p class="mb-3">Untuk menggunakan fitur ini, Anda harus mengizinkan akses lokasi.</p>
                            <p class="mb-3">Atau Anda dapat memasukkan koordinat secara manual dengan mengikuti langkah berikut:</p>
                            <ol class="text-left text-sm mb-3">
                                <li>1. Install "My GPS Coordinates" dari Play Store</li>
                                <li>2. Buka aplikasi hingga koordinat muncul</li>
                                <li>3. Tap tombol titik tiga di pojok kanan atas</li>
                                <li>4. Pilih "Share Location"</li>
                                <li>5. Pilih "Copy"</li>
                                <li>6. Paste koordinat di form berikut</li>
                            </ol>
                            <input id="manualCoords" class="border p-2 w-full mb-2 text-sm" placeholder="Paste koordinat disini">
                        `,
                        showCancelButton: true,
                        confirmButtonText: "Coba Lagi",
                        cancelButtonText: "Input Manual",
                        showDenyButton: true,
                        denyButtonText: "Kembali",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else if (result.isDismissed && result.dismiss === 'cancel') {
                            const coordText = document.getElementById('manualCoords').value;
                            const latMatch = coordText.match(/Latitude:\s*[NS]\s*(\d+)°(\d+)'([\d.]+)"/);
                            const lngMatch = coordText.match(/Longitude:\s*[EW]\s*(\d+)°(\d+)'([\d.]+)"/);
                            
                            if (latMatch && lngMatch) {
                                const latDeg = parseInt(latMatch[1]);
                                const latMin = parseInt(latMatch[2]);
                                const latSec = parseFloat(latMatch[3]);
                                const latDir = coordText.match(/Latitude:\s*([NS])/)[1];
                                
                                const lngDeg = parseInt(lngMatch[1]);
                                const lngMin = parseInt(lngMatch[2]);
                                const lngSec = parseFloat(lngMatch[3]);
                                const lngDir = coordText.match(/Longitude:\s*([EW])/)[1];
                                
                                let latitude = latDeg + (latMin/60) + (latSec/3600);
                                let longitude = lngDeg + (lngMin/60) + (lngSec/3600);
                                
                                if (latDir === 'S') latitude = -latitude;
                                if (lngDir === 'W') longitude = -longitude;
                                
                                state.form.latitude = latitude;
                                state.form.longitude = longitude;
                            } else {
                                $swal.fire({
                                    icon: "error",
                                    title: "Format Tidak Valid",
                                    text: "Pastikan format koordinat sesuai dengan contoh dari aplikasi My GPS Coordinates",
                                }).then(() => {
                                    // Reopen the main location permission popup
                                    getDeviceLocation();
                                });
                            }
                        } else if (result.isDenied) {
                            router.go(-1);
                        }
                    });
                } else {
                    $swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Geolocation is not supported by this browser.",
                    });
                }
            }
        );
    } else {
        $swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Geolocation is not supported by this browser.",
        });
    }
};
const getHistory = async () => {
    const res = await getDinas({ page: 1 });
    state.historyRaw = res.records;
    state.results = res.results
    refreshGroupedHistory();
};
onMounted(() => {
    getDeviceLocation();
    setInterval(() => {
        state.current_time = new Date();
    }, 1000);
    localStorage.removeItem("manual");
    getHistory();
});
const loadMore = async () => {
    if (!state.show_history) return;
    if (state.historyRaw.length >= state.results) return;
    state.page += 1
    state.history_loading = true
    const res = await getDinas({ page: state.page });
    state.historyRaw = state.historyRaw.concat(res.records)
    refreshGroupedHistory();
    state.history_loading = false
}
const submit = async (type) => {
    if(state.form.description == null){
        $swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Isi catatan terlebih dahulu.",
        });
        return
    }
    if (!state.form.latitude || !state.form.longitude) {
        $swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Lokasi tidak ditemukan.",
        });
        return;
    }

    const spotValidation = evaluateAttendanceSpot(state.form.latitude, state.form.longitude);
    if (!spotValidation.allowed) {
        const nearest = spotValidation.nearest;
        $swal.fire({
            icon: "error",
            title: "Di luar area absensi",
            text: nearest
                ? `Spot terdekat ${nearest.spot?.name || "-"} (radius ${Math.round(
                      nearest.radius
                  )} m, jarak ${Math.round(nearest.distance)} m).`
                : "Lokasi saat ini tidak masuk radius spot absensi.",
        });
        return;
    }

    const datetime = dayjs(state.current_time).format("YYYY-MM-DD HH:mm:ss");
    const data = {
        type: type,
        datetime: datetime,
        note: state.form.description,
        latitude: state.form.latitude,
        longitude: state.form.longitude,
        user_id: currentUser.user?.id,
    };

    localStorage.setItem("manual", JSON.stringify(data));
    router.push("/manual/capture");
};
</script>
<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <div class="w-full h-260px bg-[#F10A13] text-white relative">
                <img
                    src="/bg.png"
                    alt=""
                    class="w-full h-full object-cover object-top absolute inset-0"
                />
                <div
                    class="flex gap-3 items-center prf w-full h-80px py-4 px-6 relative my-1"
                >
                    <button @click="router.go(-1)" class="mb-2 p-2">
                        <svg
                            width="12"
                            height="20"
                            viewBox="0 0 12 20"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M10 2L2 10L10 18"
                                stroke="white"
                                stroke-width="2.34375"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                    <div class="flex-1 flex flex-col flex-1 w-4/10 gap-1">
                        <div class="flex gap-1 items-center">
                            <span class="capitalize">Welcome,</span>
                            <span class="font-bold truncate capitalize"
                                >{{
                                    currentUser?.user?.fullname
                                        ?.split(" ")[0]
                                        .toLowerCase()
                                }}
                            </span>
                        </div>
                        <span
                            class="text-xs opacity-80 truncate block w-full"
                            >{{ currentUser?.user?.department_id?.name }}</span
                        >
                    </div>
                    <button
                        class="h-50px w-50px rounded-full flex items-center justify-center flex-shrink-0 border-2 border-white"
                        @click="router.push('/account')"
                    >
                        <img
                            :src="currentUser?.auth?.photoURL"
                            alt=""
                            class="rounded-full w-full h-full object-cover"
                            v-if="currentUser?.auth?.photoURL"
                        />
                        <Icon
                            icon="solar:user-circle-bold-duotone"
                            class="w-full h-full"
                            v-else
                        />
                    </button>
                </div>
            </div>
            <div
                class="overflow-auto flex-1 -mt-170px px-4 relative gap-5 flex flex-col"
            >
                <div
                    class="flex flex-col gap-4 bg-white rounded-2xl"
                    style="box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.1)"
                >
                    <div class="">
                        <div class="flex flex-col gap-2 p-4">
                            <div
                                class="flex flex-col py-2 justify-between items-center gap-4"
                            >
                                <span class="text-sm text-[#727272]"
                                    >Waktu saat ini</span
                                >
                                <span
                                    class="text-32px font-bold text-[#434343]"
                                >
                                    {{
                                        state.current_time.toLocaleTimeString(
                                            [],
                                            {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                                second: "2-digit",
                                            }
                                        )
                                    }}
                                </span>
                            </div>
                            <hr class="transform scale-x-105" />
                            <div class="p-1">
                                <InputLongText1
                                    label="Keterangan"
                                    v-model="state.form.description"
                                    placeholder="Ketik keterangan"
                                />
                            </div>
                            <div class="flex items-center gap-4 py-2">
                                <button
                                    class="flex-1 h-50px rounded-full bg-[#12D325] text-white font-bold"
                                    @click="submit('in')"
                                    :disabled="!canCheckIn()"
                                    :class="{ 'opacity-50 pointer-events-none': !canCheckIn() }"
                                >
                                    Check in
                                </button>
                                <button
                                    class="flex-1 h-50px rounded-full bg-[#F10A13] border-[#FFB7BA] text-white font-bold"
                                    @click="submit('out')"
                                    :disabled="!canCheckOut()"
                                    :class="{ 'opacity-50 pointer-events-none': !canCheckOut() }"
                                >
                                    Check out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col bg-[#FEFEFE] rounded-2xl pb-4 mb-4">
                    <div class="flex justify-between p-4">
                        <span class="text-17px font-bold text-[#404040]"
                            >Riwayat Absensi</span
                        >
                        <span class="flex-1"></span>
                        <span class="text-14px text-[#F10A13]"
                            @click="toggleHistory"
                            >{{ historyToggleText() }}</span
                        >
                    </div>
                    <div class="px-0 pb-2 flex flex-col gap-3">
                        <div
                            class="border rounded-xl p-3 bg-white shadow-sm"
                            v-for="h in displayedHistory()"
                        >
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-bold text-gray-700">
                                    {{
                                        new Date(h.date).toLocaleDateString("id-ID", {
                                            day: "2-digit",
                                            month: "long",
                                            year: "numeric",
                                        })
                                    }}
                                </span>
                                <span
                                    class="text-10px px-2 py-1 rounded-full font-semibold"
                                    :class="
                                        h.checkIn && h.checkOut
                                            ? 'bg-green-100 text-green-700'
                                            : h.checkIn
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-gray-100 text-gray-600'
                                    "
                                >
                                    {{ getDayState(h) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <div class="bg-green-50 rounded-lg p-2">
                                    <div class="text-10px text-green-700 font-semibold">CHECK IN</div>
                                    <div class="text-sm text-gray-800 font-bold">{{ toTime(h.checkIn) }}</div>
                                    <div class="text-11px text-gray-500 mt-1 truncate">
                                        {{ h.noteIn || "-" }}
                                    </div>
                                </div>
                                <div class="bg-red-50 rounded-lg p-2">
                                    <div class="text-10px text-red-700 font-semibold">CHECK OUT</div>
                                    <div class="text-sm text-gray-800 font-bold">{{ toTime(h.checkOut) }}</div>
                                    <div class="text-11px text-gray-500 mt-1 truncate">
                                        {{ h.noteOut || "-" }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center p-4" v-if="state.show_history && state.historyRaw.length < state.results">
                        <button
                            class="flex-1 h-50px rounded-full bg-[#F10A13] border-[#FFB7BA] text-white font-bold"
                            @click="loadMore()"
                            :disabled="state.history_loading"
                            :class="{ 'opacity-50 pointer-events-none': state.history_loading }"
                        >
                            {{ state.history_loading ? "Memuat..." : "Tampilkan Lebih Banyak" }}
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
