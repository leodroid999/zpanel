<script>
import 'lity';
import 'lity/dist/lity.min.css';
import { useAppOptionStore } from '@/stores/app-option';
import { useUserStore } from '@/stores/userStore';
import { onMounted, ref } from "vue";
import { Modal } from "bootstrap";
import router from '../router/index';
import CardBody from '@/components/bootstrap/CardBody.vue';

const appOption = useAppOptionStore();
const userStore = useUserStore();

export default {
	data: function(){
		return{
			hostURL: 'http://development.z-panel.xyz',
			user: null,
			userInfo: null,
			username: "",
			userID: "",
			me: false,
			followed: false,
			followingFriends: null,
			friends: null,

			dataError: null,
			followers: null,

			sendDataModal:null,

			postDatas: [],
			photoTotalCount: 0,
			photoTotalCount: 0,
			
			avatarImage: null,
			bannerImage: null,
			desc: "",
			isLoaded: false,




			///////////////// Modal ////////////////
			postModal: null,
			isEditable: false,
			post_ID: null,
			postTitle: null,
			postContent: null,
			postMainPhoto: null,
			postPhotos: [],
			postVideo: '',
			galleryImageRaw: [],

			postPhotoDatas: [],
			postVideoDatas: []
		}
	},
	mounted() {
		if (this.$route != undefined)
			this.username = this.$route.params.username
		// const user_id = this.$route.query.id;
		// if(user_id) this.userID = user_id;
		this.getDataInfo()
		this.sendDataModal= new Modal(this.$refs.modalElement)
		this.postModal = new Modal(this.$refs.postModalElement)
	},
	methods:{
		closeModal:function(){
			this.sendDataModal.hide();
		},
		closePostModal: function() {
			this.postModal.hide()
		},
		getPosts: function() {
			userStore.getMyPostData(this.userID)
			.then((result) => {
				if (result.status) {
					this.postDatas = [];
					this.postPhotoDatas = [];
					this.postVideoDatas = [];

					this.photoTotalCount = 0;
					this.videoTotalCount = 0;
					result.posts.forEach(element => {
						let tempData = element.photos.replace(/'/g, '"');
						element.photos = JSON.parse(tempData);
						
						this.photoTotalCount = this.photoTotalCount + element.photos.length;

						element.isIncludingVideo = false;
						tempData = element.videos.replace(/'/g, '"');
						element.videos = JSON.parse(tempData);

						element.newCommet = ""
						element.openComment = false
						
						this.videoTotalCount = this.videoTotalCount + element.videos.length;

						this.postDatas.push(element)
						if (element.photos.length > 0)
							this.postPhotoDatas.push(element)
						if (element.videos.length > 0) 
							this.postVideoDatas.push(element)
					});

					console.log(this.postDatas)
					
				}
			})
			.catch((err) => {
				console.log(err)
			})
		},
		sendData:function(){
			if (this.avatarImage || this.bannerImage || this.desc) {
				userStore.updateUserInfo(this.avatarImage, this.bannerImage, this.desc)
				.then((result) => {
					if (result.status) {
						this.user.avatar = this.avatarImage
						this.userInfo.banner = this.bannerImage
						this.userInfo.description = this.desc

						userStore.user.avatar = this.avatarImage
					}
					this.sendDataModal.hide()
				})
				.catch((err) => {
					console.log(err)
					this.sendDataModal.hide()
				})
			}
		},
		submitPost:function(){
			if(this.postTitle == null || this.postTitle == "")
			{
				alert("Please select the post tile")
				return;
			}
			else {
				userStore.createPostData(this.postTitle, this.postContent, this.postMainPhoto, this.postPhotos, this.postVideo)
				.then((result) => {
					if (result.status) {
						this.getPosts();
					}
					this.sendDataModal.hide()
				})
				.catch((err) => {
					console.log(err)
					this.sendDataModal.hide()
				})
				this.postModal.hide();
			}
		},
		openEditModal: function() {
			if (this.userInfo) {
				this.avatarImage = this.user.avatar
				this.bannerImage = this.userInfo.banner
				this.desc = this.userInfo.description
			}
			this.sendDataModal.show();	
		},
		openCreatePostModal: function(postID) {
			this.postModal.show();
		},
		selectAvatar: function () {
			document.getElementById("fileAvatar").click()
		},
		selectBanner: function () {
			document.getElementById("fileBanner").click()
		},
		selectMainPhoto: function() {
			document.getElementById("filePostMainPhoto").click()
		},
		selectGallery: function() {
			document.getElementById("filePostGallery").click()
		},
		viewProfile: function(username) {
			window.location.replace('/user/'+username);
		},
		onSelectAvatar: function(event) {
			let rawImg;
			const file = event.target.files[0];
			const reader = new FileReader();

			reader.onloadend = () => {
			rawImg = reader.result;
				if (rawImg != "")
				{
					userStore.uploadAvatar(rawImg)
					.then((result) => {
						if (result.status) {
							this.avatarImage = result.image
						}
					})
					.catch((err) => {
						console.log(err)
					})
				}
			}
			reader.readAsDataURL(file);
		},
		onSelectBanner: function(event) {
			let rawImg;
			const file = event.target.files[0];
			const reader = new FileReader();

			reader.onloadend = () => {
			rawImg = reader.result;
				if (rawImg != "")
				{
					userStore.uploadAvatar(rawImg)
					.then((result) => {
						if (result.status) {
							this.bannerImage = result.image
						}
					})
					.catch((err) => {
						console.log(err)
					})
				}
			}
			reader.readAsDataURL(file);
		},
		onSelectMainPhoto: function(event) {
			let rawImg;
			const file = event.target.files[0];
			const reader = new FileReader();

			reader.onloadend = () => {
			rawImg = reader.result;
				if (rawImg != "")
				{
					userStore.uploadPostData(rawImg)
					.then((result) => {
						if (result.status) {
							this.postMainPhoto = result.image
						}
					})
					.catch((err) => {
						console.log(err)
					})
				}
			}
			reader.readAsDataURL(file);
		},
		onSelectGallery: function(event) {
			let rawImg;
			const files = event.target.files;
			this.galleryImageRaw = []
			for (let ii = 0; ii < files.length; ii++) {
				const reader = new FileReader();
				reader.onloadend = () => {
				rawImg = reader.result;
					if (rawImg != "")
					{
						this.galleryImageRaw.push(rawImg)
						if(ii == files.length - 1)
						{
							userStore.uploadPostGallery(this.galleryImageRaw)
							.then((result) => {
								if (result.status) {
									this.postPhotos = result.images
								}
							})
							.catch((err) => {
								console.log(err)
							})
						}
						
					}
				}
				reader.readAsDataURL(files[ii]);	
			}
		},
		getDataInfo: function(){
			this.dataError="";
			userStore.getUserInfoWithUserName(this.username)
			.then((result) => {
				this.userID = result.user.userID
				this.dataError = result.message;
				this.user = result.user
				this.me = result.me
				this.followed = result.followed
				this.followingFriends = result.followingFriends
				this.friends = result.friends
				this.followers = result.followers
				this.isLoaded = true
				this.userInfo = result.info

				this.getPosts();
			})
			.catch((err) => {
				console.log("Error", err)
				this.isLoaded = true
			})
		},
		addFriend: function(friendID) {
			if(!friendID) {
				this.dataError = "Select new friend"
				return
			}
			if (this.followed) {
				userStore.removeFriend(friendID)
				.then((result) => {
					this.followed = false;
				})
				.catch((err) => {
					console.log(err)
				})
			}
			else {
				userStore.addFriend(friendID)
				.then((result) => {
					this.followed = true;
				})
				.catch((err) => {
					console.log(err)
				})
			}
			
		},
		onSendCommit: function(post) {
			userStore.addCommentPost(post.post_id, post.newCommet)
			.then((result) => {
				post.newCommet = ""
				this.getPosts();
			})
			.catch((err) => {
				console.log(err)
			})
		},
		doLike: function(post) {
			userStore.doLike(post.post_id)
			.then((result) => {
				this.getPosts();
			})
			.catch((err) => {
				console.log(err)
			})
		},
		doShare: function(post) {
		//	if (this.me) return;
			userStore.doShare(post.post_id)
			.then((result) => {
				this.getPosts();
			})
			.catch((err) => {
				console.log(err)
			})
		}
	}
}
</script>
<template>
	<card v-if="user">
		<div class="w-100 h-300px" v-if="userInfo && userInfo.banner" :style="{backgroundImage: 'url('+hostURL+':3000/users/'+userInfo.banner+')'}" style="background-position: 100% 100%; background-repeat: no-repeat;background-position: center;background-size: cover;position: relative;" ></div>
		<div class="w-100 h-300px" v-else style="background-color: grey;" ></div>
		<card-body class="p-0">
			<!-- BEGIN profile -->
			<div class="profile">
				<!-- BEGIN profile-container -->
				<div class="profile-container">
					<!-- BEGIN profile-sidebar -->
					<div class="profile-sidebar border-end-0" style="position: relative;top: -150px;">
						<div class="desktop-sticky-top">
							<!-- style="box-shadow: 5px -5px green, -5px -5px green,5px 5px green, -5px 5px green;" -->
							<div class="profile-img w-80 h-80 border border-success" style="border-width: 5px !important;" >
								<img width="80" class="shadow-lg w-100 h-100" v-if="user.avatar" :src="hostURL+':3000/users/'+user.avatar" alt="">
								<img width="80" class="shadow-lg" v-else src="/assets/img/user/profile.jpg" alt="">
							</div>
							<!-- profile info -->
							<h4 v-if="user">{{ user.username }}</h4>
							<div class="mb-3 text-inverse text-opacity-50 fw-bold mt-n2" v-if="user"></div>
							<p v-if="userInfo">
								{{ userInfo.description }}
							</p>
							<div class="mb-1">
								<i class="fa fa-map-marker-alt fa-fw text-inverse text-opacity-50"></i> New York, NY
							</div>
							
							<div class="mb-3">
								<i class="fa fa-link fa-fw text-inverse text-opacity-50"></i> seantheme.com/hud
							</div>

							<div class="mb-5 d-flex" v-if="me">
								
							</div>
							<div class="mb-5 d-flex" v-else>
								<div class="text-center m-auto"><i class="fa fa-envelope fa-fw text-inverse text-opacity-50"></i> </div>
								<div class="flex-fill">
									<button @click="addFriend(userID)" type="button" class="btn btn-light btn-lg w-100 fs-6 ms-1 pt-1 pb-1">
										{{ followed ? 'Unfollow' : 'Volg' }}	
									</button>
								</div>
							</div>
					
							<hr class="mt-4 mb-4">
					
							<!-- people-to-follow -->
							<div class="fw-bold mb-3 fs-16px" v-if="me">People to follow</div>
							<div class="d-flex align-items-center mb-3" v-if="me" v-for="item in friends">
								<img width="30" class="rounded-circle" v-if="item.avatar" :src="hostURL+':3000/users/'+item.avatar" alt="">
								<img src="/assets/img/user/profile.jpg" v-else alt="" width="30" class="rounded-circle">
								<div class="flex-fill px-3">
									<div class="fw-bold text-truncate w-100px">
										{{ item.username }}
									</div>
									<div class="fs-12px text-inverse text-opacity-50">3.1m followers</div>
								</div>
								<a href="#" @click="viewProfile(item.username)" class="btn btn-sm btn-outline-theme fs-10px">View</a>
							</div>
						</div>
					</div>
					<!-- END profile-sidebar -->
			
					<!-- BEGIN profile-content -->
					<div class="profile-content border-start border-secondary">
						<a href="#" v-if="me" class=" fs-3 position-absolute end-0 mt-1 me-2" @click="openEditModal()" style="z-index: 999">
							<i class="fa fa-edit"></i>
						</a>
						<a href="#" v-if="me" class=" fs-3 position-absolute end-0 mt-1 me-5" @click="openCreatePostModal()" style="z-index: 999">
							<i class="fa fa-plus-circle"></i>
						</a>
						<ul class="profile-tab nav nav-tabs nav-tabs-v2 overflow-hidden">
							<li class="nav-item">
								<a href="#profile-post" class="nav-link active" data-bs-toggle="tab">
									<div class="nav-field">Posts</div>
									<div class="nav-value">{{ postDatas == null ? 0 : postDatas.length }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a href="#profile-followers" class="nav-link" data-bs-toggle="tab">
									<div class="nav-field">Following</div>
									<div class="nav-value">{{ followers == null ? 0 : followers.length }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a href="#profile-media" class="nav-link" data-bs-toggle="tab">
									<div class="nav-field">Photos</div>
									<div class="nav-value">{{ photoTotalCount }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a href="#profile-video" class="nav-link" data-bs-toggle="tab">
									<div class="nav-field">Videos</div>
									<div class="nav-value">{{ videoTotalCount }}</div>
								</a>
							</li>
							<li class="nav-item">
								<a href="#profile-followering" class="nav-link" data-bs-toggle="tab">
									<div class="nav-field">Followers</div>
									<div class="nav-value">{{ followingFriends.length }}</div>
								</a>
							</li>
						</ul>
						<div class="profile-content-container">
							<div class="row gx-4">
								<div class="col-xl-8">
									<div class="tab-content p-0">
										<!-- BEGIN tab-pane -->
										<div class="tab-pane fade show active" id="profile-post">
											<card class="mb-3" v-for="post in postDatas">
												<card-body>
													<!-- post header -->
													<div class="d-flex align-items-center mb-3">
														<a href="#" v-if="post.avatar"><img :src="hostURL+':3000/users/'+post.avatar" alt="" width="50" class="rounded-circle"></a>
														<a href="#" v-else><img src="/assets/img/user/profile.jpg" alt="" width="50" class="rounded-circle"></a>
														
														<div class="flex-fill ps-2">
															<div class="fw-bold"><a href="#" class="text-decoration-none fs-5">{{ post.post_title }}</a></div>
															<div class="small text-inverse text-opacity-50">{{ post.post_created }}</div>
														</div>
													</div>
						
													<!-- post content -->
													<p class="w-100" v-if="post.post_content">
														{{ post.post_content }}
													</p>
													<div class="profile-img-list">
														<div v-if="post.main_photo" class="profile-img-list-item main">
															<a :href="hostURL+':3000/posts/'+post.main_photo" data-lity class="profile-img-list-link">
																<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+post.main_photo+')'}"></span>
															</a>
														</div>
														<div class="profile-img-list-item" v-if="post.photos.length > 0">
															<a :href="hostURL+':3000/posts/'+post.photos[0]" data-lity class="profile-img-list-link">
																<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+post.photos[0]+')'}"></span>
															</a>
														</div>
														<div class="profile-img-list-item" v-if="post.photos.length > 1">
															<a :href="hostURL+':3000/posts/'+post.photos[1]" data-lity class="profile-img-list-link">
																<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+post.photos[1]+')'}"></span>
															</a>
														</div>
														<div class="profile-img-list-item" v-if="post.photos.length > 2">
															<a :href="hostURL+':3000/posts/'+post.photos[2]" data-lity class="profile-img-list-link">
																<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+post.photos[2]+')'}"></span>
															</a>
														</div>
														<div class="profile-img-list-item with-number" v-if="post.photos.length > 3">
															<a :href="hostURL+':3000/posts/'+ post.photos[3]" data-lity class="profile-img-list-link">
																<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+photo+')'}"></span>
																<div class="profile-img-number" v-if="post.photos.length > 4">+{{ post.photos.length - 4 }}</div>
															</a>
														</div>
													</div>

													<div class="ratio ratio-16x9 mt-1" v-if="post.videos" v-for="item_video in post.videos">
														<iframe :src="item_video"></iframe>
													</div>

													<div class="mt-5">
														<div class="d-flex align-items-center mt-2" v-for="comment in post.comments">
															<div class="text-center">
																<img width="35" class="rounded-circle" v-if="comment.avatar" :src="hostURL+':3000/users/'+comment.avatar" alt="">
																<img width="35" class="rounded-circle" v-else src="/assets/img/user/profile.jpg" alt="">
																<div>{{ comment.username }}</div>
															</div>
															<div class="flex-fill ps-2">
																<div class="position-relative d-flex align-items-center">
																	<div class="ms-2">
																		<p>{{ comment.content }}</p>
																	</div>
																	<div class="position-absolute end-0 text-center">
																		{{ comment.comment_created }}
																	</div>
																</div>
															</div>
														</div>
													</div>

													<hr class="mb-1 mt-2">
								
													<!-- post action -->
													<div class="row text-center fw-bold">
														<div class="col">
															<a href="#" v-if="post.isLike" class="text-inverse text-opacity-50 text-decoration-none d-block p-2" @click="(event) => { event.preventDefault(); doLike(post) }">
																<i class="fas fa-thumbs-up me-1 d-block d-sm-inline"></i> Recommended
															</a>
															<a href="#" v-else class="text-inverse text-opacity-50 text-decoration-none d-block p-2" @click="(event) => { event.preventDefault(); doLike(post) }">
																<i class="far fa-thumbs-up me-1 d-block d-sm-inline"></i> Likes
															</a>
														</div>
														<div class="col">
															<a href="#" v-if="post.openComment" class="text-inverse text-opacity-50 text-decoration-none d-block p-2" @click="(event) => { event.preventDefault(); post.openComment = !post.openComment}">
																<i class="fas fa-comment me-1 d-block d-sm-inline"></i> Comment
															</a>
															<a href="#" v-else class="text-inverse text-opacity-50 text-decoration-none d-block p-2" @click="(event) => { event.preventDefault(); post.openComment = !post.openComment}">
																<i class="far fa-comment me-1 d-block d-sm-inline"></i> Comment
															</a>
														</div>
														<div class="col">
															<a href="#" v-if="post.isShare" class="text-inverse text-opacity-50 text-decoration-none d-block p-2" @click="(event)=>{ event.preventDefault(); doShare(post) }">
																<i class="fa fa-share me-1 d-block d-sm-inline"></i> Shared
															</a>
															<a href="#" v-else class="text-inverse text-opacity-50 text-decoration-none d-block p-2 text-light" @click="(event)=>{ event.preventDefault();doShare(post) }">
																<i class="fa fa-share me-1 d-block d-sm-inline"></i> Share
															</a>
														</div>
													</div>

													<hr class="mb-2 mt-1">

													<div class="d-flex align-items-center" v-if="post.openComment">
														<img width="35" class="rounded-circle" v-if="user.avatar" :src="hostURL+':3000/users/'+user.avatar" alt="">
														<img width="35" class="rounded-circle" v-else src="/assets/img/user/profile.jpg" alt="">
														<div class="flex-fill ps-2">
															<div class="position-relative d-flex align-items-center">
																<input type="text" class="form-control rounded-pill bg-white bg-opacity-15" v-model="post.newCommet" style="padding-right: 120px;" placeholder="Write a comment...">
																<div class="position-absolute end-0 text-center">
																	<!-- <a href="#" class="text-inverse text-opacity-50 me-2"><i class="fa fa-smile"></i></a>
																	<a href="#" class="text-inverse text-opacity-50 me-2"><i class="fa fa-camera"></i></a>
																	<a href="#" class="text-inverse text-opacity-50 me-2"><i class="fa fa-video"></i></a> -->
																	<a href="#" v-if="post.newCommet" class="text-inverse text-opacity-50 me-3" @click="(event) => { event.preventDefault(); onSendCommit(post) }"><i class="fa fa-paper-plane"></i></a>
																	<a href="#" v-else class="text-inverse text-opacity-50 me-3 text-light"><i class="fa fa-paper-plane"></i></a>
																</div>
															</div>
														</div>
													</div>
													
												</card-body>
											</card>
										</div>
										<!-- END tab-pane -->
							
										<!-- BEGIN tab-pane -->
										<div class="tab-pane fade" id="profile-followers">
											<div class="list-group">
												<div class="list-group-item d-flex align-items-center" v-for="item in followers">
													<img width="50" class="rounded-sm ms-n2" v-if="item.avatar" :src="hostURL+':3000/users/'+item.avatar" alt="">
													<img src="/assets/img/user/profile.jpg" v-else alt="" width="50" class="rounded-sm ms-n2">
													<div class="flex-fill px-3">
														<div><a href="#" class="text-inverse fw-bold text-decoration-none">{{ item.username }}</a></div>
														<div class="text-inverse text-opacity-50 fs-13px">North Raundspic</div>
													</div>
													<a @click="viewProfile(item.username)" class="btn btn-sm btn-outline-theme fs-10px">View</a>
												</div>
											</div>
											<div class="text-center p-3"><a href="#" class="text-inverse text-decoration-none">Show more <b class="caret"></b></a></div>
										</div>
										<!-- END tab-pane -->
							
										<!-- BEGIN tab-pane -->
										<div class="tab-pane fade" id="profile-media">
											<card class="mb-3" v-for="post in postPhotoDatas">
												<card-header class="fw-bold bg-transparent">{{ post.post_created }}</card-header>
												<card-body>
													<div class="widget-img-list">
														<div class="widget-img-list-item" v-for="photo in post.photos">
															<a :href="hostURL+':3000/posts/'+ photo" data-lity>
																<span class="img" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+ photo +')'}"></span>
															</a>
														</div>
													</div>
												</card-body>
											</card>
											<div class="text-center p-3 d-none"><a href="#" class="text-inverse text-decoration-none">Show more <b class="caret"></b></a></div>
										</div>
										<!-- END tab-pane -->
							
										<!-- BEGIN tab-pane -->
										<div class="tab-pane fade" id="profile-video">
											<card class="mb-3" v-for="post in postVideoDatas">
												<card-header class="fw-bold bg-transparent">{{ post.post_created }}</card-header>
												<card-body>
													<div class="row gx-1">
														<div class="col-md-4 col-sm-6 mb-1" v-for="video in post.videos">
															<a :href="video" data-lity="">
																<img src="https://img.youtube.com/vi/RQ5ljyGg-ig/mqdefault.jpg" alt="" class="d-block w-100">
															</a>
														</div>
													</div>
												</card-body>
											</card>
										</div>
										<!-- END tab-pane -->

										<!-- BEGIN tab-pane -->
										<div class="tab-pane fade" id="profile-followering">
											<div class="list-group">
												<div class="list-group-item d-flex align-items-center" v-for="item in followingFriends">
													<img width="50" class="rounded-sm ms-n2" v-if="item.avatar" :src="hostURL+':3000/users/'+item.avatar" alt="">
													<img src="/assets/img/user/profile.jpg" v-else alt="" width="50" class="rounded-sm ms-n2">
													<div class="flex-fill px-3">
														<div><a href="#" class="text-inverse fw-bold text-decoration-none">{{ item.username }}</a></div>
														<div class="text-inverse text-opacity-50 fs-13px">North Raundspic</div>
													</div>
													<a @click="viewProfile(item.username)" class="btn btn-sm btn-outline-theme fs-10px">View</a>
												</div>
											</div>
											<div class="text-center p-3"><a href="#" class="text-inverse text-decoration-none">Show more <b class="caret"></b></a></div>
										</div>
										<!-- END tab-pane -->
									</div>
								</div>
								<div class="col-xl-4">
									<div class="desktop-sticky-top d-none d-lg-block">
										<card class="mb-3">
											<div class="list-group list-group-flush">
												<div class="list-group-item fw-bold px-3 d-flex">
													<span class="flex-fill">Trends for you</span> 
													<a href="#" class="text-inverse text-opacity-50"><i class="fa fa-cog"></i></a>
												</div>
												<div class="list-group-item px-3">
													<div class="text-inverse text-opacity-50"><small><strong>Trending Worldwide</strong></small></div>
													<div class="fw-bold mb-2">#BreakingNews</div>
													<a href="#" class="card text-inverse text-decoration-none mb-1">
														<div class="card-body">
															<div class="row no-gutters">
																<div class="col-md-8">
																	<div class="small text-inverse text-opacity-50 mb-1 mt-n1">Space</div>
																	<div class="h-40px fs-13px overflow-hidden mb-n1">Distant star explosion is brightest ever seen, study finds</div>
																</div>
																<div class="col-md-4 d-flex">
																	<div class="h-100 w-100" style="background: url(@/assets/img/gallery/news-1.jpg) center; background-size: cover;"></div>
																</div>
															</div>
														</div>
														<div class="card-arrow">
															<div class="card-arrow-top-left"></div>
															<div class="card-arrow-top-right"></div>
															<div class="card-arrow-bottom-left"></div>
															<div class="card-arrow-bottom-right"></div>
														</div>
													</a>
													<div><small class="text-inverse text-opacity-50">1.89m share</small></div>
												</div>
												<div class="list-group-item px-3">
													<div class="fw-bold mb-2">#TrollingForGood</div>
													<div class="fs-13px mb-1">Be a good Troll and spread some positivity on HUD today.</div>
													<div><small class="text-inverse text-opacity-50"><i class="fa fa-external-link-square-alt"></i> Promoted by HUD Trolls</small></div>
												</div>
												<div class="list-group-item px-3">
													<div class="text-inverse text-opacity-50"><small><strong>Trending Worldwide</strong></small></div>
													<div class="fw-bold mb-2">#CronaOutbreak</div>
													<div class="fs-13px mb-1">The coronavirus is affecting 210 countries around the world and 2 ...</div>
													<div><small class="text-inverse text-opacity-50">49.3m share</small></div>
												</div>
												<div class="list-group-item px-3">
													<div class="text-inverse text-opacity-50"><small><strong>Trending in New York</strong></small></div>
													<div class="fw-bold mb-2">#CoronavirusPandemic</div>
													<a href="#" class="card mb-1 text-inverse text-decoration-none">
														<div class="card-body">
															<div class="row no-gutters">
																<div class="col-md-8">
																	<div class="fs-12px text-inverse text-opacity-50 mt-n1">Coronavirus</div>
																	<div class="h-40px fs-13px overflow-hidden mb-n1">Coronavirus: US suspends travel from Europe</div>
																</div>
																<div class="col-md-4 d-flex">
																	<div class="h-100 w-100" style="background: url(@/assets/img/gallery/news-2.jpg) center; background-size: cover;"></div>
																</div>
															</div>
														</div>
														<div class="card-arrow">
															<div class="card-arrow-top-left"></div>
															<div class="card-arrow-top-right"></div>
															<div class="card-arrow-bottom-left"></div>
															<div class="card-arrow-bottom-right"></div>
														</div>
													</a>
													<div><small class="text-inverse text-opacity-50">821k share</small></div>
												</div>
												<a href="#" class="list-group-item list-group-action text-center">
													Show more
												</a>
											</div>
										</card>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END profile-content -->
				</div>
				<!-- END profile-container -->
			</div>
			<!-- END profile -->
		</card-body>
	</card>
	<card class="pt-5 pb-5 fs-4" v-else-if="isLoaded" >
		<Text class="text-center">404</Text>
		<Text class="text-center">Not found user</Text>
	</card>

	<div class="modal fade" id="modalSendData" ref="modalElement">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit profile</h5>
					<button type="button" class="btn-close" @click="closeModal" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">

					<p>Banner & Avatar</p>
					<div class="row">

						<div class="col-md-6">
							<div class="profile-img w-100 h-100 pt-3 pb-3 ps-3 pe-3 text-center" >
								<img class="shadow-lg w-100 h-200px" v-if="bannerImage" :src="hostURL+':3000/users/'+bannerImage" alt="">
								<img class="shadow-lg w-100" v-else src="/assets/img/user/profile.jpg" alt="">
								<input id="fileBanner" type="file" @change="(event) => { onSelectBanner(event) }" accept="image/png, image/gif, image/jpeg" hidden>
								<button v-if="me" @click="selectBanner()" class="btn btn-light btn-sm mt-2">Select Banner</button>
							</div>
						</div>

						<div class="col-md-6">
							<div class="profile-img w-100 h-100 pt-3 pb-3 ps-3 pe-3 text-center" >
								<img class="shadow-lg w-100 h-200px" v-if="avatarImage" :src="hostURL+':3000/users/'+avatarImage" alt="">
								<img class="shadow-lg w-100" v-else src="/assets/img/user/profile.jpg" alt="">
								<input id="fileAvatar" type="file" @change="(event) => { onSelectAvatar(event) }" accept="image/png, image/gif, image/jpeg" hidden>
								<button v-if="me" @click="selectAvatar()" class="btn btn-light btn-sm mt-2">Select Avatar</button>
							</div>
						</div>

					</div>
					
					<!-- textarea -->
					<p>Description</p>
					<textarea class="form-control" rows="3" v-model="desc"></textarea>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-default" @click="closeModal">Close</button>
					<button type="button" class="btn btn-outline-theme" @click="sendData">Update</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalSendData" ref="postModalElement">
		<div class="modal-dialog modal-lg profile">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ isEditable ? 'Edit Post' : 'Create Post' }}</h5>
					<button type="button" class="btn-close" @click="closePostModal" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">

					<card class="mb-3 profile-content">
						<card-body>

							<!-- post content -->
							<p>Title*</p>
							<input type="text" class="form-control mb-3" v-model="postTitle">

							<input id="filePostMainPhoto" type="file" @change="(event) => { onSelectMainPhoto(event) }" accept="image/png, image/gif, image/jpeg" hidden>
							<button v-if="me" @click="selectMainPhoto()" class="btn btn-light btn-sm mb-2">Select main photh</button>

							<input id="filePostGallery" type="file" @change="(event) => { onSelectGallery(event) }" accept="image/png, image/gif, image/jpeg" multiple hidden>
							<button v-if="me" @click="selectGallery()" class="btn btn-light btn-sm ms-2 mb-2">Select Gallery</button>

							<div class="profile-img-list" v-if="me">
								<div class="profile-img-list-item main">
									<a href="@/assets/img/gallery/gallery-5.jpg" data-lity class="profile-img-list-link">
										<span class="profile-img-content" v-if="postMainPhoto" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+postMainPhoto+')'}"></span>
										<span class="profile-img-content" v-else style="background-image: url(@/assets/img/gallery/gallery-5.jpg)"></span>
									</a>
								</div>
								<div class="profile-img-list-item main ratio ratio-16x9">
									<iframe v-if="postVideo" :src="postVideo"></iframe>
								</div>
								<div class="profile-img-list-item w-50">
									<span>Video Link</span>
									<input type="text" class="form-control w-100" v-model="postVideo">
								</div>
							</div>
							<div class="profile-img-list">
								<div class="profile-img-list-item" v-for="item in postPhotos">
									<a :href="hostURL+':3000/posts/'+item" data-lity class="profile-img-list-link">
										<span class="profile-img-content" :style="{backgroundImage: 'url('+hostURL+':3000/posts/'+item+')'}"></span>
									</a>
								</div>
							</div>

							<!-- textarea -->
							<p class="mt-2">Content</p>
							<textarea class="form-control" rows="3" v-model="postContent"></textarea>
						</card-body>
					</card>

					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-default" @click="closePostModal">Close</button>
					<button type="button" class="btn btn-outline-theme" @click="submitPost">{{ isEditable ? 'Edit' : 'Create' }}</button>
				</div>
			</div>
		</div>
	</div>
</template>