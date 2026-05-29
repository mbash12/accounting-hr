# api
- When creating Postman collections for this project, only include HR/employee endpoints that integrate with NUXI mobile app (Auth, Permits, Attendances, FAQs, Upload), not ERP integration endpoints (master data, purchase, sales, invoice sync). Confidence: 0.65

# nuxt
- When handling 401 errors in NUXI, avoid calling logout API after redirect to prevent infinite loops - the logout function itself calls api() which may return 401. Instead, just clear local token and redirect without calling the logout endpoint. Confidence: 0.75
