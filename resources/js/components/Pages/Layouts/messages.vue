<template>
    <div class="messages p-4">
        <div class="message d-flex align-items-center mb-3  position-relative" :class="{'active': message.user_id == getUser.id}" >
            <div class="message-img">
                <img :src="'/images/users/'+img(message.user_id)" class="user position-absolute">
            </div>
            <div class="message-content">
                <div>
                   <p v-html=" textBuffer(message['content'])" class="mb-0"></p>
                   <span class="fs-10 color-sv text-start">{{ getTiming( message['created_at']) }}</span>
                </div>
            </div>
        </div>  
    </div>
</template>

<script>
import textRender from '../../../helper.js';

import { mapGetters } from 'vuex'
    export default {
      props: ['message','key'],
      data: function()
      {
          return {
              loopItem: ''
          }
      },

      watch: {
          loopItem()
          {
              console.log(this.loopItem);
          }
      },

      computed: {
          ...mapGetters([
              'getUser',
              'findFriend'
          ])
      },
      methods: {
        img(id)
        {
            return (id == this.getUser['id']) ? this.getUser['image'] : this.findFriend['image'];
        },

        textBuffer(text)
        {
            return this.textRender(text,['links']);
        },

        loopEnd(index)
        {
            console.log(index,this.messages.length);
        },

        getTiming(time) 
        {
            let date = this.dateFormate({ReqDate: time});
            if(date.yesterday)
            {
                return 'Yesterday';
            }
            else 
            {
                let setDate = '';
                setDate += (date.day !== undefined) ? date.day + ' ' : '';
                setDate += (date.month !== undefined) ? date.monthLet + ' ' : '';
                setDate += (date.year !== undefined) ? date.year + ' ' : '';
                setDate += (date.month == undefined) ? date.hour : '';
                setDate += (date.month == undefined) ? ':'+date.min + ' '+date.period: '';
                return `${setDate}`
            }
        },

      },
      mixins:[textRender],
      created() {
      }
    }
</script>
