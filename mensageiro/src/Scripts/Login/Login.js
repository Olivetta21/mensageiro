//import router from "@/router";
import { fetchJson } from "../fetcher.js";

class Login {
    static USERLOGGED = false;

    static async login(email, password) {
        console.log("Enviando login");
        
        const data = await fetchJson("/login/login.php", [{"h":"login","b":[email, password]}]);

        console.log(data);

        //router.push({name: 'home'});
    }

    static async checkLogin() {
        console.log("Checando login");
        const data = await fetchJson("/login/check.php");
        console.log(data);
    }
}

export default Login;