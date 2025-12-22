import { createStore } from "vuex";
import actions from "./actions";
import mutations from "./mutations";
import state from "./state.js";
import cart from "./modules/cart";

const store = createStore({
    state,

    getters: {
        isAuthenticated: (state) => !!state.user.token,
        isAdmin: (state) => {
            const role = state.user.data?.customer?.role;
            return role === "admin" || role === "super_admin";
        },
        user: (state) => state.user.data,
    },

    actions,
    mutations,
    modules: {
        cart,
    },
});

export default store;
