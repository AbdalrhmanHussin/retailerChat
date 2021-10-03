<template>
    <div class="pending">
         <div class="pending-container" v-if="pending.length != 0">
            <div class="user-list list" v-for="(user,index) in pending" :key="index">
            <load v-if="!load && pending.length == 0"></load>
            <div class="user d-flex w-100 mt-3 position-relative">
                <div class="user-img position-relative" ref="user"> 
                    <img :src="'images/users/'+user.image" alt="user">
                </div>
                <div class="user-info mx-2 d-flex align-items-center pb-2">
                    <h5 class="user-name fs-13 fw-600 mb-0 ">{{ user.name }}</h5>
                </div>
                <button class="btn btn-primary ms-auto" @click="removepending(user.id,index)">Cancel</button>
            </div>
        </div>
         </div>
         <p v-else class="fs-12 text-center color-sv fw-600 mt-5">No Pending Users</p>
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
    methods: {
        removepending(id,index) {
            this.$store.dispatch('removepending',{
                id: id,
                index: index
            })
        }
    },
    created() {
        this.$store.dispatch('pending');
    }
})
</script>
