import { createStore} from "vuex";
import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/admin';

const store = createStore({
    state:{
        user:null,
        isAuthenticated:false
    },
    mutations:{
        SET_USER(state, user) {
            state.user = user
            state.isAuthenticated = !!user
        },
        LOGOUT(state) {
            state.user = null
            state.isAuthenticated = false
        }
    },
    actions:{
        async checkAuth({commit}){
            try {
                const response = await axios.get('/dashboard')
                commit('SET_USER',response.data.user)
                return true
            } catch (error) {
                commit('LOGOUT')
                return false
            }
        },
        async logout({ commit }) {
            await axios.post('/logout')
            commit('LOGOUT')
        }
    },
    getters:{
        isAuthenticated: state => state.isAuthenticated,
        user: state => state.user
    }
})

export default store;
