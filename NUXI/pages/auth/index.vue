<template>
    <main class="bg-white overflow-auto h-screen">
        <div class="flex flex-col py-8 gap-4 items-center h-full px-6 max-w-xl mx-auto w-full">
            <div class="w-full mb-6 flex items-center justify-center">
                <img src="/accounting-logo.png" alt="Elevate Attendance Logo" class="h-24 object-contain" />
            </div>
            <div class="flex flex-col gap-5 text-left w-full mb-4 px-2">
                <span class="font-regular text-26px text-[#404040]">Login</span>
                <span class="text-[#565656] font-light text-16px">Masukkan email dan password Anda</span>
            </div>
            <div class="bg-white w-full rounded-2xl flex flex-col px-5 mb-6 border border-gray-100"
                style="box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1)">
                <div class="flex items-center h-75px gap-4">
                    <div class="bg-[#E0F3FE] rounded-full p-1.5 w-35px h-35px flex items-center justify-center text-[#40B6F4]">
                        <Icon icon="clarity:email-line" width="1.5rem" height="1.5rem" />
                    </div>
                    <input type="email" class="w-full h-full outline-none" v-model="state.email" placeholder="Email Address" />
                </div>
                <hr />
                <div class="flex items-center h-75px gap-4">
                    <div class="bg-[#E0F3FE] rounded-full p-1.5 w-35px h-35px flex items-center justify-center text-[#40B6F4]">
                        <Icon icon="clarity:lock-line" width="1.5rem" height="1.5rem" />
                    </div>
                    <input type="password" class="w-full h-full outline-none" v-model="state.password" placeholder="Password" />
                </div>
            </div>
            <Button class="w-full flex items-center justify-center h-60px gap-4 flex-shrink-0" variant="red" @click="handleLogin">
                Sign in
            </Button>

            <span class="mt-4 text-[#40B6F4] cursor-pointer" @click="$router.push('/auth/forgot')">Forgot Password</span>
            <span class="flex-1"></span>
            <span class="font-light text-14px text-[#919294] pb-10">Copyright © Elevate Attendance</span>
        </div>
    </main>
</template>

<script setup>
import { onMounted, reactive } from 'vue';
import { Icon } from '@iconify/vue';
import { checkLoggedin, loginWithPassword, loading } from '@/deps/service.js';
import Swal from "sweetalert2";

const state = reactive({
    email: "",
    password: "",
});

const handleLogin = async () => {
    if (!state.email || !state.password) {
        Swal.fire("Eror!", "Email atau Password tidak boleh kosong", "error");
        return;
    }
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if (!emailRegex.test(state.email)) {
        Swal.fire("Eror!", "Email tidak valid", "error");
        return;
    }
    loading(true);
    try {
        const isLoggedin = await loginWithPassword(state.email, state.password);
        loading(false);
        if (!isLoggedin?.ok) {
            Swal.fire("Eror!", "Akun tidak terdaftar", "error");
            return;
        }
        await navigateTo('/home');
    } catch (error) {
        loading(false);
        Swal.fire("Eror!", "Email atau Password salah", "error");
    }
};

onMounted(async () => {
    try {
        const user = await checkLoggedin();
        if (user?.loggedin) {
            await navigateTo('/home');
        }
    } catch (err) {
        console.error("Error checking current user:", err);
    }
});
</script>
