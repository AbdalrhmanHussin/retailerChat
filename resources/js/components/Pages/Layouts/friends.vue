<template>
    <div class="userlist px-4 pt-4">
        <h4 class="fs-20 color-sv fw-600">Friends Suggestions</h4>
        <ul class="d-flex w-100 toggler navbar-nav fs-12 color-sv mt-4 mb-4 flex-row">
            <router-link :to="{name:'suggestions'}" class="w-50" active-class="active"><li class="nav-index">Suggestions</li></router-link>
            <router-link :to="{name:'pending'}" class="w-50" active-class="active"><li class="nav-index">Pending</li></router-link>
        </ul>
        <keep-alive>
            <router-view></router-view>  
        </keep-alive>
    </div>
</template>


