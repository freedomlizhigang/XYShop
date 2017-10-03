import Vue from 'vue'
import Router from 'vue-router'


import Hello from '.././components/Hello.vue'
import Example from '.././components/Example.vue'
import Count from '.././components/Count.vue'
import Login from '.././components/Login.vue'
import Logout from '.././components/Logout.vue'


Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: { requiresAuth: false }
    },
    {
      path: '/logout',
      name: 'Logout',
      component: Logout,
      meta: { requiresAuth: true }
    },
    {
      path: '/count',
      name: 'Count',
      component: Count,
      meta: { requiresAuth: true }
    },
    {
      path: '/example',
      name: 'Example',
      component: Example,
      meta: { requiresAuth: true }
    },
    {
      path: '/',
      name: 'Hello',
      component: Hello,
      meta: { requiresAuth: true }
    },
    { path: '*', component: Example }
  ]
})
