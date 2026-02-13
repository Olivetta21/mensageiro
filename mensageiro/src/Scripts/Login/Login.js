import router from "@/router";
import { fetchJson } from "../fetcher.js";

class Login {
    static USERLOGGED = false;

    static setLogged(expires_in) {
        Login.USERLOGGED = true;
        setTimeout(() => {
            Login.USERLOGGED = false;
            router.push({name: 'login'});
            console.log("Token expirado");
        }, expires_in * 1000);
    }

    static async login(email, password) {        
        const data = await fetchJson("/login/login.php", [{"h":"login","b":[email, password]}]);
        if (data.success) {
            this.setLogged(data.expires_in);
            router.push({name: 'home'});
        }
    }

    static async checkLogin() {
        const data = await fetchJson("/login/check.php");
        if (data.success) {
            this.setLogged(data.expires_in);
            return true;
        }        
        return false;
    }
}

export default Login;