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
     <form class="form">
        <div class="form-group position-relative"> 
            <label for="email" class="position-absolute" :class="{'active':email || emailtype.length > 0}"> Email Address</label>
            <input class="form-control" type="email" required autocomplete="email" id="email" @focus="email = true" @blur="email = false" v-model="emailtype"/>
        </div>
        <div class="form-group position-relative mt-4"> 
            <label for="password" class="position-absolute" :class="{'active':password || passwordtype.length > 0}">Password</label>
            <input class="form-control" type="password" id="password" required @focus="password = true" @blur="password = false" v-model="passwordtype"/>
        </div>
        <div class="form-group d-flex align-items-center">
          <input type="checkbox" class="remember position-relative">
          <label for="" class="fs-11 fw-600">Remember me</label>
        </div>
        <input type="submit" value="Login Now" class="mt-3 mb-3 w-100 form-btn">
        <div class="actions">
           <router-link :to="{name:'register'}" class="fs-11 color-sv d-block">Not a member? Register now</router-link>
           <router-link :to="{name:'forget'}" class="fs-11 color-sv d-block mt-2">Forget Password</router-link>
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
