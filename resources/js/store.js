import axios from 'axios';
import { createApp } from 'vue'
import { createStore } from 'vuex'
import { useToast } from "vue-toastification";


// Create a new store instance.
const store = createStore({
  state: {
	 roomid: null,
	 load: 4,
	 loading: false,
	 allLoaded: false,
	 screen: window.small,
	 model: '',
	 model_overlay: false,
	 list: false,
	 suggestions: [],
	 pending:[],
	 requestArr:[],
	 activeRoom: {
		 name: 'Doris Brown',
		 status: 'active',
		 image: '/images/users/user1.jpg'
	 },
	 auth: 0,
	 loadedUsers: [],
	 errors: [],
	 defaultUsersSettings: [],
     users: [],
	 fetched: '',
	 user: []
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

	loadstate: (status) => {
		return status.loading;
	},

	window: () => {
		console.log(window.innerWidth)
		return window;
	},

	suggestions: (status) => {
		return status.suggestions;
	},

	pending: (status) => {
		return status.pending;
	},
    
	window: () => {
		return window;
	},

	request(state)
	{
		return state.requestArr
	},

	getUser(state)
	{
		return state.user;
	},

	defaultUsers(state)
	{
		return state.defaultUsersSettings;
	}


  },
	mutations: {
		listResize(state) {
			state.listGetter();
		},
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

		loadingPage(state,load) {
			state.loading = load;
		},

		suggestions(state,payload)
		{
			state.suggestions = payload;
			console.log(state.suggestions);
		},

		pending(state,index) {
			state.pending.push(state.suggestions[index]);
			state.suggestions.splice(index,1);
		},

		request(state,payload)
		{
			state.requestArr = payload;
		},

 	},
 	actions: {
	 	getUserData({state})
		{
			axios.get('user/getuser').then((res)=>{
				console.log(res.data);
				state.user  = {
					email: res.data[0]['email'],
					name: res.data[0]['name'],
					image: res.data[0]['image'],
					status: res.data[0]['status']
				}
				if(res.data[0]['friends'].length)
				{
					state.defaultUsersSettings = res.data[0]['friends'];
					state.users = res.data[0]['friends'];
				}
			});

		},

		getSuggestions({commit,state},data={start:0,end:10,search:''})
		{
			axios.post('/user/notfriends',data).then((res)=>{
					commit('suggestions',res.data);
				});
		},
		friendRequest({commit,state},data)
		{
			axios.post('/user/request',{
				id: data.id
			})
			commit('pending',data.index);
		},

		getrequest({commit,state})
		{
			axios.post('/user/getrequest')
				.then((res)=>{
					state.request = res.data.payload[0]['friendRequest'];
					commit('request',res.data.payload[0]['friendrequest']);
				});
		},

		pending({commit,state},data)
		{
			axios.post('/user/pending')
			    .then((res)=>{
					if(res.data.payload.length > 0) {
						state.pending = res.data.payload[0]['pending_users'];	
					}
				})
		},

		removepending({commit,state},data = {index: index,id: id})
		{
			axios.post('/user/removepending',{friendid: data.id})
			state.pending.splice(data.index,1);
		},

		submitrequest({state},data = {id:'',action:'',index:''})
		{
			state.requestArr.splice(data.index,1);
			axios.post('user/submitrequest',{id:data.id,action:data.action});
		},

		recieverequest({state},user)
		{
			state.requestArr.push(user['user']);
		},

		Auth({state})
		{
			if(state.auth == 0) 
			{
				return new Promise((resolve, reject) => {
					axios.get('/user/authorized').then((res) => {
						state.auth = 1;
						resolve(res.data)
					});
				})
			} else return true;
		},
	
		modify({state},payload = {})
		{
			console.log(payload.update);
			axios.post('/user/update',payload).then((res)=>{
				const toast = useToast();
				
				if(res.data.message)
				{
					if(payload.update == 'name')
					{
						toast.success("Your name has been changed", {
							timeout: 2000
						});
					} 
					else if(payload.update == 'password')
					{
						alert('here');
						toast.success("Your password has been changed", {
							timeout: 2000
						});
					}
				} 
				else 
				{
					state.errors = res.data.payload
					console.log(state.errors);
				}

			})
		}
 	}
});
export default store;