import { reactive } from "vue";
import { apilist } from "./apilist";
import { APIURL } from "./env";
import { signOut } from "firebase/auth";
import { getCurrentUser } from "vuefire";
import { getTokens } from "./firebase.js";


export const currentUser = reactive({
    auth: null,
    user: null,
    loggedin: false,
});
export const store = reactive({
    isLoading: false,
    installed: false,
});
export const loading = (state = true) => {
    store.isLoading = state;
};
export const checkLoggedin = async () => {
    const auth = await getCurrentUser();
    if (!auth) return currentUser;
    currentUser.auth = auth;
    const user = await getUserData(currentUser.auth?.email);
    if (!user || user.code || user?.error) {
        currentUser.auth = null;
        return currentUser;
    }
    currentUser.user = user?.records[0];
    currentUser.loggedin = true;
    return currentUser;
};
export const logout = () => {
    signOut(currentUser.auth?.auth);
    setTimeout(() => {
        currentUser.auth = null;
        currentUser.user = null;
        currentUser.loggedin = false;
    }, 1000);
    return;
};
export const api = async (key, options = {}) => {
    const api_bp = apilist[key];
    const therest = {};
    let params = "";
    let route = "";
    therest.headers = {};

    if (options.params) {
        params = Object.entries(options.params)
            .map(([key, value]) => {
                const paramValue = Array.isArray(value)
                    ? value.map((v) => `${key}[]=${v}`).join("&")
                    : `${key}=${value}`;
                return paramValue;
            })
            .join("&");
        params = params ? `?${params}` : "";
    }

    if (api_bp.auth) {
        const authData = currentUser.auth;
        therest.headers["X-JWT"] = `Bearer ${authData.accessToken}`;
    }

    therest.headers["X-API-KEY"] = "GDb5Yd5P2t2qEXj5jx4R6XEy";
    if (options.body instanceof File) {
        const formData = new FormData();
        const uploadName = options.upload ?? "file";
        formData.append(uploadName, options.body);
        therest.body = formData;
    } else if (options.body) {
        therest.headers["Content-Type"] = "application/json";
        therest.body = JSON.stringify(options.body);
    }

    if (options.route) {
        route = `/${options.route}`;
    }

    try {
        const response = await fetch(`${APIURL}${api_bp.url}${route}${params}`, {
            method: api_bp.method,
            ...therest,
        });

        const result = await response.json();
        if (result.code === 1012 || result.title === "Unauthorized") {
            logout();
            setTimeout(() => {
                location.reload();
            },1000)
            return;
        }

        return result;
    } catch (error) {
        const response = await error.response.text();
        if (response.includes("<html")) {
            logout();
            location.reload();
        }
        throw error;
    }
};
export const datetodate = (date, time = false) => {
    const d = new Date(date);
    const y = d.getFullYear();
    const m = (d.getMonth() + 1).toString().padStart(2, "0");
    const dt = d.getDate().toString().padStart(2, "0");
    const hh = d.getHours().toString().padStart(2, "0");
    const mm = d.getMinutes().toString().padStart(2, "0");
    const ss = d.getSeconds().toString().padStart(2, "0");
    if (time) {
        return { hours: hh, minutes: mm, seconds: ss };
    } else {
        return `${y}-${m}-${dt} ${hh}:${mm}:${ss}`;
    }
};
const getUserData = async (email) => {
    return await api("profile", {
        params: { join: "departments", filter: `email,eq,${email}` },
    });
};
export const getFaqData = async () => {
    return await api("faq", { params: { join: "faqs" } });
};
export const uploadImage = async (file) => {
    return await api("upload", { body: file });
};

export const getLeaveQuota = async (user_id) => {
    const joinDate = new Date(currentUser.user?.join_date); // Parse the join date string
    const eligible =
        joinDate <=
        new Date(new Date().setFullYear(new Date().getFullYear() - 1));
    if (eligible) {
        const quota = await api("get_quota", {
            route: `?filter=year,eq,${new Date().getFullYear()}&filter=user_id,eq,${user_id}`,
        });
        if (quota?.records.length) {
            return quota.records[0];
        } else if (eligible) {
            const joinDate = new Date(currentUser.user?.join_date);
            const currentDate = new Date();
            const endOfYear = new Date(currentDate.getFullYear(), 11, 31);
            const monthsDifference =
                (endOfYear -
                    new Date(
                        joinDate.getFullYear() + 1,
                        joinDate.getMonth(),
                        joinDate.getDate()
                    )) /
                (1000 * 60 * 60 * 24 * 30);
            const leaveQuota = Math.min(Math.floor(monthsDifference), 12);
            await api("set_quota", {
                body: {
                    user_id: user_id,
                    year: new Date().getFullYear(),
                    quota: leaveQuota,
                    taken: 0,
                    balance: leaveQuota,
                },
            });
            const quota = await api("get_quota", {
                params: { user_id: user_id, year: new Date().getFullYear() },
            });
            return quota.records[0];
        }
    } else {
        return null;
    }
};
export const getNationalHolidays = async () => {
    const holidays = await api("get_holidays", {
        params: { year: new Date().getFullYear(), limit: 100 },
    });
    if (holidays?.records.length) {
        return holidays.records;
    } else {
        const settings = await api("get_settings", { route: "1" });
        const _holidays = await fetch(settings.holiday_source).then(
            (response) => response.json()
        );
        if (_holidays) {
            const hld = _holidays
                .filter((holiday) => holiday.is_national_holiday)
                .filter((holiday) => {
                    const date = new Date(holiday.holiday_date);
                    const day = date.getDay();
                    return day !== 0 && day !== 6;
                })
                .map((holiday) => {
                    return {
                        year: holiday.holiday_date.split("-")[0],
                        description: holiday.holiday_name,
                        date: holiday.holiday_date,
                    };
                });
            await api("set_holidays", {
                body: hld,
            });
            const holidays = await api("get_holidays", {
                route: `?filter=year,eq,${new Date().getFullYear()}`,
            });
            return holidays.records;
        }
        return [];
    }
};

export const submitForm = async (data) => {
    return await api("set_form", { body: data });
};

export const getForms = async (params) => {
    return await api("get_forms", { params: params });
};
export const getDinas = async (params) => {
    return await api("get_dinas_clocks", { params: params });
};
export const getSingleForm = async (id) => {
    return await api("get_forms", { route: id, params: { join: "users" } });
};
export const getSingleDinas = async (id) => {
    return await api("get_dinas_clocks", { route: id, params: { join: "users" } });
};

export const updateForm = async (id, data) => {
    return await api("update_form", { body: data, route: id });
};
export const updateDinas = async (id, data) => {
    return await api("update_dinas_clocks", { body: data, route: id });
};

export const submitManual = async (data) => {
    return await api("post_manual", { body: data });
};

export const submitHutang = async (data) => {
    return await api("post_loan", { body: data });
};


export const getLoans = async (params) => {
    return await api("get_loans", { params: params });
}

export const getSingleLoan = async (id) => {
    return await api("get_loans", { route: id, params: { join: "users" } });
}

export const setLoan = async (data) => {
    return await api("set_loan", { body: data });
}

export const updateLoan = async (id, data) => {
    return await api("update_loan", { body: data, route: id });
}


export const setToken = async (user_id, token) => {
    return await api("set_token", { body: { user_id: user_id, token: token } });
};

export const deleteToken = async (id) => {
    const token = await api("get_tokens", {
        route: `?filter=user_id,eq,${id}`,
    });
    if (token?.records?.length) {
        token.records.forEach(async (token) => {
            await api("delete_token", { route: token.id });
        });
    }
    return true;
};

export const getMyTokens = async (id) => {
    return await api("get_tokens", { route: `?filter=user_id,eq,${id}` });
};

export const setNotif = async (data) => {
    return await api("set_notif", { body: data });
};

export const getNotif = async (id, page, type) => {
    return await api("get_notif", {
        route: `?filter=user_id,eq,${id}&filter=notif_type,eq,${type}&order=created_at,desc&page=${page}`,
    });
};

export const readNotif = async (ids) => {
    return await api("update_notif", {
        body: {
            readed_at: new Date()
                .toISOString()
                .replace("T", " ")
                .replace("Z", ""),
        },
        route: ids.join(","),
    });
};

export const notify = async (data) => {
    return await api("notify", { body: data });
};

export const grant = async () => {
    if(!currentUser.user?.id) return
    let token = null;
    if ("Notification" in window) {
        let permission = window.Notification.permission;
        if (permission === "granted") {
            let result = await getTokens();
            if (result) {
                const tokens = await getMyTokens(currentUser.user?.id);
                if (tokens.records?.[0]?.token == result) {
                    token = result;
                } else {
                    deleteToken(currentUser.user?.id);
                    setToken(currentUser.user?.id, result);
                    token = result;
                }
            }
        } else {
            let permission = await window.Notification.requestPermission();
            if (permission === "granted") {
                let result = await getTokens();
                token = result;
                deleteToken(currentUser.user?.id);
                setToken(currentUser.user?.id, token);
            }
        }
    } else {
        console.log("Notification not supported");
    }
};
