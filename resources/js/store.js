import { createApp } from 'vue'
import { createStore } from 'vuex'

// Create a new store instance.
const store = createStore({
  state: {
	 roomid: null,
	 load: 4,
	 allLoaded: false,
	 list: false,
	 activeRoom: {
		 name: 'Doris Brown',
		 status: 'active',
		 image: '/images/users/user1.jpg'
	 },
	 loadedUsers: [],
     users: [
		 {id:1,name:'Doris Brown',email:'DorisBrown@gmail.com',image:'/images/users/user1.jpg',status:'active',messages:[
			{type:'text',content:'Hey Bud how are you',sender:true,time:'9/22/2021 2:03 am',readed:true},
			{type:'text',content:'Fine,And you',sender:false,time:'9/22/2021 2:05',readed:true}
		 ]},
		 {id:2,name:'Mark Messer',email:'DorisBrown@gmail.com',image:'/images/users/user2.jpg',status:'busy',messages:[
			{type:'text',content:'Hey Bud how are you',sender:true,time:'9/22/2021 2:03',readed:false},
			{type:'text',content:'Ok i Will send it to you',sender:false,time:'9/22/2021 2:05',readed:true}
		 ]},
		 {id:3,name:'Jone Adam',email:'DorisBrown@gmail.com',image:'/images/users/user3.jpg',status:'dis',messages:[
			{type:'text',content:'Hey Bud how are you',sender:true,time:'9/22/2021 2:03',readed:true},
			{type:'text',content:'Hello',sender:false,time:'9/22/2021 13:10',readed:false}
		 ]},
		 {id:4,name:'Ken Adam1',email:'DorisBrown@gmail.com',image:'/images/users/user4.jpg',status:'offline',messages:[
			{type:'text',content:'Hey Bud how are you',sender:true,time:'9/22/2021 2:03',readed:true},
			{type:'text',content:'Hello',sender:false,time:'9/22/2021 13:10',readed:false}
		 ]},
	 ],
	 fetched: ''
  },
  getters: {
  	users: (state) => {
		state.loadedUsers.push(state.users.slice(0,14));
		console.log(state.loadedUsers)
		return state.loadedUsers[0];
	},

	fetched: (state) => {

	},

	loadfinish: (state) => {
		return state.allLoaded;
	},

	active: (state) => {
		return state.activeRoom;
	},

	load: (state) => (index) => {
		if(!state.allLoaded) {
			return state.users.slice(index,state.load);
		}
		else 
		  	return 'all';
	},

	roomid: (status) => {
		return status.roomid;
	},

	window: () => {
		console.log(window.innerWidth)
		return window;
	}

  },
	mutations: {
		fetched(state,payload) {
	       state.fetched = payload;
		},
		active: (state,id) => {
			let user = state.users.find(x => x.id === id + 1);
			state.activeRoom.name   = user.name;
			state.activeRoom.status = user.status;
			state.activeRoom.image = user.image;
			state.roomid = id;
		},
		loadUsers(state,index) {
			if(!state.allLoaded) {
				let users = state.users.slice(index,index + state.load);
				if(users.length < state.load) 
					state.allLoaded = true 
				users.forEach(user => {
					state.loadedUsers[0].push(user);
				})
			} 
		},
 	},
 	actions: {
	 	
 	}
});
export default store;