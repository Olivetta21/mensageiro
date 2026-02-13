import { createRouter, createWebHistory } from 'vue-router'

import LoginPage from './components/LoginPage.vue'
import HomePage from './components/HomePage.vue'

import Login from './Scripts/Login/Login.js'
import Home from './Scripts/Home/Home.js'

const routes = [
  { path: '/login', name: 'login', component: LoginPage, meta: { class: Login} },
  { path: '/home', name: 'home', component: HomePage, meta: { class: Home, requiresAuth: true} },
  { path: '/:pathMatch(.*)*', redirect: '/login' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router