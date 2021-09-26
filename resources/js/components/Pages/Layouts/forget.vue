<template>
  <div class="login">
    <p class="text fs-15 fw-600 text-center pt-4 mt-4">Password Forget</p>
    <p class="text fs-12  text-center pt-4 mt-4">After submit password reset follow the instruction that has been send to your account</p>
    <form class="form">
        <div class="form-group position-relative"> 
            <label for="email" class="position-absolute" :class="{'active':email || emailtype.length > 0}"> Email Address</label>
            <input class="form-control" type="email" required autocomplete="email" id="email" @focus="email = true" @blur="email = false" v-model="emailtype"/>
            <input type="submit" value="Send Email" class="mt-3 w-100 form-btn">

        </div>
    </form>
  </div>
</template>

<script>

export default ({
  data: function() {
    return {
      email: false,
      password: false,
      emailtype: '',
      passwordtype: ''
    }
  },
})
</script>
