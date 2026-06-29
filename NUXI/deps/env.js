// Build the integration base URL from the .env file.
//
// In dev (`yarn dev`), falling back to the local Laravel server makes
// `yarn dev` work out of the box without having to set anything. In
// production builds, the value must come from .env.
//
// Override per-environment by setting VITE_INTEGRATION_BASE_URL in
// the .env file, e.g.:
//   VITE_INTEGRATION_BASE_URL=https://accounting-dev.wismaatlet.id
//
// (Vite auto-exposes only `VITE_*` vars to `import.meta.env`; the
// `NUXT_PUBLIC_*` namespace is for `useRuntimeConfig()` only.)
const configuredBase = (import.meta.env.VITE_INTEGRATION_BASE_URL || "").trim().replace(/\/$/, "");
const defaultLocalBase =
    import.meta.env.DEV && !configuredBase ? "http://localhost:8000" : "";
const base = configuredBase || defaultLocalBase;

export const INTEGRATION_BASE_URL = base;
export const APIURL = base ? `${base}/api` : "/api";
export const ASSETURL = base ? `${base}/` : "/";
