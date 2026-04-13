import { reactive } from "vue";
import { currentUser, getDinas, getForms, getLoans, loading } from "@/deps/service.js";
import dayjs from "dayjs";
export const leave_types = {
    annual: "Cuti Tahunan",
    overtime: "Lembur",
    wfh: "Kerja Dari Rumah",
    holiday: "Kerja Hari Libur",
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
    late: "Izin Masuk Telat",
    early: "Izin Pulang Cepat",
    nodn_sick: "Sakit Tanpa Surat",
    sick: "Sakit Dengan Surat",
    halfday: "Izin Setengah Hari",
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
    if (homeState.tab == 0) {
        const params = {
            page: homeState.page,
            join: "users",
            order: "id,desc",
        };
        let filter  = homeState.appliedFilter;
        if(filter=='rejected'){
            params.page += `&filter1=status,eq,${filter}`;
            params.page += `&filter2=status,eq,cancelled`;    
            params.page += `&filter3=status,eq,updating`;    
            params.page += `&filter1=user_id,eq,${currentUser.user?.id}`;
            params.page += `&filter2=user_id,eq,${currentUser.user?.id}`;
            params.page += `&filter3=user_id,eq,${currentUser.user?.id}`;
        }else{
            params.page += `&filter=status,eq,${filter}`;
            params.page += `&filter=user_id,eq,${currentUser.user?.id}`;
        }

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
    }
    if (homeState.tab == 1) {
        const params = {
            page: homeState.page,
            join: "users",
        };
        if (homeState.dates) {
            const start = dayjs(homeState.dates[0]).startOf('day').format('YYYY-MM-DD HH:mm');
            const end = dayjs(homeState.dates[1]).endOf('day').format('YYYY-MM-DD HH:mm');
            params.page += `&filter=start,le,${end}&filter=end,ge,${start}`;
        }
        if (homeState.appliedFilter == "submitted") {
            params.page += `&filter1=first_approver,eq,${currentUser.user?.id}&filter1=first_approval,is&filter1=status,eq,submitted&filter2=second_approver,eq,${currentUser.user?.id}&filter2=second_approval,is&filter2=first_approval,eq,approved&filter2=status,eq,submitted&filter2=status,eq,submitted&order=id,asc`;
        }
        if (homeState.appliedFilter == "approved") {
            params.page += `&filter1=first_approver,eq,${currentUser.user?.id}&filter1=first_approval,eq,approved&filter1=status,eq,approved&filter2=second_approver,eq,${currentUser.user?.id}&filter2=second_approval,eq,approved&filter2=status,eq,approved&order=id,desc`;
        }
        if (homeState.appliedFilter == "rejected") {
            params.page += `&filter1=first_approver,eq,${currentUser.user?.id}&filter1=first_approval,eq,rejected&filter1=status,eq,rejected&filter2=second_approver,eq,${currentUser.user?.id}&filter2=second_approval,eq,rejected&filter2=status,eq,rejected&order=id,desc`;
        }
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
    }
    if (homeState.tab == 3) {
        const params = {
            page: homeState.page,
            join: "users",
        };
        
        if (homeState.appliedFilter == "submitted") {
            params.page += `&filter1=approver,eq,${currentUser.user?.id}&filter1=status,eq,submitted&order=id,asc`;
        }
        if (homeState.appliedFilter == "approved") {
            params.page += `&filter1=approver,eq,${currentUser.user?.id}&filter1=status,eq,approved&order=id,desc`;
        }
        if (homeState.appliedFilter == "rejected") {
            params.page += `&filter1=approver,eq,${currentUser.user?.id}&filter1=status,eq,rejected&order=id,desc`;
        }
        getDinas(params).then((res) => {
            homeState.data = res.records;
            homeState.results = res.results;
            if (homeState.data) {
                homeState.data = homeState.data
            }
            loading(false);
        });
    }

    if (homeState.tab == 2) {
        const params = {
            page: homeState.page,
            join: "users",
            order: "id,desc",
        };
        
        let filter  = homeState.appliedFilter;
        if(filter=='rejected'){
            params.page += `&filter1=status,eq,${filter}`;
            params.page += `&filter2=status,eq,cancelled`;    
            params.page += `&filter3=status,eq,updating`;    
            params.page += `&filter1=user_id,eq,${currentUser.user?.id}`;
            params.page += `&filter2=user_id,eq,${currentUser.user?.id}`;
            params.page += `&filter3=user_id,eq,${currentUser.user?.id}`;
        }else{
            params.page += `&filter=status,eq,${filter}`;
            params.page += `&filter=user_id,eq,${currentUser.user?.id}`;
        }

        getLoans(params).then((res) => {
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
    }
};


export const temp = reactive({
  form:null,
})