<template>
    <div class="home-page">
        <div class="contact-container">
            <div class="contacts" v-for="contact in Home.contacts" :key="contact.id" @click="Home.selectContact(contact.id)">
                contato {{ contact.id }}
            </div>
        </div>
        <div class="message-container">
            <div class="messages-list">
                <div :class="['message', message.you ? 'you' : '']" v-for="message in Home.message_opened.messages" :key="message.id">
                    <div class="message-content"> {{ message.content }} </div>
                </div>    
            </div>
            <div class="message-box">
                <input type="text" v-model="Home.message_box_content" @keyup.enter="Home.sendMessage()">
                <button @click="Home.sendMessage()"> > </button>
            </div>
        </div>
    </div>
</template>

<script>
import Home from '../Scripts/Home/Home';

export default {
    name: 'HomePage',
    data() {
        return {
            Home: Home
        };
    }
}
</script>

<style scoped>
.home-page {
    display: flex;
    height: 100vh;
    color: white;
}

.contact-container {
    flex: 1;
    background-color: #001803;
}

.message-container {
    flex: 2;
    background-color: #004227;
    word-break: break-word;
}

.messages-list {
    height: calc(100% - 50px);
    overflow-y: auto;
    padding: 10px;
}

.message-box {
    height: 50px;
    display: flex;
    align-items: center;
    padding: 0 10px;
    background-color: #001803;
}

.message-box input {
    flex: 1;
    padding: 5px;
    margin-right: 10px;
    border-radius: 5px;
    border: none;
}

.message {
    width: 100%;
    display: flex;
    margin: 10px 0;
}

.message.you {
    justify-content: flex-end;
}

.message-content {
    background-color: #012c06;
    padding: 10px;
    border-radius: 5px;
    max-width: 60%;
}

.message.you>.message-content {
    background-color: #00794d;
}

</style>