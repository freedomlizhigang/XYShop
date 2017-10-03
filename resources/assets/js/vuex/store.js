import Vue from 'vue'
import Vuex from 'vuex'

// 这个就是我们后续会用到的counter 状态．
import counter from './modules/counter'
import user from './modules/user'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
      counter,                // 所有要管理的module, 都要列在这里.
      user
    },
    strict: debug,
    middlewares: []
})