<template>
    <div class="layout-chatter d-flex" :class="{'light': light}">
        <div class="position-relative main-container w-100 d-flex">
            <div class="side-menu align-items-center-md" v-if="this.$store.state.list || roomid == null">
                <a href="#" class="logo w-100 d-flex-lg justify-content-center menu-item d-none">
                    <img src="/images/system/logo.svg">
                </a>
                <ul class="menu d-flex flex-direction-column-lg  justify-content-center-md w-100">
                    <router-link :to="{name:'profile'}" active-class="active" class="menu-item d-flex justify-content-center align-items-center\">
                        <li class="p-2">
                            <i class="ri-user-3-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'myarea'}" active-class="active" class="menu-item d-flex justify-content-center align-items-center">
                        <li class="p-2">
                            <i class="ri-message-2-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'request'}" active-class="active" class="menu-item d-flex justify-content-center align-items-center">
                        <li class="p-2">
                            <i class="ri-contacts-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'suggestions'}" active-class="active" class="menu-item d-flex justify-content-center align-items-center">
                        <li class="p-2">
                            <i class="ri-global-line"></i>
                        </li>
                    </router-link>
                    <div class="menu-item d-flex justify-content-center align-items-center">
                        <li class="p-2" @click="lightmode" :class="{'active':light}">
                            <i class="ri-sun-line"></i>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="usersArea d-flex w-100">
                <router-view></router-view>
                <transition name="list">
                    <room></room>
                </transition>
                <div class="empty d-none-lg d-flex justify-content-center align-items-center" :class="{'d-none': resize()}" v-if="roomid == null">
                    <img src="/images/system/logo.svg" alt="logo">
                </div>
            </div>
        </div>
        <transition name="fade">
            <model-overlay v-if="this.$store.state.model_overlay"></model-overlay>
        </transition>
    </div>
</template>

<script>

import room from '../Pages/Layouts/roomLayout.vue'
import loadingScreen from './Layouts/loadingScreen.vue'
import modelOverlay from './Layouts/model-overlay.vue'
import {mapGetters} from 'vuex';
import axios from 'axios';

export default ({
    data() {
        return {
            user: JSON.parse(localStorage.getItem('user') || '{}'),
            light: JSON.parse(localStorage.getItem('lightMode') || '{}'),
        }
    },
    components: {
        'room': room,
        'loading-screen': loadingScreen,
        'model-overlay': modelOverlay
    },

    computed: {
        ...mapGetters([
          'roomid',
          'window',
          'loadstate',
          'window',
          'users',
          'defaultUsers'
        ]),

        screen() {
            if(window.innerWidth > 992)
            {
                return true
            } 
            
            else 
            {
                return false
            }
        }
    },

    methods: {
        resize()
        {
            window.addEventListener('resize',function(){
                if(this.window > 1200)
                {
                    return true;
                } 
                
                else 
                {
                    return false;
                }
            });
        },

        user()
        {
            if(localStorage.getItem('user'))
            {
                return JSON.parse(localStorage.getItem('user'))
            }
              
        },

        lightmode()
        {
            if(this.light)
            {
                localStorage.setItem('lightMode',false);
                this.light = false;
            } 
            
            else 
            {
                localStorage.setItem('lightMode',true);
                this.light = true;
            }
        },

        soundsNotification(notification)
        {
            let audio = new Audio();
            if(notification == 'message')
            {
                audio.src = '/sounds/messages.mp3';
                audio.play();
            } 
            else if (notification == 'fr') 
            {
                audio.src = '/sounds/friend_request.mp3';
                audio.play();
            }
        },
        setUserDefault(id,user,addDefault)
        {
            axios.post(`user/${id}`).then((res)=>{
                if(addDefault == 'status') user['status'] = res.data['payload']['status'];
            });
        },

       
    },

    created() {
        if(this.user != undefined)
        {
         
        //Channels Listeners

        //Private Channels
        window.Echo.private(`friendrequest.${this.user.id}`)
        .listen('FriendRequest', (e) => {
             this.$store.dispatch('recieverequest',e); 
        });

        window.Echo.private(`chat.${this.user.id}`)
        .listen('message', (e) => {
            this.soundsNotification('message');
            let user = this.users.find((x) => {
                return x.id == e['message'].user_id;
            });
            if(user['id'] > 0)
            {
                let message = {
                    'type': e['message'].type,
                    'content': e['message'].content,
                    'readed': false,
                    'user_id': e['message'].user_id,
                    'created_at': Date.now()
                }
                user['rooms'][0]['latest_message'] = message;
                user['rooms'][0]['messages'].push(message);
            }
        });

       
        }

        //Presentation Channel
        Echo.join(`retailer`)
        .here((active) => {
          this.$store.state.users.forEach(user => {
            let checkActive = active.findIndex((x) => x.id == user.id);
            if(checkActive == -1)
            {
                user['status'] = 'offline';
            }
          });
        })
        .joining((user) => {
           let joiner = this.users.findIndex((x) => x.id == user.id);

           if(joiner !== -1)
           {
               this.setUserDefault(user.id,this.users[joiner],'status');
               this.users[joiner]['status'] = this.setUserDefault(user.id,this.users[joiner],'status');
           }

        })
        .leaving((user) => {
           let leaver = this.users.findIndex((x) => x.id == user.id);

           if(leaver !== -1)
           {
               this.users[leaver]['status'] = 'offline';
           }

        })
        .listen('status', (user) => {
            let userget = this.users.findIndex(x => x.id == user['user'].id);

            if(userget != -1)
            {
                this.users[userget].status = user['user'].status
            }
        });
        this.resize()
    },
    
})
</script>

