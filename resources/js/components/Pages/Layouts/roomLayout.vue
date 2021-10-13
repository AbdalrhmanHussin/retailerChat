<template>
    <div class="room position-relative w-100" :class="{'w-100': !this.$store.state.list}" v-if="roomid !== null">
        <div class="upper p-4 w-100 border-bottom d-flex position-relative">
             <button 
             @click="this.$store.state.roomid = null;this.$store.state.list = true" 
             class="back" 
             ref="back">
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
        </div>
        <emoji :active="emoji" @emojiadd="addEmoji"></emoji>
        <p class="chat-loader" v-if="scroll">Loading</p>
        <div class="chatty position-relative">

            <div class="w-100 chat h-100 position-relative" ref="chat" @scroll="scrollTop()">
                <div class="chat-body ">
                    <div v-for="(message,key) in findFriend['rooms'][0]['messages']" :key="key" v-for-callback:[loaded]="{key: key, array: findFriend['rooms'][0]['messages'], load: this.$store.state.firstLoad ,callback: wayDown}">
                        <messages :message = "message" :key="key"></messages>
                    </div>
                </div>
            </div>

            <div class="chat-user w-100 border-top d-flex">
                <div class="position-relative d-flex align-items-center px-3 w-100">
                    <span class="position-absolute fs-13 color-sv mb-3" v-if="placeholder && mssg.length == 0">Type your message here...</span>
                    <textarea class="form-control position-relative" v-model="mssg" @focus="placeholder = false" @blur="placeholder = true" @keyup="typing"></textarea>
                    <div class="emoji" @click="emojiToggler" :class="{'active': emoji}">
                        <i class="ri-emotion-happy-line"></i>
                    </div>
                    <div class="send ml-2" @click="send()">
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
import textRender from '../../../helper.js';
import {mapGetters} from 'vuex';

export default ({
    data() {
        return {
            mssg: '',
            placeholder: true,
            emoji: false,
            loaded: true,
            loading: false,
            scroll: false,
            heightChat: this.$refs.innerHeight
        }
    },
    computed: {
       ...mapGetters([
          'active',
          'roomid',
          'window',
          'getUser',
          'userid',
          'users',
          'findFriend'
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
    watch: {
        roomid()
        {
            this.loaded = true;
            console.log(this.loaded);
        },
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

        wayDown()
        {
            if(this.loaded)
            {
                this.$refs.chat.scrollTop = document.querySelector('.chat-body').offsetHeight
            } 
            
            else {
                this.$refs.chat.scrollTop =   document.querySelector('.chat-body').offsetHeight - this.heightChat;
            }

            this.loading = false;
        },

        typing()
        {
            Echo.private(`chat.${this.$store.state.userid}`)
            .whisper('typing', {
                id: this.getUser.id
            });
        },

        send()
        {
            let message = {
                userid: this.getUser.id,
                sendtoid: this.userid,
                roomid: this.roomid,
                content: this.mssg,
                type: 'text'    
            }

            this.mssg = ''
            
            this.$store.dispatch('sendMessage',message);
        },

        scrollTop()
        {
            this.loading = true;
            this.heightChat = document.querySelector('.chat-body').offsetHeight

            
            if(this.$refs.chat.scrollTop == 0)
            {
                this.loaded = false;
                this.$store.dispatch('roomRender');
            }
        },

        textBuffer(text)
        {
            return this.textRender(text,['links']);
        },
    },
    directives: {
        forCallback(el, binding) {
            let element = binding.value
            var key = element.key
            var len = 0;
             if (Array.isArray(element.array)) {
                    len = element.array.length
                }

                else if (typeof element.array === 'object') {
                    var keys = Object.keys(element.array)
                    key = keys.indexOf(key)
                    len = keys.length
                }

                if (key == len - 1) {
                    if (typeof element.callback === 'function') {
                        binding.loaded = true;
                        element.callback()
                    }
                }
        }
    },
    mounted() {
       if(this.$refs.chat)
       {
            this.$refs.chat.scrollTop = this.$refs.chat.clientHeight * 1000000000;
       }
    }
})
</script>
