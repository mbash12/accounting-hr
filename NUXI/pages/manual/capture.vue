<script setup>
import { ref, reactive, onMounted, onUnmounted } from "vue";
const { $swal } = useNuxtApp();
import dayjs from "dayjs";
import {useRouter} from 'vue-router'
import { submitManualWithPhoto } from "@/deps/service.js";
const router = useRouter();
const videoElement = ref(null);
const canvasElement = ref(null);
const buttonElement = ref(null);
let stream;
const state = reactive({
    ready: false,
    file:null,
    form: {
        latitude: null,
        longitude: null,
        image: null,
        current_time: null,
        type: "in",
        description: null,
    },
});

const startCamera = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        videoElement.value.srcObject = stream;
        state.ready = true;
        await videoElement.value.play();
    } catch (err) {
        $swal.fire({
            icon: "error",
            title: "Gagal mengakses kamera",
            text: "Pastikan Anda telah memberikan izin akses kamera",
        });
    }
};

const captureImage = async () => {
    if (state.ready && !state.form.image) {
        const video = videoElement.value;
        const canvas = canvasElement.value;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas
            .getContext("2d")
            .drawImage(video, 0, 0, canvas.width, canvas.height);
        state.form.image = canvas.toDataURL("image/png");
        const blobBin = atob(state.form.image.split(",")[1]);
        const array = [];
        for (let i = 0; i < blobBin.length; i++) {
            array.push(blobBin.charCodeAt(i));
        }
        const file = new Blob([new Uint8Array(array)], { type: "image/png" });
        state.file = new File([file], "absensi.png", { type: "image/png" });
        $swal
            .fire({
                icon: "question",
                title: "Konfirmasi",
                text: "Anda yakin ingin mengirimkan absensi saat ini?",
                showCancelButton: true,
                confirmButtonText: "Ya, kirim",
                cancelButtonText: "Tidak",
            })
            .then(async (confirm) => {
                if (confirm.isConfirmed) {
                    const res = await submit();
                }
            });
    }
};

const clearImage = () => {
    state.form.image = null;
};

const action = async () => {
    if (!state.ready) {
        await startCamera();
    } else if (state.form.image) {
        clearImage();
    } else {
        captureImage();
    }
};

const submit = async () => {
    const date = dayjs(state.form.current_time).format('YYYY-MM-DD');
    const data = {
        type: state.form.type,
        datetime: state.form.current_time,
        date: date,
        note: state.form.description,
        status: "submitted",
        location: JSON.stringify({
            latitude: state.form.latitude,
            longitude: state.form.longitude})
    };
    try {
        let res = await submitManualWithPhoto(data, state.file);
        localStorage.removeItem("manual");
        $swal.fire({
            icon: "success",
            title: "Success",
            text: "Pengajuan anda telah dikirim",
            showConfirmButton: false,
            timer: 2000,
        }).then(async () => {
            router.push("/manual");
        });
    } catch (error) {
        const msg = error?.data?.message || error?.message || "Gagal mengirim absensi. Silakan coba lagi.";
        clearImage();
        $swal.fire({
            icon: "error",
            title: "Gagal",
            text: msg,
        });
    }
};

onMounted(() => {
    let data = localStorage.getItem("manual");
    if (data) {
        data = JSON.parse(data);
        state.form.type = data.type;
        state.form.description = data.note;
        state.form.latitude = data.latitude;
        state.form.longitude = data.longitude;
        state.form.current_time = data.datetime;
    }else{
        router.push("/home");
    }
    startCamera();
});

onUnmounted(() => {
    if (stream) {
        stream.getTracks().forEach((track) => track.stop());
    }
});
</script>

<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <Header title="Check in Wajah" />
            <div
                class="w-full h-full flex flex-col justify-center gap-5 relative"
            >
                <video
                    ref="videoElement"
                    autoplay
                    class="w-full h-full object-cover"
                ></video>
                <canvas
                    ref="canvasElement"
                    class="w-full h-full hidden"
                ></canvas>
                <img
                    :src="state.form.image"
                    alt=""
                    v-if="state.form.image"
                    class="w-full h-full object-cover absolute inset-0"
                />
                <div
                    class="absolute w-full h-full inset-0 p-10 flex items-center justify-center"
                >
                    <svg
                        width="351"
                        height="530"
                        viewBox="0 0 351 530"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M3 47.1077V13C3 7.47714 7.47715 3 13 3H42.5"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M3 482.892V517C3 522.523 7.47715 527 13 527H42.5"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M348 47.1077V13C348 7.47714 343.523 3 338 3H308.5"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                        <path
                            d="M348 482.892V517C348 522.523 343.523 527 338 527H308.5"
                            stroke="white"
                            stroke-width="5"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>
                <button
                    ref="buttonElement"
                    @click="action"
                    class="fixed bottom-8 left-1/2 transform translate-x-[-50%]"
                >
                    <svg
                        width="97"
                        height="97"
                        viewBox="0 0 97 97"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M6 48.5C6 25.0279 25.0279 6 48.5 6V6C71.9721 6 91 25.0279 91 48.5V48.5C91 71.9721 71.9721 91 48.5 91V91C25.0279 91 6 71.9721 6 48.5V48.5Z"
                            fill="white"
                        />
                        <path
                            d="M6 48.5C6 25.0279 25.0279 6 48.5 6V6C71.9721 6 91 25.0279 91 48.5V48.5C91 71.9721 71.9721 91 48.5 91V91C25.0279 91 6 71.9721 6 48.5V48.5Z"
                            fill="white"
                        />
                        <path
                            d="M3 48.5C3 73.629 23.371 94 48.5 94C73.629 94 94 73.629 94 48.5C94 23.371 73.629 3 48.5 3C23.371 3 3 23.371 3 48.5Z"
                            stroke="white"
                            stroke-opacity="0.27"
                            stroke-width="6"
                        />
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M43.3707 65.75H52.629C59.1311 65.75 62.3832 65.75 64.7186 64.2187C65.7266 63.5586 66.5945 62.7062 67.2728 61.7104C68.8332 59.4187 68.8332 56.225 68.8332 49.8416C68.8332 43.4583 68.8332 40.2646 67.2728 37.9729C66.5945 36.977 65.7266 36.1247 64.7186 35.4646C63.2186 34.4791 61.3394 34.1271 58.4623 34.0021C57.0894 34.0021 55.9082 32.9812 55.6394 31.6583C55.434 30.6893 54.9004 29.8209 54.1287 29.1999C53.357 28.5789 52.3945 28.2433 51.404 28.25H44.5957C42.5373 28.25 40.7644 29.6771 40.3603 31.6583C40.0915 32.9812 38.9103 34.0021 37.5373 34.0021C34.6623 34.1271 32.7832 34.4812 31.2811 35.4646C30.2739 36.1249 29.4067 36.9772 28.729 37.9729C27.1665 40.2646 27.1665 43.4562 27.1665 49.8416C27.1665 56.2271 27.1665 59.4166 28.7269 61.7104C29.4019 62.7021 30.2686 63.5541 31.2811 64.2187C33.6165 65.75 36.8686 65.75 43.3707 65.75ZM47.9998 41.3187C43.2061 41.3187 39.3186 45.1333 39.3186 49.8396C39.3186 54.5458 43.2082 58.3666 47.9998 58.3666C52.7915 58.3666 56.6811 54.55 56.6811 49.8437C56.6811 45.1375 52.7915 41.3187 47.9998 41.3187ZM47.9998 44.7271C45.1248 44.7271 42.7915 47.0166 42.7915 49.8416C42.7915 52.6646 45.1248 54.9541 47.9998 54.9541C50.8748 54.9541 53.2082 52.6646 53.2082 49.8416C53.2082 47.0187 50.8748 44.7271 47.9998 44.7271ZM57.8373 43.0229C57.8373 42.0812 58.6144 41.3187 59.5748 41.3187H61.8873C62.8457 41.3187 63.6248 42.0812 63.6248 43.0229C63.6204 43.4789 63.4352 43.9146 63.1098 44.2341C62.7844 44.5536 62.3455 44.7309 61.8894 44.7271H59.5748C59.3489 44.7293 59.1246 44.6869 58.915 44.6025C58.7054 44.518 58.5145 44.3931 58.3531 44.2348C58.1918 44.0766 58.0632 43.8881 57.9747 43.6802C57.8862 43.4722 57.8395 43.2489 57.8373 43.0229Z"
                            fill="#40B6F4"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </main>
</template>
