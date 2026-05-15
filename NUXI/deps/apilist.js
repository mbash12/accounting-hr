export const apilist = {
  auth_login: {
      url:"/employeeapi/auth/login",
      auth:false,
      method:'POST'
  },
  auth_me: {
      url:"/employeeapi/auth/me",
      auth:true,
      method:'GET'
  },
  auth_logout: {
      url:"/employeeapi/auth/logout",
      auth:true,
      method:'POST'
  },
  profile: {
      url:"/v1/records/users",
      auth:true,
      method:'GET'
  },
  faq: {
      url:"/employeeapi/faqs",
      auth:true,
      method:'GET'
  },
  get_forms: {
      url:"/employeeapi/permits",
      auth:true,
      method:'GET'
  },
  upload: {
      url:"/employeeapi/upload",
      auth:true,
      method:'POST'
  },
  get_quota: {
      url:"/v1/records/leave_quotas",
      auth:true,
      method:'GET'
  },
  set_quota: {
      url:"/v1/records/leave_quotas",
      auth:true,
      method:'POST'
  },
  update_quota: {
      url:"/v1/records/leave_quotas",
      auth:true,
      method:'PUT'
  },
  get_holidays: {
      url:"/v1/records/national_holidays",
      auth:true,
      method:'GET'
  },
  set_holidays: {
      url:"/v1/records/national_holidays",
      auth:true,
      method:'POST'
  },
  get_settings: {
      url:"/v1/records/settings",
      auth:true,
      method:'GET'
  },
  set_form: {
      url:"/employeeapi/permits",
      auth:true,
      method:'POST'
  },
  update_form: {
      url:"/employeeapi/permits",
      auth:true,
      method:'PUT'
  },
  get_overtimes: {
      url:"/employeeapi/overtimes",
      auth:true,
      method:'GET'
  },
  set_overtime: {
      url:"/employeeapi/overtimes",
      auth:true,
      method:'POST'
  },
  update_overtime: {
      url:"/employeeapi/overtimes",
      auth:true,
      method:'PUT'
  },
  post_manual: {
    url:"/employeeapi/attendances",
    auth:true,
    method:'POST'
  },
  get_dinas_clocks: {
    url:"/employeeapi/attendances",
    auth:true,
    method:'GET'
  },
  update_dinas_clocks: {
    url:"/employeeapi/attendances",
    auth:true,
    method:'PUT'
  },
  set_token: {
    url:"/v1/records/notif_tokens",
    auth:true,
    method:'POST'
  },
  delete_token: {
    url:"/v1/records/notif_tokens",
    auth:true,
    method:'DELETE'
  },
  get_tokens: {
    url:"/v1/records/notif_tokens",
    auth:true,
    method:'GET'
  },
  get_notif: {
    url:"/v1/records/notifications",
    auth:true,
    method:'GET'
  },
  set_notif: {
    url:"/v1/records/notifications",
    auth:true,
    method:'POST'
  },
  update_notif: {
    url:"/v1/records/notifications",
    auth:true,
    method:'PUT'
  },
  notify: {
    url:"/v3/notify",
    auth:true,
    method:'POST'
  }
  
}