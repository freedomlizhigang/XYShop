<template>
  <div id="app">
    <my-logo title="VueJs Test Li!">
    </my-logo>

    
    <ads pos_id='1' nums='3'></ads>

    <router-link :to="{name: 'Example'}">
          示例
    </router-link>
    <router-link :to="{name: 'Login'}">
          登陆
    </router-link>
    <router-link :to="{name: 'Logout'}">
          退出
    </router-link>
    <router-link :to="{name: 'Count'}">
          计数页面
    </router-link>

    <router-view></router-view>
  </div>
</template>

<script>
  import MyLogo from './Logo'
  import Ads from './Ads'
  import store from '.././vuex/store'
  import { LOGIN } from '.././vuex/mutation_types'
  export default {
    name: 'app',
    components: {
      MyLogo,
      Ads
    },
    store,
    created(){
      // 自动登陆
      store.commit(LOGIN,userinfo)
      console.log(store.getters.get_user_name);
    }
  }
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  width: 1000px;
  margin: 0 auto;
  margin-top: 60px;
}
.tl {text-align: left;}
img {max-width: 100%; height: auto;}
</style>
