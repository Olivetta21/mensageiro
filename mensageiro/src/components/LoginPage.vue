<template>
    <div class="login-page">
        <h1>Login</h1>
        <form @submit.prevent="Login.login(email, password)">
        <div>
            <label for="email">Email:</label>
            <input type="text" id="email" v-model="email" required />
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit">Login</button>
        <button type="button" @click="Login.checkLogin()">Check</button>
        </form>
        <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
    </div>
</template>

<script>
import Login from '../Scripts/Login/Login.js'

export default {
    name: 'LoginPage',
    data() {
        return {
            Login: Login,
            email: '',
            password: '',
            errorMessage: ''
        };
    }
}
</script>