<template>
    <div>
        <router-view></router-view>
    </div>
    <loading-screen v-if="this.$store.state.loading"></loading-screen>
</template>
<script>
import loadingScreenVue from './Pages/Layouts/loadingScreen.vue'
export default {
    components: {
        'loading-screen': loadingScreenVue
    },
}
</script>