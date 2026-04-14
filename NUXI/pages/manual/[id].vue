<script setup>
import { onMounted, reactive } from "vue";
import { Icon } from "@iconify/vue";
import {
    getSingleDinas,
    loading,
} from "@/deps/service.js";
import { useRoute, useRouter } from "vue-router";
import { ASSETURL } from "@/deps/env.js";
const router = useRouter();
const route = useRoute();
const state = reactive({
    view_attachment: false,
    data: null,
    location: null,
});
onMounted(async () => {
    loading();
    const id = route.params.id;
    const data = await getSingleDinas(id);
    state.data = data;
    state.location = JSON.parse(data.location);
    loading(false);
});
</script>
<template>
    <main class="bg-white">
        <div class="w-full h-full flex flex-col">
            <Header title="Absen Manual" />
            <div class="flex-1 overflow-auto pb-60px">
                <div class="flex flex-col">
                    <div class="flex flex-col px-6">
                        <div
                            class="flex justify-start items-center py-4 text-sm mt-4 gap-4"
                        >
                            <svg
                                width="22"
                                height="24"
                                viewBox="0 0 22 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3.40313 10.0135H13.3787C13.6016 10.0135 13.7819 9.84783 13.7819 9.64319C13.7819 9.43854 13.6016 9.27292 13.3787 9.27292H3.40313C3.18032 9.27292 3 9.43854 3 9.64319C3 9.84783 3.18032 10.0135 3.40313 10.0135Z"
                                    fill="#F10A13"
                                />
                                <path
                                    d="M6.14759 6.5V3.22727H7.49951C7.74454 3.22727 7.95601 3.27148 8.13392 3.35991C8.3129 3.44727 8.45086 3.57298 8.54781 3.73704C8.64475 3.90004 8.69323 4.09339 8.69323 4.31712C8.69323 4.54403 8.64369 4.73686 8.54461 4.8956C8.44553 5.05327 8.30491 5.17365 8.12274 5.25675C7.94056 5.33878 7.72483 5.37979 7.47554 5.37979H6.62061V4.75657H7.32852C7.44784 4.75657 7.54745 4.74112 7.62735 4.71023C7.70832 4.67827 7.76958 4.63033 7.81112 4.56641C7.85267 4.50142 7.87345 4.41832 7.87345 4.31712C7.87345 4.21591 7.85267 4.13228 7.81112 4.06623C7.76958 3.99911 7.70832 3.94904 7.62735 3.91602C7.54639 3.88192 7.44678 3.86488 7.32852 3.86488H6.93861V6.5H6.14759ZM7.9901 5.00426L8.80509 6.5H7.94216L7.14316 5.00426H7.9901ZM9.12709 7.42045V4.04545H9.90052V4.46573H9.92449C9.95645 4.39116 10.0017 4.31925 10.0603 4.25C10.12 4.18075 10.1956 4.12429 10.2872 4.08061C10.3799 4.03587 10.4907 4.01349 10.6196 4.01349C10.7901 4.01349 10.9494 4.05824 11.0974 4.14773C11.2466 4.23722 11.367 4.37518 11.4586 4.56161C11.5502 4.74805 11.596 4.98562 11.596 5.27433C11.596 5.55238 11.5518 5.78516 11.4634 5.97266C11.376 6.16016 11.2578 6.30078 11.1086 6.39453C10.9605 6.48828 10.7959 6.53516 10.6148 6.53516C10.4913 6.53516 10.3842 6.51491 10.2936 6.47443C10.2031 6.43395 10.1269 6.38068 10.0651 6.31463C10.0044 6.24858 9.95752 6.17773 9.92449 6.1021H9.90851V7.42045H9.12709ZM9.89253 5.27273C9.89253 5.40483 9.91011 5.51989 9.94527 5.6179C9.98149 5.71591 10.0332 5.79208 10.1003 5.84641C10.1685 5.89968 10.25 5.92631 10.3448 5.92631C10.4407 5.92631 10.5222 5.89968 10.5893 5.84641C10.6564 5.79208 10.707 5.71591 10.7411 5.6179C10.7762 5.51989 10.7938 5.40483 10.7938 5.27273C10.7938 5.14062 10.7762 5.0261 10.7411 4.92915C10.707 4.83221 10.6564 4.7571 10.5893 4.70384C10.5232 4.65057 10.4417 4.62393 10.3448 4.62393C10.2489 4.62393 10.1674 4.65004 10.1003 4.70224C10.0332 4.75444 9.98149 4.82901 9.94527 4.92596C9.91011 5.0229 9.89253 5.13849 9.89253 5.27273Z"
                                    fill="#F10A13"
                                />
                                <path
                                    d="M3.49151 11.8443H13.4671C13.6899 11.8443 13.8703 11.6787 13.8703 11.474C13.8703 11.2694 13.6899 11.1038 13.4671 11.1038H3.49151C3.2687 11.1038 3.08838 11.2694 3.08838 11.474C3.08838 11.6787 3.2687 11.8443 3.49151 11.8443Z"
                                    fill="#F10A13"
                                />
                                <path
                                    d="M6.55163 18.4327H3.49151C3.2687 18.4327 3.08838 18.5984 3.08838 18.803C3.08838 19.0077 3.2687 19.1733 3.49151 19.1733H6.55163C6.77444 19.1733 6.95477 19.0077 6.95477 18.803C6.95477 18.5984 6.77444 18.4327 6.55163 18.4327Z"
                                    fill="#F10A13"
                                />
                                <path
                                    d="M16.7659 12.0718V1.6859C16.7659 0.816658 15.9959 0.109375 15.0494 0.109375H1.71647C0.770028 0.109375 0 0.816658 0 1.6859V20.3464C0 21.2157 0.770068 21.9229 1.71647 21.9229H12.3398C13.3529 22.6366 14.6153 23.0659 15.9876 23.0659C19.3028 23.0659 22 20.5879 22 17.5423C22 14.7402 19.7139 12.4242 16.7659 12.0718ZM1.71647 21.1824C1.21452 21.1824 0.80627 20.8074 0.80627 20.3464V1.6859C0.80627 1.22488 1.21452 0.849908 1.71647 0.849908H15.0494C15.5514 0.849908 15.9596 1.22488 15.9596 1.6859V12.0214C14.7492 12.0266 13.6239 12.3642 12.6816 12.9364H3.39512C3.17231 12.9364 2.99199 13.102 2.99199 13.3066C2.99199 13.5113 3.17231 13.6769 3.39512 13.6769H11.701C11.3519 14.0033 11.0504 14.3712 10.7975 14.7685H3.39512C3.17231 14.7685 2.99199 14.9341 2.99199 15.1388C2.99199 15.3434 3.17231 15.5091 3.39512 15.5091H10.4027C10.2518 15.8574 10.1403 16.223 10.069 16.6018H3.39512C3.17231 16.6018 2.99199 16.7674 2.99199 16.9721C2.99199 17.1767 3.17231 17.3423 3.39512 17.3423H9.98618C9.98356 17.4092 9.97517 17.4748 9.97517 17.5423C9.97517 18.9371 10.5454 20.2091 11.4771 21.1824H1.71647ZM16.3907 22.3066V22.0216C16.3907 21.817 16.2104 21.6513 15.9876 21.6513C15.7648 21.6513 15.5845 21.817 15.5845 22.0216V22.3074C13.0357 22.1266 10.9986 20.2545 10.8019 17.9125H11.1126C11.3354 17.9125 11.5157 17.7469 11.5157 17.5423C11.5157 17.3376 11.3354 17.172 11.1126 17.172H10.8019C10.9986 14.8308 13.0354 12.9601 15.5845 12.7794V13.0644C15.5845 13.269 15.7648 13.4346 15.9876 13.4346C16.2104 13.4346 16.3907 13.269 16.3907 13.0644V12.7794C18.9398 12.9601 20.9775 14.8311 21.1743 17.172H20.7804C20.5575 17.172 20.3772 17.3376 20.3772 17.5423C20.3772 17.7469 20.5575 17.9125 20.7804 17.9125H21.1733C20.9766 20.2544 18.9398 22.1259 16.3907 22.3066Z"
                                    fill="#F10A13"
                                />
                                <path
                                    d="M16.1324 14.9523C15.9096 14.9523 15.7293 15.1179 15.7293 15.3225V17.867L14.4454 18.9713C14.2829 19.1109 14.2746 19.3452 14.4269 19.4946C14.5061 19.5723 14.6135 19.6117 14.721 19.6117C14.8198 19.6117 14.9186 19.5785 14.9966 19.5115L16.408 18.2977C16.4895 18.2279 16.5355 18.1299 16.5355 18.0276V15.3225C16.5355 15.1179 16.3552 14.9523 16.1324 14.9523Z"
                                    fill="#F10A13"
                                />
                            </svg>

                            <span class="font-bold text-17px text-[#404040]"
                                >Detail Absen Manual</span
                            >
                        </div>
                        <div
                            class="flex justify-between items-center py-4 border-b text-sm font-medium"
                        >
                            <span class="text-gray-500">Status</span>
                            <Status :status="state.data?.status" />
                        </div>
                        <div
                            class="flex justify-between items-center py-4 border-b text-sm font-medium"
                        >
                            <span class="text-gray-500">TIpe</span>
                            <span class="text-sm">
                                {{
                                    state.data?.type == "in"
                                        ? "Datang"
                                        : "Pulang"
                                }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center py-4 border-b text-sm font-medium"
                        >
                            <span class="text-gray-500">Tanggal & Jam</span>
                            <span class="capitalize">
                                {{
                                    new Date(
                                        state.data?.datetime
                                    ).toLocaleString("id-ID", {
                                        day: "2-digit",
                                        month: "2-digit",
                                        year: "numeric",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                    })
                                }}
                            </span>
                        </div>
                        <div
                            class="flex justify-between items-center py-4 border-b text-sm font-medium"
                            v-if="state.data?.location"
                        >
                            <span class="text-gray-500">Lokasi</span>
                            <a
                                :href="`https://www.google.com/maps/?q=${state.location?.latitude},${state.location?.longitude}&z=21`"
                                target="_blank"
                                class="flex gap-2 items-center"
                            >
                                <span class="text-red-500 font-bold">
                                    Lihat di Maps
                                </span>
                                <svg
                                    width="16"
                                    height="17"
                                    viewBox="0 0 16 17"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g clip-path="url(#clip0_2235_33)">
                                        <path
                                            d="M10.8121 5.18692C11.5874 5.18692 12.2182 4.55615 12.2182 3.78084C12.2182 3.00552 11.5874 2.37476 10.8121 2.37476C10.0368 2.37476 9.40601 3.00552 9.40601 3.78084C9.40601 4.55615 10.0367 5.18692 10.8121 5.18692ZM10.8121 3.31214C11.0705 3.31214 11.2808 3.5224 11.2808 3.78084C11.2808 4.03928 11.0705 4.24953 10.8121 4.24953C10.5537 4.24953 10.3434 4.03928 10.3434 3.78084C10.3434 3.5224 10.5536 3.31214 10.8121 3.31214Z"
                                            fill="#F10A13"
                                        />
                                        <path
                                            d="M0.296598 14.5902C0.296598 14.5902 5.01094 16.4635 5.01097 16.4635C5.13311 16.5017 5.20654 16.5172 5.33606 16.4741L10.7981 14.6534L15.3573 16.465C15.5013 16.5222 15.6648 16.5047 15.7936 16.4172C15.9221 16.33 15.9991 16.1848 15.9991 16.0294V5.65567C15.9991 5.46313 15.8813 5.29018 15.7022 5.21959L14.0027 4.54986C14.0623 4.3007 14.093 4.0432 14.093 3.78086C14.093 1.97179 12.6213 0.5 10.8122 0.5C9.00312 0.5 7.53133 1.97179 7.53133 3.78086C7.53133 3.97824 7.54917 4.17275 7.5831 4.3632L5.20195 5.15691L0.642744 3.34532C0.498449 3.28798 0.334999 3.30582 0.206452 3.39306C0.0779361 3.4803 0.000976562 3.62556 0.000976562 3.78089V14.1547C0.000976562 14.3467 0.118119 14.5193 0.296598 14.5902ZM15.0617 5.97475V15.3389L11.2809 13.8365V8.62406L13.4382 5.74791C13.5174 5.64232 13.5896 5.53293 13.6548 5.42035L15.0617 5.97475ZM10.8122 1.43742C12.1044 1.43742 13.1557 2.4887 13.1557 3.78089C13.1557 4.29217 12.9941 4.77786 12.6883 5.18541L10.8122 7.68671L8.93604 5.18538C8.64113 4.79224 8.46872 4.30873 8.46872 3.78086C8.46872 2.4887 9.51997 1.43742 10.8122 1.43742ZM8.18616 5.74791L10.3435 8.62406V13.8168L5.65655 15.3791V5.99347L7.87929 5.25256C7.96612 5.42485 8.06817 5.59064 8.18616 5.74791ZM0.938365 4.47146L4.71916 5.97375V15.3389L0.938365 13.8366V4.47146Z"
                                            fill="#F10A13"
                                        />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2235_33">
                                            <rect
                                                width="16"
                                                height="16"
                                                fill="white"
                                                transform="translate(0 0.5)"
                                            />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </div>

                        <div class="flex flex-col border-b text-sm py-4 gap-2">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Catatan</span>
                                <!-- <span>Value</span> -->
                            </div>
                            <div
                                class="text-sm"
                                v-html="
                                    state?.data?.note?.replace(/\n/g, '<br/>')
                                "
                            ></div>
                        </div>

                        <div
                            class="flex flex-col border-b text-sm py-4 gap-2"
                            v-if="state?.data?.attachment"
                        >
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Foto Wajah</span>
                            </div>
                            <div class="text-xs bg-gray-100 block relative">
                                <img
                                    :src="ASSETURL + state?.data?.attachment"
                                    alt=""
                                    class="transition-all duration-300 w-full object-cover"
                                    :class="
                                        state.view_attachment
                                            ? 'max-h-200 '
                                            : ' max-h-32'
                                    "
                                    @click="
                                        state.view_attachment =
                                            !state.view_attachment
                                    "
                                />
                                <a
                                    class="absolute top-1 right-1 flex items-center justify-center text-white"
                                    :href="ASSETURL + state?.data?.attachment"
                                    target="_blank"
                                >
                                    <Icon
                                        icon="solar:square-arrow-right-up-bold"
                                        width="2rem"
                                        height="2rem"
                                    />
                                </a>
                            </div>
                        </div>
                        <div
                            class="flex flex-col border-b text-sm py-4 gap-2"
                            v-if="state?.data?.reject_reason"
                        >
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500"
                                    >Alasan Ditolak</span
                                >
                            </div>
                            <div
                                class="text-sm"
                                v-html="
                                    state?.data?.reject_reason.replace(
                                        /\n/g,
                                        '<br/>'
                                    )
                                "
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-4 border-t flex flex-col">
                <Button block variant="gray" class="text-14px" @click="router.go(-1)">Kembali</Button>
            </div>
        </div>
    </main>
</template>
