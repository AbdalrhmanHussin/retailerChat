<template>
  <div class="login">
    <p class="text fs-15 fw-600 text-center pt-4 mt-4" v-if="validToken">Password Reset</p>
    <p class="text fs-12  text-center pt-4 mt-4" v-if="validToken">Use a password easy to remember for you like something in your life or a date </p>
    <form class="form" v-if="validToken" @submit.prevent="changePassword()">
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':active == 'password' || form.password.length > 0}">Password</label>
            <input class="form-control" type="password" id="password" required @focus="active = 'password'" @blur="active = ''" v-model="form.password"/>
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password_confirmation" class="position-absolute" :class="{'active':active == 'password_confirmation' || form.password_confirmation.length > 0}">Confirm Password</label>
            <input class="form-control" type="password" id="password_confirmation" required @focus="active = 'password_confirmation'" @blur="active = ''" v-model="form.password_confirmation"/>
        </div>
        <input type="submit" value="Reset Password" class="mt-3 w-100 form-btn">
        <span class="error d-block w-100" v-if="error.password">{{ error.password[0] }}</span>
    </form>
     <span class="error d-block w-100" v-if=" ExpiredToken">Invalid or expired token</span>
     <div class="loadIndicator d-flex justify-content-center align-items-center w-100">
        <p class="mb-0 px-2 fs-14 mt-5 fw-600" v-if="!validToken && loading">Loading</p>
      </div>
  </div>
</template>

<script>
import axios from "axios";


export default ({
  props: ['token','email'],
  data: function() {
    return {
      active: '',
      form: {
        email: '',
        password: '',
        password_confirmation:''
      },
      error: {},
      validToken: false,
      ExpiredToken: false,
      loading: true
    }
  },
  methods: {
    changePassword()
    {
      axios.post('/user/changepassword',{
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
          email: this.email,
          token: this.token  
        }).then((res)=>{
           if(res.data.message) {
              this.$router.push('/');
           } else {
            let payload = res.data.payload;
            for(var key in payload)
            {
              this.error[key] = payload[key]
            }
           }
        });
    },
    checkToken()
    {
      console.log('worked');
      axios.post('/user/checktoken',{
        token: this.token,
        email: this.email
      }).then((res) => {
         console.log(res.data.message);
         if(res.data.message)
         {
            this.validToken = true;
         }
         else 
         {
           this.ExpiredToken = true;
         }
         this.loading = false;
      })
    }  
  },
  created()
  {
    this.checkToken()
  }
})
</script>
