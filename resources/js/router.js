import { createRouter, createWebHistory } from 'vue-router';
import AppComponent from './components/AppComponent.vue';
import chatComponent from './components/Pages/chatComponent.vue';
import AuthComponent from './components/Pages/authComponent.vue';
import userList from './components/Pages/Layouts/userListLayout';
import login from './components/Pages/Layouts/login.vue';
import register from './components/Pages/Layouts/register.vue';
import forget from './components/Pages/Layouts/forget.vue';
import reset from './components/Pages/Layouts/reset.vue';



const router = createRouter({
  history: createWebHistory(),
  routes: [
    {path:'',component:AppComponent,children:[
        {path:'',component:AuthComponent,children:[
          {path:'',component:login,name:'login'},
          {path:'/forget',component:forget,name:'forget'},
          {path:'/register',name:'register',component:register},
          {path:'/reset',name:'reset',component:reset}
        ]},
        {path:'/chat',component:chatComponent,children: [
          {path:'',component:userList},
        ]}
    ]},
  ],
});


export default router;