export const useRegistration = () => useState("registration", () => "false");
export const usePropsEditData = () => useState("edit", () => {});
export const useUserData = () => useState("data", () => ({ data: { user_id: '', name: '', email: '', password: '' } }));
