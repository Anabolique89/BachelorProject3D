import axiosClient from "../axios";

export default {
    async login({ commit }, user) {
    return axiosClient.post("/login", user).then(({ data }) => {
        commit("setUser", data.user);
        commit("setToken", data.token);
        
        return data;
    });
},

    async logout({ commit }) {
        try {
            await axiosClient.post("/logout");
        } catch (error) {
            console.error("Logout error:", error);
        } finally {
            commit("logout");
        }
    },

    async getCurrentUser({ commit }) {
    try {
        const response = await axiosClient.get('/me');
        console.log('getCurrentUser response:', response.data);
        
        // The data is nested inside response.data.data because of UserResourcee
        if (response.data.data) {
            commit('setUser', response.data.data);
        } else {
            commit('setUser', response.data);
        }
        
        return response;
    } catch (error) {
        console.error('getCurrentUser error:', error);
        commit('logout');
        throw error;
    }
},

    async getDashboardStats({ state }) {
        try {
            const response = await axiosClient.get("/admin/dashboard/stats");
            return response.data;
        } catch (error) {
            console.error("Failed to fetch stats:", error);
            throw error;
        }
    },

     /**
     * Get all products with filters
     */
    async getProducts({ commit }, params = {}) {
        try {
            const response = await axiosClient.get('/products', { params });
            return response.data;
        } catch (error) {
            console.error('Failed to fetch products:', error);
            throw error;
        }
    },
    

    /**
     * Get single product by slug
     */
    async getProduct({ commit }, slug) {
        try {
            const response = await axiosClient.get(`/products/${slug}`);
            return response.data.data;
        } catch (error) {
            console.error('Failed to fetch product:', error);
            throw error;
        }
    },
    
    /**
     * Create new product (Admin only)
     */
    async createProduct({ commit }, productData) {
        try {
            const response = await axiosClient.post('/admin/products', productData);
            return response.data.data;
        } catch (error) {
            console.error('Failed to create product:', error);
            throw error;
        }
    },
    
    /**
     * Update product (Admin only)
     */
    async updateProduct({ commit }, { id, data }) {
        try {
            const response = await axiosClient.put(`/admin/products/${id}`, data);
            return response.data.data;
        } catch (error) {
            console.error('Failed to update product:', error);
            throw error;
        }
    },
    
    /**
     * Delete product (Admin only)
     */
    async deleteProduct({ commit }, id) {
        try {
            const response = await axiosClient.delete(`/admin/products/${id}`);
            return response.data;
        } catch (error) {
            console.error('Failed to delete product:', error);
            throw error;
        }
    },
};


