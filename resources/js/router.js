import { createRouter, createWebHistory } from 'vue-router';
import AppComponent from './components/AppComponent.vue';
import chatComponent from './components/Pages/chatComponent.vue';
import AuthComponent from './components/Pages/authComponent.vue';
import userList from './components/Pages/Layouts/userListLayout';
import login from './components/Pages/Layouts/login.vue';
import register from './components/Pages/Layouts/register.vue';
import forget from './components/Pages/Layouts/forget.vue';
import reset from './components/Pages/Layouts/reset.vue';
import store from './store.js';
import friends from './components/Pages/Layouts/friends';
import pending from './components/Pages/Layouts/pending';
import suggestions from './components/Pages/Layouts/suggestions';
import request from './components/Pages/Layouts/request';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path:'',component:AppComponent,children:[
        {path:'',component:AuthComponent,children:[
          {path:'',component:login,name:'login'},
          {path:'/forget',component:forget,name:'forget'},
          {path:'/register',name:'register',component:register},
          {path:'/reset/:token/:email',name:'reset',component:reset,props:true}
        ]},
        {path:'/chat',component:chatComponent,name:'chat',children: [
          {path:'',component:userList,name:'myarea'},
          {path:'/friends',component:friends,name:'friends',children: [
            {path:'/suggestions',component:suggestions,name:'suggestions'},
            {path:'/pending',component:pending,name:'pending'}
          ]},
          {path:'/request',component:request,name:'request'}
        ]}
    ]},
  ],
});

router.beforeEach((to, from, next) => {
  if(from.name == 'login' & to.name == 'chat')
  {
    store.commit('loadingPage',true)
  }
  next()
});

router.afterEach((to,from,next)=> {
  setTimeout(()=>{
    store.commit('loadingPage', false);
  },1000)
})

export default router;