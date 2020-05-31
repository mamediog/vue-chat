import Vue from 'vue'
import VueRouter from 'vue-router'

import Verify from '../views/Verify.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Verify',
    meta: { rule: 'isEveryone' },
    component: Verify
  },
  {
    path: '/home',
    name: 'Home',
    meta: { rule: 'isLogged' },
    component: () => import(/* webpackChunkName: "about" */ '../views/isLogged/ChatHome.vue')
  },
  {
    path: '/register',
    name: 'Register',
    meta: { rule: 'isEveryone' },
    component: () => import(/* webpackChunkName: "about" */ '../views/Register.vue')
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
    component: () => import(/* webpackChunkName: "about" */ '../views/Login.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
