export const apilist = {
  profile: {
      url:"/v1/records/users",
      auth:true,
      method:'GET'
  },
  faq: {
      url:"/v1/records/faq_categories",
      auth:true,
      method:'GET'
  },
  get_forms: {
      url:"/v1/records/forms",
      auth:true,
      method:'GET'
  },
  upload: {
      url:"/v3/upload",
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
      url:"/v1/records/forms",
      auth:true,
      method:'POST'
  },
  update_form: {
      url:"/v1/records/forms",
      auth:true,
      method:'PUT'
  },
  get_loans: {
      url:"/v1/records/loans",
      auth:true,
      method:'GET'
  },
  set_loan: {
      url:"/v1/records/loans",
      auth:true,
      method:'POST'
  },
  update_loan: {
      url:"/v1/records/loans",
      auth:true,
      method:'PUT'
  },
  post_manual: {
    url:"/v1/records/dinas_clocks",
    auth:true,
    method:'POST'
  },
  get_dinas_clocks: {
    url:"/v1/records/dinas_clocks",
    auth:true,
    method:'GET'
  },
  update_dinas_clocks: {
    url:"/v1/records/dinas_clocks",
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