<template>
  <div class="login">
     <p class="text fs-12 fw-600 text-center pt-4">Sign in by using social accounts</p>
     <div class="social-btns">
        <a href="#" class="d-flex fs-12 color-wh facebook justify-content-center">
          <i class="ri-facebook-circle-fill"></i>
          <p class="px-2 mb-0 text-center">Sign in with facebook</p>
        </a>
        <a href="#" class="d-flex mt-3 fs-12  google justify-content-center">
          <i class="ri-google-fill pr-2"></i>
          <p class="px-2 mb-0">Sign in with google</p>
        </a>
     </div>
     <p class="text fs-12 fw-600 text-center pt-4 mt-4">Or sign in by using email</p>
     <form class="form" @submit.prevent="register">
        <div class="form-group position-relative mb-4"> 
            <label for="user" class="position-absolute" :class="{'active':active == 'username' || form.name.length > 0}"> Username</label>
            <input class="form-control" type="text" required autocomplete="user" id="user" @focus="active = 'username'" @blur="active = ''" v-model="form.name"/>
            <span class="error d-block w-100" v-if="error.name">{{ error.name[0] }}</span>
        </div>
        <div class="form-group position-relative"> 
            <label for="email" class="position-absolute" :class="{'active':active == 'email' || form.email.length > 0}"> Email Address</label>
            <input class="form-control" type="email" required autocomplete="email" id="email" @focus="active = 'email'" @blur="active = ''" v-model="form.email"/>
            <span class="error d-block w-100" v-if="error.email">{{ error.email[0] }}</span>
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':active == 'password' || form.password.length > 0}">Password</label>
            <input class="form-control" type="password" id="password" required  @focus="active = 'password'" @blur="active = ''" v-model="form.password"/>
            <span class="error d-block w-100" v-if="error.password">{{ error.password[0] }}</span>
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':active == 'password_confirmation' || form.password_confirmation.length > 0}">Confirm Password</label>
            <input class="form-control" type="password" id="password" required  @focus="active = 'password_confirmation'" @blur="active = ''" v-model="form.password_confirmation"/>
        </div> 
        <input type="submit" value="Register Now" class="mt-3 w-100 form-btn">
        <div class="actions mt-3">
           <router-link :to="{name:'register'}" class="fs-11 color-sv d-block">Already have account? Sign in</router-link>
        </div>
     </form>
  </div>
</template>

<script>
import useVuelidate from '@vuelidate/core'
import { required, email, minLength, sameAs } from '@vuelidate/validators'
import axios from 'axios'

export default ({
  data: function() {
    return {
      active: '',
      form: {
        email: '',
        password: '',
        name: '',
        password_confirmation: ''
      },
      error: {}
    }
  },
  methods: {
    register()
    {
      axios.post('user/register',this.form).then((res)=>{
        if(res.data.message)
        {
          this.$router.push({name:'chat'});
        }
        else 
        {
          let payload = res.data.payload;
          for(var key in payload)
          {
            this.error[key] = payload[key]
          }
        }
      })
    }
  }
})
</script>
