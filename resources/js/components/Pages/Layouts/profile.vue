<template>
    <div class="userlist px-4 pt-4 profile">
        <div class="user-container">
            <div class="pt-4 avatar text-center">
                <div class="avatar-container position-relative">
                    <img :src="'images/users/'+getUser.image" alt="user">
                    <div class="overlay position-absolute justify-content-center d-flex align-items-center" @click="this.$store.state.model_overlay = true;this.$store.state.model = 'avatar'">
                        <i class="ri-camera-fill fs-25"></i>
                    </div>
                </div>
            </div>
            <!-- Change Username -->
            <form class="form w-100 mt-5 pt-2"  @submit.prevent="update('name',{name:name})">
                <label for="username" class="fs-12 color-sv d-block mb-2">Username</label>
                <input class="form-control w-100 list-input fs-13 color-sv ps-2" placeholder="User name"  id="username" v-model="name">
                <p class="error" v-if="this.$store.state.errors.name">{{ this.$store.state.errors.name[0] }}</p>
                <button class="btn btn-primary ms-auto w-100 mt-5  fs-12 profile_btn">Change Username</button>
            </form>
             <!-- Change password -->
            <form class="form w-100 mt-5 pt-2" @submit.prevent="update('password',{password: password})">
                <label for="password" class="fs-12 color-sv d-block mb-2">Change Password</label>
                <div class="form-group mt-2">
                    <input class="form-control w-100 list-input fs-13 color-sv ps-2" placeholder="Password" id="password" v-model="password.oldpassword">  
                </div>
                <div class="form-group mt-2">
                    <input class="form-control w-100 list-input fs-13 color-sv ps-2" placeholder="New Password" id="newpassword" v-model="password.password">  
                </div>
                <div class="form-group mt-2">
                    <input class="form-control w-100 list-input fs-13 color-sv ps-2" placeholder="Confirm Password"  id="newcpassword" v-model="password.password_confirmation">  
                </div>
                <p class="error" v-if="this.$store.state.errors.password">{{ this.$store.state.errors.password[0] }}</p>
                <button class="btn btn-primary ms-auto w-100 mt-5  fs-12 profile_btn">Change Password</button>
            </form>
            <div class="state-select mt-5 pt-2 position-relative">
               <p class="mb-0 fs-12 fw-600 select-header d-flex" @click="select = !select;">
                   {{ statusUpper }}
                   <i class="ri-arrow-drop-down-line ms-auto d-block"></i>
                </p>
                <transition name="list">
                    <ul class="nav-menu navbar-nav select-menu w-100 position-absolute" v-if="select">
                        <li class="fs-12 color-wh mb-0" :class="{'active': status == 'active' }" @click="status = 'active'">Active</li>
                        <li class="fs-12 color-wh mb-0" :class="{'active': status == 'busy' }" @click="status = 'busy'">Busy</li>
                        <li class="fs-12 color-wh mb-0" :class="{'active': status == 'Do Not Disturb'  }" @click="status = 'Do Not Disturb'">Do Not Disturb</li>
                    </ul>
                </transition>
                <button class="btn btn-primary ms-auto w-100 mt-5 logout fs-12 profile_btn" @click="logout">Logout</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex"


export default ({
    data() {
       return {
           select: false,
           image: 'images/users/',
           name: this.$store.state.user.name,
           status: this.$store.state.user.status,
           password: {
               oldpassword: '',
               password: '',
               password_confirmation: ''
           }
       } 
    },

    computed: {
        ...mapGetters([
            'getUser'
        ]),
        
        statusUpper()
        {
            return this.status.charAt(0).toUpperCase() + this.status.slice(1);
        }
    },

    watch: {
        status() 
        {
            this.select = false;
            console.log(this.user);
            this.update('status',{status: this.status});
        }
    },

    methods: {
        update(col,val = {})
        {
            this.$store.dispatch('modify',{update: col,value: val})
        },
        logout()
        {
            axios.post('/auth/logout').then(()=>{
                this.$router.push('/');
            })
        }
    },

    created() {
        console.log(this.$store.state.user.name);
    }
})
</script>
