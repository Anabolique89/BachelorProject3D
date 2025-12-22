import axios from "axios";
import store from "./store";
import router from "./router";

const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
});

// Request interceptor - Add token to every request
axiosClient.interceptors.request.use((config) => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`;
    return config;
});

// Response interceptor - Handle 401 errors
axiosClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            sessionStorage.removeItem("TOKEN");
            router.push({ name: "login" });
        }
        throw error;
    }
);

export default axiosClient;
