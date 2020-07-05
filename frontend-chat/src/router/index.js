import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/isLogged/ChatHome.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    meta: { rule: 'isLogged' },
    component: Home
  },
  {
    path: '/verify',
    name: 'Verify',
    meta: { rule: 'isEveryone' },
    component: () => import(/* webpackChunkName: "about" */ '../views/ChatVerify.vue')
  },
  {
    path: '/register',
    name: 'Register',
    meta: { rule: 'isEveryone' },
    component: () => import(/* webpackChunkName: "about" */ '../views/ChatRegister.vue')
  },
  {
    path: '/notfound',
    name: 'NotFound',
    meta: { rule: 'isEveryone' },
    component: () => import(/* webpackChunkName: "about" */ '../views/ChatNotFound.vue')
  },
  {
    path: '/login',
    name: 'Login',
    meta: { rule: 'isEveryone' },
    component: () => import(/* webpackChunkName: "about" */ '../views/ChatLogin.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
