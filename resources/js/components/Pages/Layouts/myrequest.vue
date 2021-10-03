<template>
    <div class="userlist px-4 pt-4">
        <h4 class="fs-20 color-sv fw-600">Friend Requests</h4>
        <div class="pending">
            <div class="user-list list" v-for="(user,index) in request" :key="index">
                <load v-if="!load && request.length == 0"></load>
                <div class="user d-flex w-100 mt-3 position-relative">
                    <div class="user-img position-relative" ref="user"> 
                        <img :src="'images/users/'+user.image" alt="user">
                    </div>
                    <div class="user-info mx-2 d-flex align-items-center pb-2">
                        <h5 class="user-name fs-13 fw-600 mb-0 ">{{ user.name }}</h5>
                    </div>
                    <div class="d-flex ms-auto">
                        <button class="btn btn-primary " @click="myrequest('accept',user.id,index)">Accept</button>
                        <button class="btn btn-primary mx-2 color-red" @click="myrequest('cancel',user.id,index)">Cancel</button>
                    </div>
                </div>
            </div>
            <p v-if="request.length == 0" class="fs-12 text-center color-sv fw-600 mt-5">No Friend Requests</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";


export default ({
    computed: {
        ...mapGetters([
            'request'
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
        },
        myrequest(action,userid,index)
        {
            this.$store.dispatch('submitrequest',{id:userid,action:action,index:index})
        }
    },
    created() {
        this.$store.dispatch('pending');
        this.$store.dispatch('getrequest')
    }
})
</script>
