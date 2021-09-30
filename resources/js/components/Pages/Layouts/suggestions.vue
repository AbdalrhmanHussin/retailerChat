<template>
    <div class="user-list list" v-for="(user,index) in suggestions" :key="index">
        <load v-if="!load && suggestions.length == 0"></load>
        <div class="user d-flex w-100 mt-3 position-relative">
            <div class="user-img position-relative" ref="user"> 
                <img :src="'images/users/'+user.image" alt="user">
            </div>
            <div class="user-info mx-2 d-flex align-items-center pb-2">
                <h5 class="user-name fs-13 fw-600 mb-0 ">{{ user.name }}</h5>
            </div>
            <button class="btn btn-primary ms-auto" @click="pending(index,user.id)">Add Friend</button>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
import userLoad from "./userLoad.vue";


export default ({
    components: {
        'load': userLoad
    },
    data() {
        return {
            load: false
        }
    },
    computed: {
        ...mapGetters([
            'suggestions'
        ])
    },
    watch: {
        suggestions: function()
        {
            this.load = true;
        }
    },
    methods: {
      pending(index,id)
      {
          this.$store.dispatch('friendRequest',{
              index: index,
              id: id
          });
      },
    },
    created()
    {
        if(this.suggestions.length == 0)
        {
            this.$store.dispatch('getSuggestions');
        }
    }
})
</script>