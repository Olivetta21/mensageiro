import { ref } from "vue";
import { fetchJson } from "../fetcher";

class Home {
    static before_enter() {
        this.load_contacts();
    }

    
    static contacts_ = ref([]);
    static get contacts() { return this.contacts_.value; }
    static set contacts(value) { this.contacts_.value = value; }

    static async load_contacts() {        
        const response = await fetchJson("contacts/load.php");
        if (response.success) {
            this.contacts = response.contacts;
        }
        console.log(this.contacts);
    }


    
    static message_opened_ = ref({
        contact_linked_id: null,
        messages: []
    });
    static get message_opened() { return this.message_opened_.value; }

    static async selectContact(id) {
        if (id == null) {
            this.message_opened.contact_linked_id = null;
            this.message_opened.messages = [];
            return;
        }

        const response = await fetchJson("contacts/loadMessage.php", [{"h": "contact_id", "b": id}]);
        if (response.success) {
            this.message_opened.contact_linked_id = id;
            this.message_opened.messages = response.messages;
        }
    }
}

export default Home;