<template>
   <div class="auth d-flex">
        <div class="w-75 auth" style="background:url('/images/Auth/auth.png')">
            <div class="overlay w-100 h-100 d-flex flex-column justify-content-end p-4">
            </div>
        </div>
        <div class="w-25 login m-4">
            <div class="logo w-100 d-flex justify-content-center">
                <img src="/images/system/logo.svg" alt="logo">
            </div>
            <router-view></router-view>
        </div>
   </div>
</template>