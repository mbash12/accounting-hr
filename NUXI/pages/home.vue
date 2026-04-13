<script setup>
import { onMounted, watch, reactive } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/dist/ScrollTrigger";
import { Icon } from "@iconify/vue";
import { currentUser } from "@/deps/service.js";
import { getData, homeState } from "@/deps/store.js";

gsap.registerPlugin(ScrollTrigger);
const getDt = async (next) => {
    if (next) {
        homeState.page++;
    } else {
        homeState.page--;
    }
    getData();
};
const switchTab = (val) => {
    homeState.tab = val;
    homeState.appliedFilter = "submitted";
    homeState.dates = null;
    homeState.page = 1;
    getData();
};

const switchStatus = (val) => {
    homeState.appliedFilter = val;
    homeState.page = 1;
    getData();
};

watch(
    () => homeState.appliedFilter,
    (val) => {
        switchStatus(val);
    }
);

onMounted(async () => {
    setTimeout(() => {
        gsap.timeline({
            scrollTrigger: {
                scroller: ".ide",
                trigger: ".ide",
                start: "top top",
                end: "top+=100 top",
                scrub: true,
                markers: false,
            },
        })
            .to(".trg", { paddingLeft: 0, paddingRight: 0, duration: 2 }, 1)
            .to(".prf", { scale: 0.9, opacity: 0, duration: 2 }, 0)
            .to(
                ".pgr",
                {
                    bottom: 0,
                    width: "100%",
                    borderRadius: 0,
                    height: 60,
                    duration: 2,
                },
                0
            )
            .to(".pgrs", { display: "block" }, 1)
            .to(".fab", { opacity: 0, pointerEvents: "none" }, 0.3)
            .to(".pgra", { paddingLeft: 16, paddingRight: 16 }, 0)
            .to(
                ".tb",
                {
                    opacity: 0,
                    borderRadius: 0,
                    duration: 2,
                },
                1
            )
            .to(
                ".fltr",
                {
                    opacity: 1,
                    duration: 2,
                },
                1
            );
    }, 0);
    if (currentUser.user?.role === "management") {
        switchTab(1);
    } else {
        switchTab(0);
    }
});
</script>

<template>
    <main>
        <div class="w-full h-full bg-gray-100 overflow-hidden">
            <div class="flex flex-col absolute w-full">
                <div class="w-full h-230px bg-[#F10A13] text-white relative">
                    <img
                        src="/bg.png"
                        alt=""
                        class="w-full h-full object-cover object-top absolute inset-0"
                    />
                    <div
                        class="flex gap-3 items-center prf w-full h-80px py-4 px-6 relative my-4 z-10"
                    >
                        <button
                            class="h-50px w-50px rounded-full flex items-center justify-center flex-shrink-0 border-2 border-white"
                            @click="$router.push('/account')"
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
                                >{{
                                    currentUser?.user?.department_id?.name
                                }}</span
                            >
                        </div>
                        <div
                            class="flex gap-2 items-center flex-shrink-0 ml-auto"
                        >
                            <button
                                class="flex items-center justify-center"
                                @click="$router.push('/faq')"
                            >
                                <Icon
                                    icon="fluent:chat-help-24-filled"
                                    width="2rem"
                                    height="2rem"
                                />
                            </button>
                            <button
                                class="flex items-center justify-center relative"
                                @click="$router.push('/notifications')"
                            >
                                <Icon
                                    icon="fluent:alert-24-filled"
                                    width="2rem"
                                    height="2rem"
                                />
                                <span
                                    class="absolute p-1 rounded-full text-10px font-semibold bg-[#FFA408] shadow border border-white text-black h-3 w-3 flex items-center justify-center top-0 right-0"
                                ></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="w-full h-full overflow-auto relative  ide"
            >
                <div class="px-4 mt-110px trg">
                    <div
                        class="w-full bg-white px-4 rounded-t-2xl tb flex gap-4 items-center z-10"
                    >
                        <div
                            class="flex justify-evenly w-full border-b items-end"
                        >
                            <button
                                class="flex flex-col w-[28%] items-center gap-2 h-full px-2 pt-6"
                                @click="switchTab(0)"
                                v-if="
                                    currentUser?.user?.role === 'admin' ||
                                    currentUser?.user?.role === 'spv' ||
                                    currentUser?.user?.role === 'staff'
                                "
                            >
                                <img
                                    src="/tab-01.png"
                                    alt=""
                                    class="w-26px h-26px object-contain"
                                />
                                <span
                                    class="leading-4 block text-12px h-32px"
                                    :class="
                                        homeState.tab === 0 ? 'font-bold' : ''
                                    "
                                    >Pengajuan Izin</span
                                >
                                <span
                                    class="w-full h-1 bg-red-500 transform transition-all duration-300 rounded-t-full"
                                    :class="
                                        homeState.tab === 0
                                            ? 'opacity-100 scale-x-110'
                                            : 'opacity-0 scale-x-10'
                                    "
                                ></span>
                            </button>
                            <button
                                class="flex flex-col w-[28%] items-center gap-2 h-full px-2 pt-6"
                                @click="switchTab(1)"
                                v-if="
                                    currentUser?.user?.role === 'admin' ||
                                    currentUser?.user?.role === 'spv' ||
                                    currentUser?.user?.role === 'management'
                                "
                            >
                                <img
                                    src="/tab-02.png"
                                    alt=""
                                    class="w-26px h-26px object-contain"
                                />
                                <span
                                    class="leading-4 block text-12px h-32px"
                                    :class="
                                        homeState.tab === 1 ? 'font-bold' : ''
                                    "
                                    >Approval Izin</span
                                >
                                <span
                                    class="w-full h-1 bg-red-500 transform transition-all duration-300 rounded-t-full"
                                    :class="
                                        homeState.tab === 1
                                            ? 'opacity-100 scale-x-110'
                                            : 'opacity-0 scale-x-10'
                                    "
                                ></span>
                            </button>
                            <button
                                class="flex flex-col w-[28%] items-center gap-2 h-full px-2 pt-6"
                                @click="switchTab(3)"
                                v-if="
                                    currentUser?.user?.role === 'admin' ||
                                    currentUser?.user?.role === 'spv'
                                "
                            >
                                <img
                                    src="/tab-04.png"
                                    alt=""
                                    class="w-26px h-26px object-contain"
                                />
                                <span
                                    class="leading-4 block text-12px h-32px"
                                    :class="
                                        homeState.tab === 3 ? 'font-bold' : ''
                                    "
                                    >Approval Absen</span
                                >
                                <span
                                    class="w-full h-1 bg-red-500 transform transition-all duration-300 rounded-t-full"
                                    :class="
                                        homeState.tab === 3
                                            ? 'opacity-100 scale-x-110'
                                            : 'opacity-0 scale-x-10'
                                    "
                                ></span>
                            </button>
                            <button
                                class="flex flex-col w-[28%] items-center gap-2 h-full px-2 pt-6"
                                @click="switchTab(2)"
                                v-if="
                                    currentUser?.user?.role === 'admin' ||
                                    currentUser?.user?.role === 'spv' ||
                                    currentUser?.user?.role === 'staff'
                                "
                            >
                                <img
                                    src="/tab-03.png"
                                    alt=""
                                    class="w-26px h-26px object-contain"
                                />
                                <span
                                    class="leading-4 block text-12px h-32px"
                                    :class="
                                        homeState.tab === 2 ? 'font-bold' : ''
                                    "
                                    >Hutang</span
                                >
                                <span
                                    class="w-full h-1 bg-red-500 transform transition-all duration-300 rounded-t-full"
                                    :class="
                                        homeState.tab === 2
                                            ? 'opacity-100 scale-x-110'
                                            : 'opacity-0 scale-x-10'
                                    "
                                ></span>
                            </button>
                        </div>
                    </div>
                    <div
                        class="font-bold text-sm p-4 text-white sticky top-0 fltr h-60px -mt-60px opacity-0 pointer-events-none flex items-center bg-[#EE0C15]"
                    >
                        {{
                            [
                                "Pengajuan Izin",
                                "Approval Izin",
                                "Hutang",
                                "Approval Absen",
                            ][homeState.tab]
                        }}
                    </div>
                    <div class="w-full bg-white px-2 sticky top-60px py-3">
                        <Segment
                            :options="homeState.filters"
                            v-model="homeState.appliedFilter"
                        />
                    </div>
                    <div
                        class="w-full bg-white shadow-xl pb-60px min-h-90vh px-4"
                        v-if="
                            (homeState.tab == 0 || homeState.tab == 1) &&
                            homeState.data?.length !== 0
                        "
                    >
                        <button
                            class="border-b w-full py-4 px-2 flex flex-col gap-2"
                            v-for="(item, i) in homeState.data"
                            :key="i"
                            @click="
                                () => {
                                    item.status == 'updating'
                                        ? $router.push(
                                              `/permit/?update=${item.id}`
                                          )
                                        : $router.push(`/permit/${item.id}`);
                                }
                            "
                        >
                            <div class="flex justify-between w-full">
                                <span class="text-sm font-bold text-gray-500">{{
                                    item.textType
                                }}</span>
                                <div
                                    class="flex items-center capitalize text-xs text-gray-500"
                                >
                                    <Status :status="item.status" />
                                </div>
                            </div>
                            <div class="flex gap-3 items-center">
                                <div
                                    class="flex gap-2 items-center text-gray-500"
                                >
                                    <Icon
                                        icon="solar:calendar-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span
                                        class="text-xs mt-1px"
                                        v-if="
                                            [
                                                'marry',
                                                'kids_marry',
                                                'khitan',
                                                'family_death',
                                                'inhouse_death',
                                                'maternity',
                                                'maternity_death',
                                                'force_majure',
                                                'absent',
                                                'nodn_sick',
                                                'sick',
                                                'annual',
                                                'wfh',
                                                'holiday',
                                            ].includes(item.sub_type)
                                        "
                                    >
                                        {{
                                            new Date(
                                                item.start
                                            ).toLocaleDateString("id-ID", {
                                                day: "2-digit",
                                                month: "2-digit",
                                                year: "numeric",
                                            })
                                        }}
                                        -
                                        {{
                                            new Date(
                                                item.end
                                            ).toLocaleDateString("id-ID", {
                                                day: "2-digit",
                                                month: "2-digit",
                                                year: "numeric",
                                            })
                                        }}
                                    </span>
                                    <span class="text-xs mt-1px" v-else>
                                        {{
                                            new Date(
                                                item.start
                                            ).toLocaleDateString("id-ID", {
                                                day: "2-digit",
                                                month: "2-digit",
                                                year: "numeric",
                                            })
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex gap-2 items-center text-gray-500"
                                    v-if="
                                        ![
                                            'marry',
                                            'kids_marry',
                                            'khitan',
                                            'family_death',
                                            'inhouse_death',
                                            'maternity',
                                            'maternity_death',
                                            'force_majure',
                                            'absent',
                                            'nodn_sick',
                                            'sick',
                                            'annual',
                                            'wfh',
                                            'holiday',
                                            'halfday',
                                            'others',
                                            'sudden',
                                        ].includes(item.sub_type)
                                    "
                                >
                                    <Icon
                                        icon="solar:clock-square-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span
                                        class="text-xs mt-1px"
                                        v-if="
                                            ![
                                                'halfday',
                                                'late',
                                                'early',
                                            ].includes(item.sub_type)
                                        "
                                    >
                                        {{
                                            new Date(
                                                item.start
                                            ).toLocaleTimeString("id-ID", {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                            })
                                        }}
                                        -
                                        {{
                                            new Date(
                                                item.end
                                            ).toLocaleTimeString("id-ID", {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                            })
                                        }}
                                    </span>
                                    <span
                                        class="text-xs mt-1px"
                                        v-if="
                                            ['late', 'early'].includes(
                                                item.sub_type
                                            )
                                        "
                                    >
                                        {{
                                            new Date(
                                                item.start
                                            ).toLocaleTimeString("id-ID", {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                            })
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex gap-1 items-center text-gray-500"
                                    v-if="
                                        !['halfday', 'late', 'early'].includes(
                                            item.sub_type
                                        )
                                    "
                                >
                                    <Icon
                                        icon="solar:hourglass-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span class="text-xs mt-1px"
                                        >{{
                                            item.duration_um == "days"
                                                ? Math.floor(item.duration)
                                                : item.duration
                                        }}
                                        {{
                                            item.duration_um == "days"
                                                ? "Hari"
                                                : "Jam"
                                        }}</span
                                    >
                                </div>
                            </div>
                            <div
                                class="flex gap-3 items-center"
                                v-if="homeState.tab === 1"
                            >
                                <div
                                    class="flex gap-2 items-center text-gray-500"
                                >
                                    <Icon
                                        icon="solar:user-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span class="text-xs mt-1px">{{
                                        item.user_id?.fullname
                                    }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div
                        class="w-full bg-white shadow-xl pb-60px min-h-90vh px-4"
                        v-if="homeState.tab === 2 && homeState.data?.length > 0"
                    >
                        <button
                            class="border-b w-full py-4 px-2 flex flex-col gap-2"
                            v-for="(item, i) in homeState.data"
                            :key="i"
                            @click="
                                () => {
                                    item.status == 'updating'
                                        ? $router.push(
                                              `/loan/?update=${item.id}`
                                          )
                                        : $router.push(`/loan/${item.id}`);
                                }
                            "
                        >
                            <div class="flex justify-between w-full">
                                <span class="text-sm font-bold text-gray-500"
                                    >Hutang</span
                                >
                                <div
                                    class="flex items-center capitalize text-xs text-gray-500"
                                >
                                    <Status
                                        :status="item.status"
                                        v-if="item.status != 'approved'"
                                    />
                                    <span
                                        class="text-xs px-2 py-1 rounded-xl"
                                        v-else
                                        :class="
                                            item.payment_status == 'paid'
                                                ? 'text-green-500 bg-green-100 '
                                                : 'text-red-500 bg-red-100 '
                                        "
                                    >
                                        {{
                                            item?.payment_status === "paid"
                                                ? "Lunas"
                                                : item?.payment_status === "skip"
                                                ? "Lewati"
                                                : "Belum Lunas"
                                        }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-3 items-center w-full">
                                <div
                                    class="flex flex-col gap-2 items-start w-[35%]"
                                >
                                    <span
                                        class="font-semibold text-11px text-[#707888]"
                                        >Jumlah</span
                                    >
                                    <span
                                        class="text-13px font-semibold text-[#EE0C15]"
                                        >Rp
                                        {{
                                            new Intl.NumberFormat(
                                                "id-ID"
                                            ).format(item.amount)
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex flex-col gap-2 items-start w-[35%]"
                                >
                                    <span
                                        class="font-semibold text-11px text-[#707888]"
                                        >Sumber</span
                                    >
                                    <span
                                        class="text-13px font-semibold capitalize"
                                        >{{
                                            item.source == "bulanan"
                                                ? "Gaji"
                                                : "Uang Makan"
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex flex-col gap-2 items-start w-[30%]"
                                >
                                    <span
                                        class="font-semibold text-11px text-[#707888]"
                                        >Durasi</span
                                    >
                                    <span
                                        class="text-13px font-semibold capitalize"
                                        >{{ item.duration }}
                                        {{
                                            item.source == "bulanan"
                                                ? "Bulan"
                                                : "Minggu"
                                        }}</span
                                    >
                                </div>
                            </div>
                        </button>
                    </div>
                    <div
                        class="w-full bg-white shadow-xl pb-60px min-h-90vh px-4"
                        v-if="homeState.tab === 3 && homeState.data?.length > 0"
                    >
                        <button
                            class="border-b w-full py-4 px-2 flex flex-col gap-2"
                            v-for="(item, i) in homeState.data"
                            :key="i"
                            @click="
                                () => {
                                    $router.push(`/manual/${item.id}`);
                                }
                            "
                        >
                            <div class="flex justify-between w-full">
                                <div class="flex gap-1 items-center">
                                    <span
                                        class="text-sm font-bold text-gray-500"
                                        >{{ item.datetime }}</span
                                    >
                                    <span
                                        class="text-xs font-bold px-1 rounded uppercase"
                                        :class="
                                            item.type == 'in'
                                                ? 'text-green-500 bg-green-100 '
                                                : 'text-red-500 bg-red-100 '
                                        "
                                    >
                                        {{ item.type }}
                                    </span>
                                </div>
                                <div
                                    class="flex items-center capitalize text-xs text-gray-500"
                                >
                                    <Status :status="item.status" />
                                </div>
                            </div>
                            <div class="flex gap-3 items-center min-w-0 w-full">
                                <div
                                    class="flex gap-2 items-center text-gray-500 min-w-0 w-full"
                                >
                                    <Icon
                                        icon="solar:chat-line-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span
                                        class="text-xs mt-1px truncate block w-full text-left"
                                    >
                                        {{ item.note }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-3 items-center">
                                <div
                                    class="flex gap-2 items-center text-gray-500"
                                >
                                    <Icon
                                        icon="solar:user-bold-duotone"
                                        width="1rem"
                                        height="1rem"
                                        class="text-blue-800"
                                    />
                                    <span class="text-xs mt-1px">{{
                                        item.user_id.fullname
                                    }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div
                        class="w-full bg-white shadow-xl pb-60px min-h-90vh flex items-center justify-center flex-col gap-4"
                        v-if="homeState.data?.length === 0"
                    >
                        <svg
                            width="184"
                            height="152"
                            viewBox="0 0 184 152"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g fill="none" fill-rule="evenodd">
                                <g transform="translate(24 31.67)">
                                    <ellipse
                                        fill-opacity=".8"
                                        fill="#F5F5F7"
                                        cx="67.797"
                                        cy="106.89"
                                        rx="67.797"
                                        ry="12.668"
                                    ></ellipse>
                                    <path
                                        d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z"
                                        fill="#AEB8C2"
                                    ></path>
                                    <path
                                        d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z"
                                        fill="url(#linearGradient-1)"
                                        transform="translate(13.56)"
                                    ></path>
                                    <path
                                        d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z"
                                        fill="#F5F5F7"
                                    ></path>
                                    <path
                                        d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z"
                                        fill="#DCE0E6"
                                    ></path>
                                </g>
                                <path
                                    d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z"
                                    fill="#DCE0E6"
                                ></path>
                                <g
                                    transform="translate(149.65 15.383)"
                                    fill="#FFF"
                                >
                                    <ellipse
                                        cx="20.654"
                                        cy="3.167"
                                        rx="2.849"
                                        ry="2.815"
                                    ></ellipse>
                                    <path
                                        d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"
                                    ></path>
                                </g>
                            </g>
                        </svg>
                        <span class="text-sm text-gray-400 font-bold">{{
                            [
                                "Tidak ada pengajuan",
                                "Tidak ada persetujuan",
                                "Tidak ada pengajuan",
                                "Tidak ada persetujuan",
                            ][homeState.tab]
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="absolute w-full left-1/2 h-40px w-50px bg-white pgr overflow-hidden flex flex-col items-center rounded-xl bg-gray-100 bg-opacity-80 backdrop-filter backdrop-blur bottom-10px"
            style="transform: translateX(-50%)"
        >
            <div class="flex items-center gap-4 h-full w-full pgra px-0">
                <button
                    class="pgrs hidden w-120px text-sm bg-white py-2 rounded shadow-sm"
                    @click="getDt(false)"
                    :disabled="homeState.page <= 1"
                >
                    Sebelumnya
                </button>
                <div
                    class="flex-1 flex justify-center items-center text-xs font-bold"
                >
                    {{ homeState.page }}
                    /
                    {{ Math.ceil(homeState.results / 20) }}
                </div>
                <button
                    class="pgrs hidden w-120px text-sm bg-white py-2 rounded shadow-sm"
                    :disabled="
                        homeState.page >= Math.ceil(homeState.results / 20)
                    "
                    @click="getDt(true)"
                >
                    Berikutnya
                </button>
            </div>
        </div>
        <div
            class="absolute right-5px fab bottom-10px opacity-100"
            style="transform-origin: bottom right"
            v-show="homeState.tab === 0 || homeState.tab === 2"
        >
            <FAB />
        </div>
    </main>
</template>
