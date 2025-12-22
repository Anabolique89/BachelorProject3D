import axiosClient from '../../axios';

const state = {
    items: [],
    summary: {
        subtotal: 0,
        tax: 0,
        total: 0,
        item_count: 0,
    },
    loading: false,
}

const getters = {
    cartItems: (state) => state.items,
    cartSummary: (state) => state.summary,
    cartItemCount: (state) => state.summary.item_count,
    isLoading: (state) => state.loading,
}

const actions = {
    /**
     * Fetch cart from server
     */
    async fetchCart({ commit }) {
        commit('setLoading', true)
        try {
            const response = await axiosClient.get('/cart')
            commit('setCart', response.data)
        } catch (error) {
            console.error('Failed to fetch cart:', error)
            throw error
        } finally {
            commit('setLoading', false)
        }
    },

    /**
     * Add item to cart
     */
    async addToCart({ commit, dispatch }, { product_id, quantity = 1 }) {
        try {
            await axiosClient.post('/cart/add', {
                product_id,
                quantity,
            })
            // Refresh cart after adding
            await dispatch('fetchCart')
            return true
        } catch (error) {
            console.error('Failed to add to cart:', error)
            throw error
        }
    },

    /**
     * Update cart item quantity
     */
    async updateCartItem({ commit, dispatch }, { id, quantity }) {
        try {
            await axiosClient.put(`/cart/items/${id}`, { quantity })
            // Refresh cart after updating
            await dispatch('fetchCart')
        } catch (error) {
            console.error('Failed to update cart item:', error)
            throw error
        }
    },

    /**
     * Remove item from cart
     */
    async removeFromCart({ commit, dispatch }, id) {
        try {
            await axiosClient.delete(`/cart/items/${id}`)
            // Refresh cart after removing
            await dispatch('fetchCart')
        } catch (error) {
            console.error('Failed to remove from cart:', error)
            throw error
        }
    },

    /**
     * Clear entire cart
     */
    async clearCart({ commit }) {
        try {
            await axiosClient.delete('/cart/clear')
            commit('setCart', { items: [], summary: { subtotal: 0, tax: 0, total: 0, item_count: 0 } })
        } catch (error) {
            console.error('Failed to clear cart:', error)
            throw error
        }
    },

    /**
     * Get cart count (for navbar badge)
     */
    async fetchCartCount({ commit }) {
        try {
            const response = await axiosClient.get('/cart/count')
            commit('setCartCount', response.data.count)
        } catch (error) {
            console.error('Failed to fetch cart count:', error)
        }
    },
}

const mutations = {
    setCart(state, data) {
        state.items = data.items || []
        state.summary = data.summary || { subtotal: 0, tax: 0, total: 0, item_count: 0 }
    },

    setCartCount(state, count) {
        state.summary.item_count = count
    },

    setLoading(state, loading) {
        state.loading = loading
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}