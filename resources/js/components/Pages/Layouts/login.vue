<template>
  <div class="login">
     <p class="text fs-12 fw-600 text-center pt-4">Sign in by using social accounts</p>
     <div class="social-btns">
        <a href="/socialite/facebook" class="d-flex fs-12 color-wh facebook justify-content-center">
          <i class="ri-facebook-circle-fill"></i>
          <p class="px-2 mb-0 text-center">Sign in with facebook</p>
        </a>
        <a href="/socialite/google" class="d-flex mt-3 fs-12  google justify-content-center">
          <i class="ri-google-fill pr-2"></i>
          <p class="px-2 mb-0">Sign in with google</p>
        </a>
     </div>
     <p class="text fs-12 fw-600 text-center pt-4 mt-4">Or sign in by using email</p>
     <form class="form"  @submit.prevent="login()">
        <div class="form-group position-relative"> 
            <label for="email" class="position-absolute" :class="{'active':active == 'email' || form.email.length > 0}"> Email Address</label>
            <input class="form-control" type="email" required autocomplete="email" id="email" @focus="active = 'email'" @blur="active = ''" v-model="form.email" @input="handleInput" />
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':active == 'password' || form.password.length > 0}">Password</label>
            <input class="form-control" type="password" id="password" required @focus="active ='password'" @blur="active = ''" v-model="form.password" ref="password" />
        </div>
        <div class="form-group d-flex align-items-center">
          <input type="checkbox" class="remember position-relative" v-model="form.remember" id="remember">
          <label for="remember" class="fs-11 fw-600">Remember me</label>
        </div>
        <span class="error d-block w-100" v-if=" error.login">Wrong Credentials</span>
        <input type="submit" value="Login Now" class="mt-3 mb-3 w-100 form-btn">
        <div class="actions">
           <router-link :to="{name:'register'}" class="fs-11 color-sv d-block">Not a member? Register now</router-link>
           <router-link :to="{name:'forget'}" class="fs-11 color-sv d-block mt-2">Forget Password</router-link>
        </div>
     </form>
  </div>
</template>

<script>
import axios from 'axios'

export default ({
  data: function() {
    return {
      active: '',
      form: {
         email: '',
         password: '',
         remember: false
      },
      error: {}
    }
  },
  methods: {
    login()
    {
      axios.post('/user/login',this.form)
        .then((res) => {
          if(res.data.message == true)
          {
            this.$router.push('/chat');
          } else {
            let payload = res.data.payload;
            for(var key in payload)
            {
              this.error[key] = payload[key]
            }
          }
      })
    },
    handleInput () {
      
    }
  },
  mounted(){
  }
})
</script>
