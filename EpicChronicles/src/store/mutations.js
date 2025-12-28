export default {
    setUser(state, userData) {
        console.log('🔧 MUTATION: setUser called')
        console.log('🔧 userData received:', userData)
        console.log('🔧 userData.customer:', userData?.customer)
        console.log('🔧 tokens:', userData?.customer?.total_tokens)
        
        state.user.data = userData
        
        console.log('🔧 State after mutation:', state.user.data)
        console.log('🔧 State customer after:', state.user.data?.customer)
    },

    setToken(state, token) {
        console.log('🔧 MUTATION: setToken called with:', token)
        
        state.user.token = token
        if (token) {
            sessionStorage.setItem("TOKEN", token)
        } else {
            sessionStorage.removeItem("TOKEN")
        }
        
        console.log('🔧 Token in state:', state.user.token)
    },

    logout(state) {
        console.log('🔧 MUTATION: logout called')
        
        state.user.data = {}
        state.user.token = null
        sessionStorage.removeItem("TOKEN")
        
        console.log('🔧 State cleared')
    },
}