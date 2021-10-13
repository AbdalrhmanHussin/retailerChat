import axios from 'axios';
import { createApp } from 'vue'
import { createStore } from 'vuex'
import { useToast } from "vue-toastification";


// Create a new store instance.
const store = createStore({
  state: {
	roomid: null,
	userid: null,
	loadMess: false,
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
	user: [],
	firstLoad: true
  },
  getters: {
  	users: (state) => {
		state.loadedUsers.push(state.users.slice(0,14));
		console.log(state.users);
		return state.users;
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

	userid(state)
	{
		return state.userid;
	},

	defaultUsers(state)
	{
		return state.defaultUsersSettings;
	},

	findFriend(state)
	{
		let user = state.users.find((x) => {
			return x.id == state.userid;
		});
		return user;
	},


  },
	mutations: {

		listResize(state) {
			state.listGetter();
		},
		fetched(state,payload) {
	       state.fetched = payload;
		},

		active: (state,id) => {
			let user = state.users.findIndex(x => x.id === id);
			state.activeRoom.name   = state.users[user].name;
			state.activeRoom.status = state.users[user].status;
			state.activeRoom.image  = state.users[user].image;
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
			axios.get('auth/getuser').then((res)=>{
				state.user  = {
					id: res.data[0]['id'],
					email: res.data[0]['email'],
					name: res.data[0]['name'],
					image: res.data[0]['image'],
					status: res.data[0]['status']
				}

				if(res.data[0]['friends'].length)
				{
					state.defaultUsersSettings = res.data[0]['friends'];

					res.data[0]['friends'].forEach((e) => {
						e['typing'] = false
						e['rooms'][0]['messages'] = []
					});

					state.users = res.data[0]['friends'];
				}

			});

		},

		getSuggestions({commit,state},data={start:0,end:10,search:''})
		{
			axios.post('/user/aquariance',data).then((res)=>{
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
			axios.post('user/submitrequest',{id:data.id,action:data.action}).then((res) => {
				console.log(res.data);
				state.user.push(res.data);
			});
		},

		recieverequest({state},user)
		{
			let userReq = state.requestArr.find((x) => {
				return x.id == user['user'].id;
			});


			if(!userReq)
			{
				state.requestArr.push(user['user']);
				this.soundsNotification('fr');
			}
		},

		Auth({state})
		{
			if(state.auth == 0) 
			{
				return new Promise((resolve, reject) => {
					axios.get('/auth/authorized').then((res) => {
						console.log(res);
						if(res.data)
						{
							state.auth = 1;
						}  
						resolve(res.data)
					});
				})
			} else return true;
		},
	
		modify({state},payload = {})
		{
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
				}

			})
		},

		sendMessage({state,getters},message)
		{
			axios.post('message/send',message)
			let messages = {
				type: 'text',
				content: message.content,
				readed: true,
				user_id: getters.getUser.id,
				created_at: Date.now()
			}

			getters.findFriend['rooms'][0]['messages'].push(messages);
			getters.findFriend['rooms'][0]['latest_message'] = messages;
		},


		roomRender({state,getters})
		{
			let from = getters.findFriend['rooms'][0]['messages'].length;
			
			axios.post('/message/room',{room: state.roomid,from:from}).then((res) => {
				let user = state.users.find((x) => {
					return x.id == state.userid
				});

				res.data[0]['get_messages'].forEach((x) => {
					user['rooms'][0]['messages'].unshift(x);
				})

			});
		},

		

 	}
});
export default store;