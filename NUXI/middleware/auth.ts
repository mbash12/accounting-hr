import { getCurrentUser } from "vuefire"
export default defineNuxtRouteMiddleware(async (to, from) => {
  const user = await getCurrentUser()
  if (!user) {
    return navigateTo({
      path: '/auth',
      query: {
        redirect: to.fullPath,
      },
    })
  }
})