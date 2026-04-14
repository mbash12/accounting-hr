<script setup>
import { Icon } from "@iconify/vue";
import { checkLoggedin, currentUser, logout } from "@/deps/service.js";
import { useRouter } from "vue-router";

const router = useRouter();
const doLogout = async () => {
    await logout();
    await checkLoggedin();
    await router.push("/auth");
}
const formatJoinDate = (value) => {
    if (!value) return "-";
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return "-";
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
};
const formatEmployeeStatus = (value) => {
    const map = {
        permanent: "Tetap",
        contract: "Kontrak",
        internship: "Magang",
        probation: "Probation",
    };
    return map[value] || "-";
};
</script>

<template>
    <main>
        <div class="w-full h-full flex flex-col bg-gray-100 overflow-hidden">
            <div class="flex flex-col w-full h-full">
                <div
                    class="w-full h-280px bg-[#F10A13] p-4 text-white relative"
                >
                <img
                        src="/bg.png"
                        alt=""
                        class="w-full h-full object-cover object-top absolute inset-0"
                    />
                    <div class="flex gap-2 items-center z-10 relative justify-center">
                        <!-- <span class="font-bold text-sm text-center">Profile</span> -->
                    </div>
                    <div class="mt-4 flex items-center justify-center relative z-20">
                        <div
                            class="flex flex-col justify-center gap-2 items-center w-full"
                        >
                            <div
                                class="h-100px w-100px rounded-full flex items-center justify-center border-4 border-white shadow-md"
                            >
                                <Icon
                                    icon="solar:user-circle-bold-duotone"
                                    class="w-full h-full"
                                />
                            </div>
                            <div class="flex-1 flex flex-col items-center">
                                <span class="font-bold">{{
                                    currentUser?.user?.fullname
                                }}</span>
                                <span class="text-xs opacity-80">{{
                                    currentUser?.user?.department_id?.name
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 -mt-16 mb-20 flex-1 relative  z-20">
                    <div
                        class="bg-white w-full h-full rounded-xl shadow p-4 flex flex-col"
                    >
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">ID Karyawan</span>
                            <span>{{ currentUser?.user?.employee_id || "-" }}</span>
                        </div>
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">Jabatan</span>
                            <span>{{ currentUser?.user?.position || "-" }}</span>
                        </div>
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">Status Karyawan</span>
                            <span>{{ formatEmployeeStatus(currentUser?.user?.status) }}</span>
                        </div>
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">Tanggal Bergabung</span>
                            <span>{{ formatJoinDate(currentUser?.user?.join_date || currentUser?.user?.hire_date) }}</span>
                        </div>
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">Email</span>
                            <span>{{ currentUser?.user?.email || "-" }}</span>
                        </div>
                        <div class="flex justify-between text-xs py-4 border-b px-2">
                            <span class="text-gray-500">NIK</span>
                            <span>{{ currentUser?.user?.nik || "-" }}</span>
                        </div>
                        <span class="flex-1"></span>
                        <span class="font-light text-14px text-[#919294] pb-4 text-center">
                            Version 2.0
                        </span>
                    </div>
                </div>
                <div
                    class="absolute bottom-0 left-0 w-full bg-white px-4 py-2 border-t flex gap-4"
                >
                    <Button block variant="gray" class="text-14px w-1/2"
                    @click="$router.go(-1)"
                        >Kembali</Button
                    >
                    <Button block variant="red" class="text-14px w-1/2"
                    @click="doLogout"
                        >Logout</Button
                    >
                </div>
            </div>
        </div>
    </main>
</template>
