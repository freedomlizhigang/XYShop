<template>
  <div>
    <h1> 退出页面 </h1>
    {{logins}}
    
    <router-link :to="{name: 'Login'}">
          登陆
    </router-link>
  </div>
</template>

<script>
import store from '.././vuex/store'
import { LOGOUT } from '.././vuex/mutation_types'
export default {
  computed: {
    logins() {
      return store.getters.get_user_id != 0 ? '登陆成功' : '未登陆'
    }
  },
  store,
  created: function(){
    store.commit(LOGOUT)
  }
}
</script>