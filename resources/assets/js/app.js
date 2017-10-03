
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
// 引入基础类
import Vue from 'vue'
import router from './router'
import VueResource from 'vue-resource'
// 首页模板
import App from './components/App.vue'

import store from './vuex/store'

Vue.use(VueResource)
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App }
})

router.beforeEach((to, from, next) => {
  // console.log(to);
  if (to.meta.requiresAuth && store.getters.get_user_id == 0 && to.name != 'Login') {
    next({
      name:'Login'
    });
  }
  else
  {
    next();
  }
})