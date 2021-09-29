<template>
    <div class="loading-page d-flex flex-column justify-content-center align-items-center w-100 h-100 position-fixed top-0 left-0" >
        <transition name="loader-spinner">
            <div class="loader-spinner" v-if="this.$store.state.loading">
                <div class="load-logo">
                        <img src="/images/system/logo.svg" alt="logo">
                </div>
                <div class="loadIndicator d-flex justify-content-center align-items-center">
                    <p class="mb-0 px-2 fs-14">Loading</p>
                    <div class="loader px-2">Loading...</div>
                </div>
            </div>
       </transition>
    </div>
</template>