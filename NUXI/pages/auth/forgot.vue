<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
    getAuth,
    sendPasswordResetEmail,
    GoogleAuthProvider,
} from "firebase/auth";
import { Icon } from "@iconify/vue";
import { grant, checkLoggedin, loading } from "@/deps/service.js";
import Swal from "sweetalert2";
const auth = getAuth();

const state = reactive({
    email: "",
});

const handleSubmit = () => {
    state.error = null;
    if (!state.email) {
        Swal.fire("Eror!", "Email tidak boleh kosong", "error");
        return;
    }

    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailRegex.test(state.email)) {
        Swal.fire("Eror!", "Email tidak valid", "error");
        return;
    }
    loading(true);

    sendPasswordResetEmail(auth, state.email)
        .then(() => {
            loading(false);
            Swal.fire(
                "Berhasil!",
                "Link reset password telah dikirim ke email anda",
                "success"
            ).then(() => {
                router.push("/auth");
            })

        })
        .catch((error) => {
            loading(false);
            Swal.fire("Eror!", "Email tidak terdaftar", "error");
        });
};
</script>

<template>
    <main class="bg-white overflow-auto">
        <div
            class="flex flex-col py-8 gap-4 items-center h-full px-6 max-w-xl mx-auto w-full"
        >
        <div class="w-220px h-260px mb-10 flex items-center justify-center">
          <img src="/auth.png" alt="" class="h-full object-contain" />
        </div>
            <div class="flex flex-col gap-5 text-left w-full mb-4 px-2">
                <span class="font-regualar text-26px text-[#404040]"
                    >Forgot Password</span
                >
                <span class="text-[#565656] font-light text-16px leading-5"
                    >Masukkan email anda, kami akan mengirim link untuk mereset
                    password melalui email</span
                >
            </div>
            <div
                class="bg-white w-full rounded-2xl flex flex-col px-5 mb-6 border border-gray-100"
                style="box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1)"
            >
                <div class="flex items-center h-75px gap-4">
                    <div
                        class="bg-[#FFEDED] rounded-full p-1.5 w-35px h-35px flex items-center justify-center text-[#F10A13]"
                    >
                        <Icon
                            icon="clarity:email-line"
                            width="1.5rem"
                            height="1.5rem"
                        />
                    </div>
                    <input
                        type="email"
                        class="w-full h-full outline-none"
                        v-model="state.email"
                        placeholder="Email Address"
                    />
                </div>
            </div>
            <Button
                class="w-full flex items-center justify-center h-60px gap-4 flex-shrink-0"
                variant="red"
                @click="handleSubmit"
            >
                Submit
            </Button>
            <span
                class="mt-4 text-[#EE1F25] cursor-pointer"
                @click="$router.go(-1)"
                >Back</span
            >

            <span class="flex-1"></span>
            <span class="font-light text-14px text-[#919294] pb-10"
                >Copyright © PT Pelangi Sentral Kreasi</span
            >
        </div>
    </main>
</template>
