<template>
    <div class="userlist px-4 pt-4" v-if="this.$store.state.list">
        <h4 class="fs-23 color-sv fw-600">Chats</h4>
        <form class="form mt-3 position-relative" @submit.prevent>
            <label class="ri-search-line position-absolute top-0 color-sv p-2 fs-14"></label>
            <input type="text" placeholder="Search users" class="form-control search fs-13 color-sv" v-model="userSearch">
        </form>
        <div class="chats" v-if="users.length">
            <span class="d-block fs-13 mt-3 fw-600 color-sv">Recent</span>
            <div class="list w-100">
                <div class="user-list-loaded" v-if="loaded" @scroll="scroll()" ref="chat">
                    <div class="user  d-flex w-100 mt-3 position-relative" 
                    :class="{'active': usersEleActive == index && roomid !== null}"
                    v-for="(user,index) in usersFilter" 
                    :key="index" 
                    @click="usersEleActive = index;active(index,user['rooms'][0]['id']);listDeclare()" >
                        <div class="user-img position-relative" ref="user"> 
                            <img :src="'images/users/'+user.image" alt="user">
                            <span class="status position-absolute" :class="{'active': user.status == 'active','busy':user.status == 'busy','danger': user.status == 'Do Not Disturb','offline':user.status == 'offline'}"></span>
                        </div>
                        <div class="user-info mx-2">
                            <h5 class="user-name fs-13 fw-600 mb-0">{{ user.name }}</h5>
                            <p class="user-name fs-12 color-sv mb-0" :class="{'fw-900': lastMessage(index)['readed'] }" v-if="lastMessage(index)">{{ lastMessage(index)['content'] }}</p>
                            <span class="d-block position-absolute top-0 right-0 fs-10 color-sv p-2" v-if="lastMessage(index)">{{ getTiming(lastMessage(index)['created_at']) }}</span>
                            <p class="user-name fs-12 color-sv mb-0"  v-if="!lastMessage(index)">Say hi to your new friend</p>

                        </div>
                    </div>
                </div>
                <load v-if="!loaded"></load>
            </div>
            <transition name="load">
                <div class="loader w-100 d-flex position-relative justify-content-center" v-if="scrollLoading && !loadfinish">
                    <div class="position-absolute w-100 d-flex loader-frame justify-content-center ">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </transition>
        </div>
        <p class="no-friend fs-12 text-center color-sv mt-4" v-else> No friends to show </p>
        <span></span>
    </div>
</template>

<script>
import dateFormate from '../../../helper.js';
import {mapGetters} from 'vuex'
import userLoad from './userLoad.vue'

export default {
    data() {
        return {
            usersEleActive: 0,
            userSearch:'',
            loaded: false,
            scrollLoading: false,
            screen: window.innerWidth,
        }
    },

    components: {
        'load': userLoad
    },

    computed: {
        ...mapGetters([
            'users',
            'load',
            'loadfinish',
            'roomid',
            'window'
        ]),
        //Search for a user
        usersFilter()
        {
            this.loading()
            return this.users.filter(x => {
                return x.name.toLowerCase().indexOf(this.userSearch.toLowerCase()) > -1;
            });
        },
        
    },

    methods: {
        //get the last message that has been send
        lastMessage(id) 
        {
            if(id <= this.users.length) 
            {
                if(this.users[id]['rooms'][0]['messages'].length > 0)
                {
                    return this.users[id]['rooms'][0]['messages'][0];
                } 
                else {
                    return false;
                }
               
            }
            else 
            {
                throw `Out of range,the maximum allowed id is ${this.users.length}`
            }
        },

        getTiming(time) 
        {
            let date = this.dateFormate({ReqDate: time});
            let setDate = '';
            setDate += (date.month !== undefined) ? date.monthLet + ' ' : '';
            setDate += (date.day !== undefined) ? date.day + ' ' : '';
            setDate += (date.year !== undefined) ? date.year + ' ' : '';
            setDate += (date.month == undefined) ? date.hour : '';
            setDate += (date.month == undefined) ? ':'+date.min + ' '+date.period: '';
            return `${setDate}`
        },

        loading() 
        {
            setTimeout(()=>{
                this.loaded = true
            },2000);
        }, 

        active(id,roomid) 
        {
            console.log(roomid);
            this.$store.state.roomid = roomid;
            this.$store.commit('active',id);
        },

        scroll()
        {
            if(this.$refs.chat.offsetHeight + this.$refs.chat.scrollTop >= this.$refs.chat.scrollHeight)
            {
                this.scrollLoading = true;
                setTimeout(() => {
                    let startFrom = document.querySelectorAll('.user').length;
                    if(!this.loadfinish)
                    {
                        this.$store.commit('loadUsers',startFrom);
                        this.scrollLoading = false;
                    }
                },2000)
            } 
        },

        resize()
        {
           window.addEventListener('resize',() => {
               if(this.roomid == null || window.innerWidth > 1200)
               {
                   this.$store.state.list = true
               }
               else 
               {
                   this.$store.state.list = false;
               }
           })
        },
        listDeclare()
        {
            this.$store.state.list = (this.roomid == null || window.innerWidth > 992) ? true : false;
        }
    },     
    mixins:[dateFormate],
    
    created()
    {
        this.loading();
        this.resize();
        this.listDeclare();
        console.log(this.users);
        console.log('counter:'+this.users.length);
    }
}
</script>