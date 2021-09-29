<template>
  <div class="login">
    <p class="text fs-15 fw-600 text-center pt-4 mt-4">Password Forget</p>
    <p class="text fs-12  text-center pt-4 mt-4" v-if="!send">After submit password reset follow the instruction that has been send to your account</p>
    <form class="form" @submit.prevent="forget" v-if="!send">
        <div class="form-group position-relative"> 
            <label for="email" class="position-absolute" :class="{'active':active == 'email' || form.email.length > 0}"> Email Address</label>
            <input class="form-control" type="email" required autocomplete="email" id="email" @focus="active = 'email'" @blur="active = ''" v-model="form.email"/>
            <input type="submit" value="Send Email" class="mt-3 w-100 form-btn">
        </div>
    </form>
    <p class="mb-0 mt-3 fs-12" v-if="send">If this email is a available in our database we will send you a mail</p>
  </div>
</template>

<script>
import axios from "axios"


export default ({
  data: function() {
    return {
      active: '',
      form: {
        email:''
      },
      send: false
    }
  },
  methods: {
    forget()
    {
      axios.post('user/forget',this.form)
      this.send = true;
    }
  }
})
</script>
