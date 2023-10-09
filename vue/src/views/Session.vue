<script>
import navscrollto from '@/components/app/NavScrollTo.vue';
import { ScrollSpy } from 'bootstrap';
import { useSessionStore } from '@/stores/sessionStore';
import { useAppVariableStore } from '@/stores/app-variable';
import { onMounted, ref } from "vue";
import { Modal } from "bootstrap";
import jsVectorMap from 'jsvectormap';
import 'jsvectormap/dist/maps/world.js';
import 'jsvectormap/dist/css/jsvectormap.min.css';

const appVariable = useAppVariableStore();
const sessionStore = useSessionStore();
const modalElement = null;

export default {
	components: {
		navScrollTo: navscrollto
	},
	data: function(){
		return {
			activeModal:null,
			activeModalName:null,
			activeModalToken:null,
			sendDataModal:null,
			sendError:false,
			modalShowSendWithError:false,
			fields:{}
		}
	},
	computed:{
		sessionId:function(){
			return this.$route.params.id;
		},
		session:function(){
			return sessionStore.currentSession
		},
		panelOptions:function(){
			if(sessionStore.currentSession && sessionStore.currentSession.options){
				let options = sessionStore.currentSession.options
				let filteredOptions=options.filter(option => {
					return !option.isMainRow || option.isMainRow === "";
				})
				return filteredOptions
			}
			return []
		},
		mainOptions:function(){
			if(sessionStore.currentSession && sessionStore.currentSession.options){
				let options = sessionStore.currentSession.options
				let filteredOptions=options.filter(option => {
					return option.isMainRow
				})
				return filteredOptions
			}
			return []
		},
		modalFields:function(){
			if(this.activeModal){
				let fields=[]
				let fieldIds=this.activeModal.replace("pushdata","").split("-")
				for(let field of fieldIds){
					fields.push({id:parseInt(field),label:sessionStore.currentSession["dataName"+field]})
				}
				return fields
			}
			return []
		},
		statusColor:function(){
			if(sessionStore.currentSession){
				try{
					let ts=parseInt(sessionStore.currentSession.Last_Online);
					let d=new Date(ts*1000);
					let now=new Date();
					let seconds = parseInt(Math.abs(now.getTime() - d.getTime())/1000);
					if(seconds <= 5){
						return "color:lightgreen"
					}
				}
				catch(e){
					return "color:rgb(176, 176, 176)"
				}
			}
			return "color:rgb(176, 176, 176)"
		},
		mainFieldName:function(){
			if(!sessionStore.currentSession){
				return "..."
			}
			let field=sessionStore.currentSession.MainField
			if(!field){
				return "-"
			}
			return field.replace("_","")
		},
		mainField:function(){
			if(!sessionStore.currentSession){
				return "..."
			}
			let field=sessionStore.currentSession.MainField
			if(!field){
				return "-"
			}
			return this.sessionProp(field,"...");
		},
	},
	methods:{
		closeModal:function(){
			this.sendDataModal.hide();
		},
		onOptionClick:function(ev){
			let btn=ev.currentTarget;
			if(btn.dataset && btn.dataset.token){
				let token = btn.dataset.token;
				let selectedToken = sessionStore.currentSession.options.find(option=>{
					return option.tokenName === token;
				})
				if(selectedToken){
					if(selectedToken.tokenButtonType && selectedToken.tokenButtonType.includes("pushdata")){
						this.activeModal=selectedToken.tokenButtonType;
						this.activeModalName=selectedToken.tokenButtonName;
						this.activeModalToken=selectedToken.tokenName;
						this.modalShowSendWithError=(selectedToken.SendTokenWithError == "1" || selectedToken.SendTokenWithError == 1);
						this.fields={}
						let fieldIds=selectedToken.tokenButtonType.replace("pushdata","").split("-")
						for(let field of fieldIds){
							this.fields[field]="";
						}
						this.sendDataModal.show()
						return;
					}
					let sendError = (selectedToken.SendTokenWithError == "1" || selectedToken.SendTokenWithError == 1);
					sessionStore.updateRedirect(selectedToken.tokenName,sendError);
				}
			}
		},
		sendData:function(){
			sessionStore.sendData(this.fields,this.activeModalToken,this.sendError)
			this.sendDataModal.hide()
		},
		onlineStatus:function(str){
			try{
				let ts=parseInt(str);
				let d=new Date(ts*1000);
				let now=new Date();
				let seconds = parseInt(Math.abs(now.getTime() - d.getTime())/1000);
				if(seconds <= 5){
					return "Online"
				}
				if(seconds > 5 && seconds<60){
					return "Offline, seen just now"
				}
				if(seconds >= 60 && seconds<3600){
					let minutes=Math.round(seconds/60)
					return "Offline, seen "+minutes+" minutes ago"
				}
				if(seconds>=3600){
					return "Offline, seen along time ago"
				}
			}
			catch(e){
				return "-"
			}
		},
		formatDate:function(str){
			try{
				let ts=parseInt(str);
				let d=new Date(ts*1000)
				return d.toLocaleString();
			}
			catch(e){
				return "-"
			}
		},
		loadSession:function($sessionId){
			sessionStore.searchSession($sessionId)
		},
		getMarkerData() {
			let results=[]
			if(sessionStore.currentSession){
				let session = sessionStore.currentSession;
				if( session.lat && session.lon){
					results.push({name:"",coords:[parseFloat(session.lat),parseFloat(session.lon)]})
				}
			}
			return results;
		},
		sessionProp:function(key,placeholder,notSetValue,formatter){
			if(sessionStore.currentSession){
				let session=sessionStore.currentSession
				if(key in session){
					if(session[key]){
						if(formatter){
							return formatter(session[key])
						}
						return session[key]
					}
					else{
						if(notSetValue){
							return notSetValue
						}
						return "None/Not set";
					}
				}
			}
			return placeholder;
		},
		btnStyle:function(option){
			if(option.tokenButtonType=="error"){
				return "btn-h btn gap btn-outline-danger btn-sm"
			}
			return "btn-h btn gap btn-outline-theme btn-sm"
		},
		async renderMap() {
			let map_name="world";
			if(sessionStore.currentSession && sessionStore.currentSession.country){
				map_name=sessionStore.currentSession.country.toLowerCase()+"_merc"
				let map_file=sessionStore.currentSession.country.toLowerCase()+"-merc"
				await import("/public/assets/js/jquery-jvectormap-"+map_file+".js")
			}
			document.getElementById('map-container').innerHTML = '<div id="map"></div>';
			let markers=this.getMarkerData();
			var map = new jsVectorMap({
				selector: '#map',
				map: map_name,
				zoomButtons: true,
				normalizeFunction: 'polynomial',
				hoverOpacity: 0.5,
				hoverColor: false,
				zoomOnScroll: false,
				series: { regions: [{ normalizeFunction: 'polynomial' }] },
				labels: { markers: { render: (marker) => marker.name } },
				focusOn: { x: 0.5, y: 0.5, scale: 1 },
				markers,
				markerStyle: { initial: { fill: appVariable.color.theme, stroke: 'none', r: 5 }, hover: { fill: appVariable.color.theme } },
				markerLabelStyle: {
					initial: {
						fill: 'rgb(200,200,200)',
						stroke: 'rgb(170,170,170)',
						strokeWidth: '0.5px'
					}
				},
				regionStyle: {
					 initial: { 
						fill: 'rgba(255, 255, 255, 0.75)', fillOpacity: 0.35, stroke: 'none', strokeWidth: 0.4, strokeOpacity: 1
					 },
				 	hover: { fillOpacity: 0.5 },
					selected: { 
						fill: appVariable.color.theme, fillOpacity: 0.6, stroke: 'none', strokeWidth: 0.4, strokeOpacity: 1
					 },
				},
				backgroundColor: 'transparent',
			});
		},
	},
	mounted() {
		new ScrollSpy(document.body, {
			target: '#sidebar-bootstrap',
			offset: 200
		})
		if(this.$route.params.id){
			this.loadSession(this.$route.params.id)
		}
		this.sendDataModal= new Modal(this.$refs.modalElement)
		this.renderMap();
		sessionStore.$subscribe((mutation)=>{
			let ev=mutation.events;
			if(ev.key=="currentSession"){
				if(!ev.oldValue){
					this.renderMap();
				}
				if(ev.oldValue && ev.oldValue.SessionId!= ev.newValue.SessionId){
					this.renderMap();
				}
			}
		})
		window.addEventListener("resize", (event) => {
			this.$nextTick(() => {
				setTimeout(() => {
					this.renderMap();
				}, 50);
			});
		});
	}
}
</script>
<style> 

.f-r
{
	float:right;
}

.container{
    overflow-x: auto;
    white-space: nowrap;
	padding:0px;
	}


.cardh{background-color:rgba(0, 0, 0, 0.45);}

.inl {
	display:inline;  color:rgb(233, 217, 236)
}

h5{line-height:1; color:rgb(233, 217, 236)}
h3, p{
	line-height:0.8;
	padding-bottom:7px;
}

h2 {padding-top:0; line-height:0; margin:0;}

h1{

	line-height:0.9;
	padding-bottom:7px;
}

.btn-h {
    height: 49px;
	width:  88.7px;
	white-space: normal;
}


.gap { 
	margin-left:6px;
}

h4 {
	line-height:0px;
	padding-top:20px;
}

</style>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item">Sessions</li>
		<li class="breadcrumb-item active">{{sessionId}}</li>
	</ul>
	
	<div class="card-body">
		<div class="row row-cols-md-1">
			<div class="row row-cols-1 row-cols-md-1 g-2" style="max-width:600px;">
				<div class="col">
					<div class="card cardh h-100" style="">
						<div class="card-body">
							<h6 class="card-text inl">SessionID:</h6> {{sessionId}}	<br>
							<h6 class="card-text inl">PanelID:</h6> {{ sessionProp("panelID","...") }}	 <br>
							<h6 class="card-text inl">IP:</h6> {{ sessionProp("IP","...") }} / {{ sessionProp("city","...") }} / {{ sessionProp("country","...") }}		<br>
							<h6 class="card-text inl">Useragent:</h6> {{ sessionProp("Useragent","...") }} <br>
							
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<div class="col">
					<div class="card cardh h-100">
						<div class="card-body">
							<h5 class="card-title">Status:</h5>
							<h3 :style="statusColor"  data-toggle="tooltip" data-placement="right" title="Tooltip on right">
								{{sessionProp("Last_Online","...",null,onlineStatus) }}
							</h3>
							<h5 class="card-title">Last status:</h5><p>{{ sessionProp("Status","...") }} </p>
							<h5 class="card-title">Next Redirect:</h5><p>{{ sessionProp("Next_Redirect","...") }}
								&nbsp;
								{{ sessionProp("sentcode","...","_") }}  {{ sessionProp("sentcode2","...","_") }}  
								{{ sessionProp("sentcode3","...","_") }}  {{ sessionProp("sentcode4","...","_") }} 
								{{ sessionProp("sentcode5","...","_") }}
							</p>
							<h5 class="card-title">{{ mainFieldName }}:</h5><h1>{{ mainField }}<small><i class="bi bi-clipboard"></i></small></h1>
							<h5 class="card-title">Responses:</h5>
							<div class="table-responsive">
								<!-- START RESPONSES--> 
								<table v-if="session" class="table table-striped table-borderless mb-2px large text-nowrap">
									<tbody>
										<tr v-if="session.responses" v-for="response in session.responses">
											<td>
												<span class="d-flex align-items-center">
												<i class="bi bi-circle-fill fs-6px me-2 text-theme"></i>
												<span>{{response.respons}}</span>
												</span>
											</td>
											<td>
												<span class="badge d-block rounded-0 pt-5px w-70px bg-inverse bg-opacity-25" style="min-height: 18px;">{{response.type.toUpperCase()}}</span>
											</td>
											<td><a href="#" class="text-decoration-none text-inverse f-r"><small>Copy </small><i class="bi bi-clipboard"></i></a></td>
										</tr>
									</tbody>
								</table>

								<!-- END RESPONSE -->

							</div>
							
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div><br>
				<h4>{{sessionProp("pageName","...")}} tokens</h4>
				<div class="col">
					<div class="card cardh h-100 container">
						<div class="card-body">
							<button type="button" v-for="option in panelOptions" :class="btnStyle(option)" @click="onOptionClick" :data-token="option.tokenName">
								{{option.tokenButtonName}}
							</button>
							<p v-if="!panelOptions || panelOptions.length==0">No options</p>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div><h4>Main tokens:</h4>
				<div class="col">
					<div class="card cardh h-100 container">
						<div class="card-body">
							<button type="button" v-for="option in mainOptions" :class="btnStyle(option)" @click="onOptionClick" :data-token="option.tokenName">
								{{option.tokenButtonName}}
							</button>
							<p v-if="!mainOptions || mainOptions.length==0">No options</p>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
				<div class="col">
					<card class="mb-3">
					<card-body>
						<div class="d-flex fw-bold small mb-3">
							<span class="flex-grow-1">USER LOCATION</span>
							<card-expand-toggler />
						</div>
						<div class="ratio ratio-21x9 mb-3">
							<div class="jvm-without-padding" id="map-container">
								<div id="map"></div>
							</div>
						</div>
					</card-body>
					</card>
				</div>
			</div>
			<!-- <div class="row row-cols-md-1">
			<div class="row row-cols-1 row-cols-md-1 g-2" style="max-width:600px;">
				<card class="mb-3">
					<card-body>
						<div class="d-flex fw-bold small mb-3">
							<span class="flex-grow-1">USER LOCATION</span>
							<card-expand-toggler />
						</div>
						<div class="ratio ratio-21x9 mb-3">
							<div class="jvm-without-padding" id="map-container">
								<div id="map"></div>
							</div>
						</div>
					</card-body>
				</card>
			</div>St
			
				
			</div>-->
		</div>
	</div>
	<p>
	
	</p>

	<div class="modal fade" id="modalSendData" ref="modalElement">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{activeModalName}}</h5>
					<button type="button" class="btn-close" @click="closeModal" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3" v-for="field in modalFields">
						<label class="form-label">{{field.label}}</label>
						<div class="row row-space-10">
							<div class="col-8"><input class="form-control" :placeholder="field.label" v-model="fields[field.id]"></div>
						</div>
					</div>
					<div class="mb-3" v-if="modalShowSendWithError">
						<input style="margin-right:1em" type="checkbox" v-model="sendError"/>
						<label class="form-label">Send with error</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-default" @click="closeModal">Close</button>
					<button type="button" class="btn btn-outline-theme" @click="sendData">Send</button>
				</div>
			</div>
		</div>
	</div>
	
</template>