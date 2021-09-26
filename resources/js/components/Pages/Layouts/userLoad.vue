<template>
    <div class="user-load d-flex" v-for="(item,index) in 10" :key="index">
        <div class="load-img"></div>
        <div class="load-info">
            <div class="load-name"></div>
            <div class="load-mssg"></div>
        </div>
    </div>
</template>