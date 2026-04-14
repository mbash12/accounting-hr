import { reactive } from "vue";
import { getForms, loading } from "@/deps/service.js";
export const leave_types = {
    annual: "Cuti Tahunan",
    marry: "Cuti Menikah",
    kids_marry: "Cuti Menikahkan Anak",
    khitan: "Cuti Khitan/Baptis Anak",
    // family_death: "Cuti Keluarga Meninggal",
    inhouse_death: "Cuti Keluarga Inti (Suami, Istri, Orangtua, Mertua, dan Anak) Meninggal",
    family_death: "Cuti Keluarga Inti (Suami, Istri, Orangtua, Mertua, dan Anak) Meninggal",
    maternity_husband: "Cuti Istri Melahirkan",
    maternity: "Cuti Melahirkan",
    maternity_death: "Cuti Keguguran",
    force_majure: "Cuti Bencana Alam",
    nodn_sick: "Sakit Tanpa Surat",
    sick: "Sakit Dengan Surat",
    others:"Izin",
    sudden:"Izin Mendadak"
};

export const homeState = reactive({
    tab: 0,
    data: null,
    page: 1,
    results: 0,
    appliedFilter: "submitted",
    isSpv: false,
    dates: null,
    filters: [
        { value: "submitted", text: "Diajukan" },
        { value: "approved", text: "Disetujui" },
        { value: "rejected", text: "Ditolak" },
    ],
});

export const getData = async () => {
    loading();
    const params = {
        page: homeState.page,
        status: homeState.appliedFilter,
    };

    getForms(params).then((res) => {
        homeState.data = res.records;
        homeState.results = res.results;

        if (homeState.data) {
            homeState.data = homeState.data.map((item) => {
                item.textType = leave_types[item.sub_type];
                return item;
            });
        }
        loading(false);
    });

};


export const temp = reactive({
  form:null,
})