/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');



import { createApp } from 'vue';
import router from './router.js';
import store from './store.js';
import AppComponent from './components/AppComponent.vue'
import chatComponent from './components/Pages/chatComponent.vue'

import VueChatScroll from 'vue-chat-scroll'
import useVuelidate from '@vuelidate/core'
import Toast from "vue-toastification";
import vLinkify from 'v-linkify';


// Import the CSS or use your own!
import "vue-toastification/dist/index.css";




window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

function detectSize() 
{
    if(window.innerWidth < 1200)
    {
        window.small = true;
    }
    else 
    {
        window.small = false;
    }
}

window.addEventListener('resize',function(){
    detectSize();
});

detectSize();

let app = createApp({
    components: {
        AppComponent,
        chatComponent
    },
    created() 
    {
        console.log('hello');
    }
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


app.use(store)
   .use(router)
   .use(VueChatScroll)
   .use(useVuelidate)
   .use(Toast)
   .use(vLinkify)
   .mount("#app")

