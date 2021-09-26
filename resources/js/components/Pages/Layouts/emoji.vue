<template>
    <div class="emoji-container" v-if="active">
        <div class="upper">
            <div class="container h-100 d-flex align-items-center">
                <ul class="navbar-nav d-flex flex-row">
                    <li class="navbar-item fs-12 px-2" :class="{'active': panal == 'faces'}" @click="panal  = 'faces'">Faces</li>
                    <li class="navbar-item fs-12 px-2" :class="{'active': panal == 'thumbs'}" @click="panal = 'thumbs'">Thumbs</li>
                    <li class="navbar-item fs-12 px-2" :class="{'active': panal == 'others'}" @click="panal = 'others'">Others</li>
                </ul>
            </div>
        </div>
        <div class="page faces w-100 d-flex container mt-3" v-if="panal  == 'faces'">
            <button class="emoji" value="😀" @click="addEmoji('😀')">😀</button>
            <button class="emoji" value="😄" @click="addEmoji('😄')">😄</button>
            <button class="emoji" value="😁" @click="addEmoji('😁')">😁</button>
            <button class="emoji" value="😅" @click="addEmoji('😅')">😅</button>
            <button class="emoji" value="😂" @click="addEmoji('😂')">😂</button>
            <button class="emoji" value="🤣" @click="addEmoji('🤣')">🤣</button>
            <button class="emoji" value="😇" @click="addEmoji('😇')">😇</button>
            <button class="emoji" value="😌" @click="addEmoji('😌')">😌</button>
            <button class="emoji" value="🥰" @click="addEmoji('🥰')">🥰</button>
            <button class="emoji" value="😍" @click="addEmoji('😍')">😍</button>
        </div>
        <div class="page thumbs w-100" v-if="panal  == 'thumbs'">thumbs</div>
        <div class="page others w-100" v-if="panal  == 'others'">other</div>

    </div>
</template>

<script>

export default ({
   props: ['active'],
   data() {
       return {
           panal: 'faces'
       }
   },
   methods: {
       addEmoji(index) 
       {
          this.$emit('emojiadd',index);
       }
   }
})
</script>
