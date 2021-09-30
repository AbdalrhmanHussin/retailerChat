<template>
    <div class="layout-chatter d-flex">
        <div class="position-relative main-container w-100 d-flex">
            <div class="side-menu align-items-center-md">
                <a href="#" class="logo w-100 d-flex-lg justify-content-center menu-item d-none">
                    <img src="/images/system/logo.svg">
                </a>
                <ul class="menu d-flex flex-direction-column-lg  justify-content-center-md w-100">
                    <router-link :to="{name:'myarea'}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-user-3-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'myarea'}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-message-2-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'request'}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-contacts-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'myarea'}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-settings-2-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:'suggestions'}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-global-line"></i>
                        </li>
                    </router-link>
                    <router-link :to="{name:''}" active-class="active" class="menu-item">
                        <li class="p-2">
                            <i class="ri-sun-line"></i>
                        </li>
                    </router-link>
                </ul>
            </div>
            <div class="usersArea d-flex w-100">
                <router-view></router-view>
                <room></room>
                <div class="empty d-none-md d-flex justify-content-center align-items-center" v-if="roomid == null">
                    <img src="/images/system/logo.svg" alt="logo">
                </div>
            </div>
        </div>
        <transition name="fade">
            <loading-screen v-if="loadstate"></loading-screen>
        </transition>
    </div>
</template>

<script>
import room from '../Pages/Layouts/roomLayout.vue'
import loadingScreen from './Layouts/loadingScreen.vue'
import {mapGetters} from 'vuex';
export default ({
    components: {
        'room': room,
        'loading-screen': loadingScreen
    },

    computed: {
        ...mapGetters([
          'roomid',
          'window',
          'loadstate'
        ]),

        screen() {
            if(window.innerWidth > 992)
            {
                return true
            } else {
                return false
            }
        }
    },

    methods: {
        resize()
        {
            window.addEventListener('resize',function(){
                console.log(this.window.innerWidth > 992 && this.roomid == null);
            });
        },
    },

    created() {
    }
    
})
</script>

