export default {
    setUser(state, userData) {
        state.user.data = userData;
    },

    setToken(state, token) {
        state.user.token = token;
        if (token) {
            sessionStorage.setItem("TOKEN", token);
        } else {
            sessionStorage.removeItem("TOKEN");
        }
    },

    logout(state) {
        state.user.data = {};
        state.user.token = null;
        sessionStorage.removeItem("TOKEN");
    },
};
