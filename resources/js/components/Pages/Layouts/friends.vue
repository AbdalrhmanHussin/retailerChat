<template>
    <div class="userlist px-4 pt-4">
        <h4 class="fs-20 color-sv fw-600">Friends Suggestions</h4>
        <form class="form mt-3 position-relative mb-3" @submit.prevent>
            <label class="ri-search-line position-absolute top-0 color-sv p-2 fs-14"></label>
            <input type="text" placeholder="Search users" class="form-control search fs-13 color-sv">
        </form>
        <ul class="d-flex w-100 toggler navbar-nav fs-12 color-sv mt-4 mb-4">
            <router-link :to="{name:'suggestions'}" class="w-50" active-class="active"><li class="nav-index">Suggestions</li></router-link>
            <router-link :to="{name:'pending'}" class="w-50" active-class="active"><li class="nav-index">Pending</li></router-link>
        </ul>
        <keep-alive>
            <router-view></router-view>  
        </keep-alive>
    </div>
</template>


