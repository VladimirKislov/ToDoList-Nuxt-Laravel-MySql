export default defineNuxtRouteMiddleware((to, from) => {
  const isRegistration = useRegistration();

  if (to.path === "/" && isRegistration.value === "false") {
    return navigateTo("/login");
  }
});
