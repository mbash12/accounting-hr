import { reactive } from "vue";
import { apilist } from "./apilist";
import { APIURL } from "./env";
import { getTokens } from "./firebase.js";

const AUTH_STORAGE_KEY = "nuxi_auth_token";

export const currentUser = reactive({
    auth: null,
    user: null,
    loggedin: false,
});

const normalizeAttendanceSpots = (employee = {}) => {
    const spots = Array.isArray(employee?.attendance_spots) ? employee.attendance_spots : [];
    if (spots.length) {
        return spots
            .map((spot) => ({
                id: spot?.id ?? null,
                name: spot?.name ?? "Spot",
                latitude: Number(spot?.latitude),
                longitude: Number(spot?.longitude),
                radius_meters: Number(spot?.radius_meters),
            }))
            .filter(
                (spot) =>
                    Number.isFinite(spot.latitude) &&
                    Number.isFinite(spot.longitude) &&
                    Number.isFinite(spot.radius_meters) &&
                    spot.radius_meters > 0
            );
    }

    const legacy = employee?.attendance_location;
    if (
        legacy &&
        Number.isFinite(Number(legacy?.latitude)) &&
        Number.isFinite(Number(legacy?.longitude)) &&
        Number.isFinite(Number(legacy?.radius_meters)) &&
        Number(legacy?.radius_meters) > 0
    ) {
        return [
            {
                id: null,
                name: "Default Spot",
                latitude: Number(legacy.latitude),
                longitude: Number(legacy.longitude),
                radius_meters: Number(legacy.radius_meters),
            },
        ];
    }

    return [];
};

const normalizeEmployeePayload = (employee = {}) => {
    return {
        ...employee,
        attendance_spots: normalizeAttendanceSpots(employee),
    };
};
export const store = reactive({
    isLoading: false,
    installed: false,
});
export const loading = (state = true) => {
    store.isLoading = state;
};
const getAuthToken = () => {
    if (typeof window === "undefined") return null;
    return localStorage.getItem(AUTH_STORAGE_KEY);
};
const setAuthToken = (token) => {
    if (typeof window === "undefined") return;
    if (token) {
        localStorage.setItem(AUTH_STORAGE_KEY, token);
    } else {
        localStorage.removeItem(AUTH_STORAGE_KEY);
    }
};
export const checkLoggedin = async () => {
    const token = getAuthToken();
    if (!token) {
        currentUser.auth = null;
        currentUser.user = null;
        currentUser.loggedin = false;
        return currentUser;
    }

    currentUser.auth = { accessToken: token };
    const profile = await api("auth_me");
    if (!profile || profile?.message === "Unauthorized") {
        setAuthToken(null);
        currentUser.auth = null;
        currentUser.user = null;
        currentUser.loggedin = false;
        return currentUser;
    }
    currentUser.user = normalizeEmployeePayload(profile?.employee ?? {});
    currentUser.loggedin = true;
    return currentUser;
};
export const logout = () => {
    api("auth_logout").catch(() => null);
    setAuthToken(null);
    currentUser.auth = null;
    currentUser.user = null;
    currentUser.loggedin = false;
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
        const token = getAuthToken() ?? currentUser.auth?.accessToken;
        if (token) {
            therest.headers["Authorization"] = `Bearer ${token}`;
        }
    }
    if (options.body instanceof FormData) {
        therest.body = options.body;
    } else if (options.body instanceof File) {
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

        if (!response.ok) {
            throw { status: response.status, data: result };
        }

        if (
            result.code === 1012 ||
            result.title === "Unauthorized" ||
            result.message === "Unauthorized"
        ) {
            logout();
            setTimeout(() => {
                location.reload();
            },1000)
            return;
        }

        return result;
    } catch (error) {
        if (error?.response) {
            try {
                const response = await error.response.text();
                if (response.includes("<html")) {
                    logout();
                    location.reload();
                }
            } catch (_) {}
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
export const loginWithPassword = async (email, password) => {
    const result = await api("auth_login", {
        body: { email, password },
    });

    if (!result?.token || !result?.employee) {
        return { ok: false, data: result };
    }

    setAuthToken(result.token);
    currentUser.auth = { accessToken: result.token };
    currentUser.user = normalizeEmployeePayload(result.employee ?? {});
    currentUser.loggedin = true;

    return { ok: true, data: result };
};
export const getFaqData = async () => {
    return await api("faq", { params: { join: "faqs" } });
};
export const uploadImage = async (file) => {
    return await api("upload", { body: file });
};

const mapOvertimeStatusToAccounting = (status) => {
    if (status === "submitted") return "draft";
    return status;
};

const mapOvertimeStatusToApp = (status) => {
    if (status === "draft") return "submitted";
    return status;
};

const mapAccountingOvertimeToApp = (overtime) => {
    return {
        id: overtime.id,
        employee_id: overtime.employee_id,
        date: overtime.date,
        hours: Number(overtime.hours ?? 0),
        is_holiday: Boolean(overtime.is_holiday),
        calculated_amount: Number(overtime.calculated_amount ?? 0),
        status: mapOvertimeStatusToApp(overtime.status),
        reason: overtime.reason ?? null,
        created_at: overtime.created_at,
        updated_at: overtime.updated_at,
    };
};

const mapAppOvertimeToAccounting = (payload) => {
    return {
        date: payload.date,
        hours: Number(payload.hours ?? payload.duration ?? 0),
        is_holiday: Boolean(payload.is_holiday ?? false),
        reason: payload.reason ?? payload.description ?? null,
        status: mapOvertimeStatusToAccounting(payload.status ?? "submitted"),
    };
};

export const getOvertimes = async (params) => {
    const mappedParams = {
        ...params,
        status: mapOvertimeStatusToAccounting(params?.status),
    };
    const result = await api("get_overtimes", { params: mappedParams });
    return {
        ...result,
        records: (result?.records ?? []).map(mapAccountingOvertimeToApp),
    };
};

export const getSingleOvertime = async (id) => {
    const result = await api("get_overtimes", { route: id });
    return mapAccountingOvertimeToApp(result);
};

export const submitOvertime = async (data) => {
    return await api("set_overtime", { body: mapAppOvertimeToAccounting(data) });
};

export const updateOvertime = async (id, data) => {
    return await api("update_overtime", {
        body: mapAppOvertimeToAccounting(data),
        route: id,
    });
};

export const getLeaveQuota = async (user_id) => {
    return {
        id: null,
        user_id,
        quota: 12,
        taken: 0,
        balance: 12,
    };
};
export const getNationalHolidays = async () => {
    try {
        const result = await api("get_holidays");
        return (result?.records ?? []).map((h) => ({
            date: h.date ?? h.holiday_date,
            name: h.name ?? h.description,
        }));
    } catch (_error) {
        return [];
    }
};

const mapStatusToAccounting = (status) => {
    if (status === "submitted") return "pending";
    return status;
};

const mapStatusToApp = (status) => {
    if (status === "pending") return "submitted";
    return status;
};

const normalizeStoragePath = (path) => {
    if (!path || typeof path !== "string") return path;
    return path.replace(/^\/?storage\//, "");
};

const mapAccountingPermitToApp = (permit) => {
    const start = permit.start ?? `${permit.start_date} 00:00:00`;
    const end = permit.end ?? `${permit.end_date} 00:00:00`;

    return {
        id: permit.id,
        type: "absence",
        sub_type: permit.type ?? "others",
        status: mapStatusToApp(permit.status),
        description: permit.reason ?? null,
        reason: permit.reason ?? null,
        duration: Number(permit.duration ?? 1),
        duration_um: "days",
        start,
        end,
        attachment: permit.attachment_path,
        user_id: {
            id: permit.employee_id,
            fullname: currentUser.user?.fullname,
        },
        created_at: permit.created_at,
    };
};

const mapAppPermitToAccounting = (payload) => {
    return {
        type: payload.sub_type ?? payload.type ?? "others",
        start_date: (payload.start || "").slice(0, 10),
        end_date: (payload.end || payload.start || "").slice(0, 10),
        reason: payload.description ?? payload.reason ?? null,
        attachment_path: normalizeStoragePath(payload.attachment) ?? null,
        status: mapStatusToAccounting(payload.status ?? "submitted"),
    };
};

const mapAttendanceStatusToAccounting = (status) => {
    if (!status || status === "submitted" || status === "approved") return "present";
    if (status === "rejected") return "absent";
    return status;
};

const mapAttendanceStatusToApp = (status) => {
    if (status === "present" || status === "late" || status === "permit" || status === "leave") {
        return "approved";
    }
    if (status === "absent") return "rejected";
    return "submitted";
};

const mapAccountingAttendanceToApp = (attendance) => {
    const isIn = Boolean(attendance.check_in);
    const datetime = attendance.check_in ?? attendance.check_out;

    return {
        id: attendance.id,
        type: isIn ? "in" : "out",
        datetime,
        date: attendance.date,
        note: isIn
            ? (attendance.notes_in ?? attendance.notes)
            : (attendance.notes_out ?? attendance.notes),
        status: mapAttendanceStatusToApp(attendance.status),
        attachment: isIn ? attendance.photo_in_path : attendance.photo_out_path,
        location: JSON.stringify({
            latitude: isIn ? attendance.lat_in : attendance.lat_out,
            longitude: isIn ? attendance.lng_in : attendance.lng_out,
        }),
        user_id: {
            id: attendance.employee_id,
            fullname: currentUser.user?.fullname,
        },
    };
};

const mapAccountingAttendanceToAppEntries = (attendance) => {
    const baseUser = {
        id: attendance.employee_id,
        fullname: currentUser.user?.fullname,
    };
    const entries = [];

    if (attendance.check_in) {
        entries.push({
            id: attendance.id,
            type: "in",
            datetime: attendance.check_in,
            date: attendance.date,
            note: attendance.notes_in ?? attendance.notes,
            status: mapAttendanceStatusToApp(attendance.status),
            attachment: attendance.photo_in_path,
            location: JSON.stringify({
                latitude: attendance.lat_in,
                longitude: attendance.lng_in,
            }),
            user_id: baseUser,
        });
    }

    if (attendance.check_out) {
        entries.push({
            id: attendance.id,
            type: "out",
            datetime: attendance.check_out,
            date: attendance.date,
            note: attendance.notes_out ?? attendance.notes,
            status: mapAttendanceStatusToApp(attendance.status),
            attachment: attendance.photo_out_path,
            location: JSON.stringify({
                latitude: attendance.lat_out,
                longitude: attendance.lng_out,
            }),
            user_id: baseUser,
        });
    }

    if (!entries.length) {
        entries.push(mapAccountingAttendanceToApp(attendance));
    }

    return entries;
};

const mapAppAttendanceToAccounting = (payload) => {
    const isIn = payload.type === "in";
    let location = {
        latitude: payload.latitude,
        longitude: payload.longitude,
    };
    if (payload.location) {
        if (typeof payload.location === "string") {
            try {
                location = JSON.parse(payload.location);
            } catch (_error) {
                location = {
                    latitude: payload.latitude,
                    longitude: payload.longitude,
                };
            }
        } else {
            location = payload.location;
        }
    }

    return {
        date: (payload.date || payload.datetime || "").slice(0, 10),
        check_in: isIn ? payload.datetime : null,
        check_out: isIn ? null : payload.datetime,
        lat_in: isIn ? location?.latitude ?? null : null,
        lng_in: isIn ? location?.longitude ?? null : null,
        lat_out: isIn ? null : location?.latitude ?? null,
        lng_out: isIn ? null : location?.longitude ?? null,
        status: mapAttendanceStatusToAccounting(payload.status),
        photo_in_path: isIn ? normalizeStoragePath(payload.attachment) ?? null : null,
        photo_out_path: isIn ? null : normalizeStoragePath(payload.attachment) ?? null,
        notes_in: isIn ? (payload.note ?? null) : null,
        notes_out: isIn ? null : (payload.note ?? null),
        notes: payload.note ?? null,
    };
};

export const submitForm = async (data) => {
    return await api("set_form", { body: mapAppPermitToAccounting(data) });
};

export const getForms = async (params) => {
    const mappedParams = {
        ...params,
        status: mapStatusToAccounting(params?.status),
    };
    const result = await api("get_forms", { params: mappedParams });
    return {
        ...result,
        records: (result?.records ?? []).map(mapAccountingPermitToApp),
    };
};
export const getDinas = async (params) => {
    const result = await api("get_dinas_clocks", { params });
    return {
        ...result,
        records: (result?.records ?? []).flatMap(mapAccountingAttendanceToAppEntries),
    };
};
export const getSingleForm = async (id) => {
    const result = await api("get_forms", { route: id });
    return mapAccountingPermitToApp(result);
};
export const getSingleDinas = async (id) => {
    const result = await api("get_dinas_clocks", { route: id });
    return mapAccountingAttendanceToApp(result);
};

export const updateForm = async (id, data) => {
    return await api("update_form", {
        body: mapAppPermitToAccounting(data),
        route: id,
    });
};
export const updateDinas = async (id, data) => {
    return await api("update_dinas_clocks", {
        body: mapAppAttendanceToAccounting(data),
        route: id,
    });
};

export const submitManual = async (data) => {
    return await api("post_manual", { body: mapAppAttendanceToAccounting(data) });
};
export const submitManualWithPhoto = async (data, file) => {
    const mapped = mapAppAttendanceToAccounting(data);
    const formData = new FormData();
    Object.entries(mapped).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== "") {
            formData.append(key, String(value));
        }
    });
    if (file) {
        formData.append("photo", file);
    }
    return await api("post_manual", { body: formData });
};
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
