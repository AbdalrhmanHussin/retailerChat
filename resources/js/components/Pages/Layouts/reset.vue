<template>
  <div class="login">
    <p class="text fs-15 fw-600 text-center pt-4 mt-4">Password Reset</p>
    <p class="text fs-12  text-center pt-4 mt-4">Use a password easy to remember for you like something in your life or a date </p>
    <form class="form">
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':password || passwordtype.length > 0}">Password</label>
            <input class="form-control" type="password" id="password" required @focus="password = true" @blur="password = false" v-model="passwordtype"/>
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':cpassword || cpasswordtype.length > 0}">Confirm Password</label>
            <input class="form-control" type="password" id="password" required @focus="cpassword = true" @blur="cpassword = false" v-model="cpasswordtype"/>
        </div>
        <input type="submit" value="Reset Password" class="mt-3 w-100 form-btn">

    </form>
  </div>
</template>

<script>

export default ({
  data: function() {
    return {
      email: false,
      password: false,
      passwordtype: '',
      cpassword: false,
      cpasswordtype: ''
    }
  },
})
</script>
