<template>
  <div>
    <h1> 登陆页面 </h1>
    {{logins}}
    <br />
    <input type='button' @click='login' value='登陆'/><br/>
    <input type='button' @click='logout' value='退出登陆'/><br/>
    <router-link :to="{name: 'Logout'}">
          退出登陆
    </router-link>
  </div>
</template>

<script>
import store from '.././vuex/store'
import { LOGOUT,LOGIN } from '.././vuex/mutation_types'
export default {
  computed: {
    logins() {
      return store.getters.get_user_id != 0 ? '登陆成功' : '未登陆'
    }
  },
  store,
  methods: {
    logout() {
      store.commit(LOGOUT)
    },
    login() {
      store.commit(LOGIN,userinfo)
    }
  }
}
</script>