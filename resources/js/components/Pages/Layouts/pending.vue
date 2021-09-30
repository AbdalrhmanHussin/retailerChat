<template>
    <div class="user-list list" v-for="(user,index) in pending" :key="index">
        <load v-if="!load && pending.length == 0"></load>
        <div class="user d-flex w-100 mt-3 position-relative">
            <div class="user-img position-relative" ref="user"> 
                <img :src="'images/users/'+user.image" alt="user">
            </div>
            <div class="user-info mx-2 d-flex align-items-center pb-2">
                <h5 class="user-name fs-13 fw-600 mb-0 ">{{ user.name }}</h5>
            </div>
            <button class="btn btn-primary ms-auto" @click="pending(index)">Cancel</button>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";


export default ({
    computed: {
        ...mapGetters([
            'pending'
        ])
    },
    watch: {
        pending: function(){
            console.log(this.pending);
        }
    },
    created() {
        this.$store.dispatch('pending');
    }
})
</script>
