<template>
    <div class="room position-relative w-100" :class="{'w-100': !this.$store.state.list}" v-if="roomid !== null">
        <div class="upper p-4 w-100 border-bottom d-flex position-relative">
             <button @click="this.$store.state.roomid = null;this.$store.state.list = true" class="back" ref="back">
                <i class="ri-arrow-left-s-line"></i>
             </button>
             <div class="user-info d-flex">
                 <div class="user-img">
                     <img :src="'images/users/'+active.image" alt="user" class="w-100 h-100">
                 </div>
                 <div class="user-info position-relative d-flex mx-3">
                     <h5 class="ml-2 fs-14 mb-0 d-flex align-items-center">{{ active.name }}</h5>
                     <span class="status position-absolute  d-flex" :class="{'active': active.status == 'active','busy': active.status == 'busy','danger': active.status == 'dis','offline': active.status == 'offline'}"></span>
                 </div>
             </div>
             <ul class="navbar-nav flex-row ml-auto tools">
                 <li class="nav-item d-flex align-items-center">
                     <i class="ri-search-line"></i>
                 </li>
                  <li class="nav-item d-flex align-items-center">
                     <i class="ri-phone-line"></i>
                 </li>
                  <li class="nav-item d-flex align-items-center">
                     <i class="ri-vidicon-line"></i>
                 </li>
                  <li class="nav-item d-flex align-items-center">
                     <i class="ri-user-2-line"></i>
                 </li>
             </ul>
        </div>
        <emoji :active="emoji" @emojiadd="addEmoji"></emoji>
        <div class="chatty">
            <div class="w-100 chat h-100" ref="chat">
                <messages></messages>
            </div>
            <div class="chat-user w-100 border-top d-flex">
                <div class="position-relative d-flex align-items-center px-3 w-100">
                    <span class="position-absolute fs-13 color-sv mb-3" v-if="placeholder && mssg.length == 0">Type your message here...</span>
                    <textarea class="form-control position-relative" v-model="mssg" @focus="placeholder = false" @blur="placeholder = true" @keyup="typing"></textarea>
                    <div class="emoji" @click="emojiToggler" :class="{'active': emoji}">
                        <i class="ri-emotion-happy-line"></i>
                    </div>
                    <div class="send ml-2">
                        <i class="ri-send-plane-2-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import emoji from "./emoji.vue";
import messages from './messages.vue';
import {mapGetters} from 'vuex';

export default ({
    data() {
        return {
            mssg: '',
            placeholder: true,
            emoji: false
        }
    },
    computed: {
       ...mapGetters([
          'active',
          'roomid',
          'window'
       ]),
       height: function() 
       {
           document.querySelector('.chat');
           return 'hello'
       },
    },
    components: {
       'emoji': emoji,
       'messages': messages
    },
    methods: {
        emojiToggler()
        {
            this.emoji = (this.emoji) ? false : true; 
        },
        addEmoji(emoji)
        {
            this.mssg += emoji;
        },
        resize()
        {
            window.onresize = () => {
                if(this.roomid !== null && window.innerWidth < 992)
                {
                    this.$refs.back.style.display = 'none'
                }
                else 
                {
                    this.$refs.back.style.display = 'block'
   
                }
            }
        },
        typing()
        {
            console.log('typing')
            Echo.private(`chat.1`)
            .whisper('typing', {
                name: 'typing'
            });
        }
    },
    mounted() {
       if(this.$refs.chat)
       {
            this.$refs.chat.scrollTop = this.$refs.chat.clientHeight * 1000000000;
       }
       console.log(this.window.innerWidth);
    }
})
</script>
