<script setup>
import { onMounted, reactive, ref } from "vue";
import { Icon } from "@iconify/vue";
import { getFaqData, loading } from "@/deps/service.js";
const search_field = ref(null);
const state = reactive({
    on_search: false,
    search: "",
    data: null,
    current_category: null,
    current_faq: null,
});
onMounted(async () => {
    loading();
    await getFaqData().then((d) => {
        state.data = d.records.filter((r) => r.faqs?.length);
        state.current_category = state.data[0];
    });
    loading(false);
});
const selectCategory = (cat) => {
    state.current_category = cat;
    state.current_faq = null;
};
const toggleSearch = () => {
    state.on_search = !state.on_search;
    state.search = "";
    if (state.on_search) {
        search_field.value.focus();
    }
};
</script>
<template>
    <main>
        <div class="w-full h-full flex flex-col bg-white">
            <Header :back="true" title="FAQ" />
            <div
                class="px-2 border-b h-60px flex items-center overflow-x-auto gap-2"
            >
                <button
                    v-for="cat in state.data"
                    class="text-xs px-4 py-2 rounded-full cursor-pointer font-medium whitespace-nowrap"
                    :class="`${
                        state.current_category?.id == cat?.id
                            ? 'bg-gradient-to-bl from-[#FF6C69] to-[#F20A13] text-white border border-[#FFB7BA]'
                            : 'text-gray-500'
                    }`"
                    @click="selectCategory(cat)"
                >
                    {{ cat.name }}
                </button>
            </div>
            <div class="flex-1 overflow-auto pb-60px">
                <div
                    class="border-b-4 border-gray-100 p-4 flex flex-col"
                    v-for="(faq, i) in state.current_category?.faqs"
                >
                    <div
                        class="flex justify-between"
                        @click="
                            state.current_faq =
                                state?.current_faq === i ? null : i
                        "
                    >
                        <span class="text-xs font-semibold leading-5">{{
                            faq.question
                        }}</span>
                        <Icon
                            icon="solar:alt-arrow-down-linear"
                            width="1rem"
                            height="1rem"
                            class="flex-shrink-0 ml-2"
                        />
                    </div>
                    <div
                        class="text-xs text-gray-500 overflow-hidden transition-all duration-300 leading-5"
                        v-html="faq.answer"
                        :class="`${
                            state?.current_faq === i
                                ? 'max-h-100 mt-3'
                                : 'max-h-0 mt-0'
                        }`"
                    ></div>
                </div>
                <div class="h-10px">

                </div>
            </div>

            <div
                class="absolute bottom-0 left-0 w-full gap-4 bg-white px-4 py-2 border-t flex"
            >
                <Button block variant="gray" class="text-14px" @click="$router.go(-1)"
                    >Kembali</Button
                >
            </div>
        </div>
        <div
            class="absolute flex flex-col transition-all duration-300"
            :class="
                state.on_search
                    ? 'w-full h-full right-0 top-0'
                    : 'w-55px h-55px right-0 top-0 '
            "
        >
            <div
                class="transition-all duration-300 flex items-center relative"
                :class="
                    state.on_search
                        ? 'w-full h-55px shadow-lg bg-white  p-1'
                        : 'bg-transparent p-0'
                "
            >
                <div
                    class="w-55px h-55px flex items-center justify-center"
                    @click="toggleSearch"
                >
                    <Icon
                        icon="ph:magnifying-glass-duotone"
                        width="1.2rem"
                        height="1.2rem"
                        :class="state.on_search ? 'text-red-500' : 'text-white'"
                    />
                </div>
                <input
                    type="text"
                    class="transition-all duration-300 min-w-0"
                    :class="
                        state.on_search
                            ? 'w-full h-55px bg-transparent flex items-center px-2 focus:outline-none'
                            : 'w-0 h-0 p-0'
                    "
                    placeholder="Cari"
                    v-model="state.search"
                    ref="search_field"
                />
            </div>
            <div
                class="flex-1 bg-white transition-all overflow-auto duration-300"
                :class="state.search != '' ? 'opacity-100' : 'opacity-0'"
                v-if="state.search != ''"
            >
                <div class="flex flex-col">
                    <div
                        class="flex flex-col"
                        v-for="cat in state.data"
                        v-show="
                            cat.faqs
                                .map((f) => `${f.question} ${f.answer}`)
                                .join(' ')
                                .toLocaleLowerCase()
                                .includes(state.search.toLocaleLowerCase())
                        "
                    >
                        <div class="w-full py-2 sticky top-0 px-4 bg-gray-100">
                            <span class="text-sm font-bold">{{
                                cat.name
                            }}</span>
                        </div>
                        <div
                            class="border-b flex flex-col px-4 pb-4"
                            v-for="(faq, i) in cat.faqs"
                            :key="i"
                            v-show="
                                `${faq.question} ${faq.answer}`
                                    .toLocaleLowerCase()
                                    .includes(state.search.toLocaleLowerCase())
                            "
                        >
                            <div
                                class="flex justify-between py-2"
                                @click="
                                    state.current_faq =
                                        state?.current_faq === i ? null : i
                                "
                            >
                                <span
                                    class="text-xs font-semibold py-2 leading-5"
                                    >{{ faq.question }}</span
                                >
                            </div>
                            <div
                                class="text-xs text-gray-500 overflow-hidden transition-all duration-300 leading-5"
                                v-html="faq.answer"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1" v-else @click="toggleSearch"></div>
        </div>
    </main>
</template>
