import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import Login from './Scripts/Login/Login.js'

const app = createApp(App)

router.beforeEach((to, from, next) => {
    console.log('Rota atual:', to.path);
    console.log('Rota anterior:', from.path);
    //if (from.matched.length > 0) from.meta?.class?.leaving();
    //to.meta?.class?.entering();
    
    if (to.meta?.requiresAuth && !Login.USERLOGGED) {
        next('/login');
    } else {
        next();
    }
})

app.use(router).mount('#app')