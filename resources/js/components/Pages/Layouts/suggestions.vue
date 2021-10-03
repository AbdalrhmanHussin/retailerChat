<template>
   <div class="sug">
        <form class="form mt-3 position-relative mb-3" @submit.prevent @keyup="searchFriend()">
            <label class="ri-search-line position-absolute top-0 color-sv p-2 fs-14"></label>
            <input type="text" placeholder="Search users" class="form-control search fs-13 color-sv" v-model="search">
        </form>
        <load v-if="!load && suggestions.length == 0"></load>
        <div class="user-container">
            <transition-group name="list" tag="div">
                <div class="user-list list" v-for="(user,index) in suggestions" :key="user.id">
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
            </transition-group>
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
            load: false,
            search: ''
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
        searchFriend()
        {
            this.$store.dispatch('getSuggestions',{
                start: 0,
                search: this.search,
                end:10
            })
        },
    },
    created()
    {
        this.$store.dispatch('getSuggestions');
    }
})
</script>