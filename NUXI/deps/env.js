// export const APIURL = 'http://localhost:8989/api';
// export const ASSETURL = 'http://localhost:8989/';

// export const APIURL = 'https://permithub.pelangiservice.com/api';
// export const ASSETURL = 'https://permithub.pelangiservice.com/';

const configuredBase = (import.meta.env.VITE_INTEGRATION_BASE_URL || "").trim().replace(/\/$/, "");
const defaultLocalBase =
    import.meta.env.DEV && !configuredBase ? "http://localhost:8000" : "";
const base = configuredBase || defaultLocalBase;

export const INTEGRATION_BASE_URL = base;
export const APIURL = base ? `${base}/api` : "/api";
export const ASSETURL = base ? `${base}/` : "/";
