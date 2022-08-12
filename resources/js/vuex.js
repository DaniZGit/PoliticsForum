import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";

const store = createStore({
    plugins: [createPersistedState()],
    state() {
      return {
        user: {
            token: null,
            is_authenticated: false,
            data: null,
        }
      }
    },
    getters: {
        user: (state) => {
            return state.user
        }
    },
    mutations: {
        user(state, user) {
            state.user.token = user.token
            state.user.is_authenticated = user.is_authenticated
            state.user.data = user.data
        }
    },
    actions: {
        user(context, user) {
            context.commit('user', user)
        }
    }
})

export default store