<script lang="ts"> 
import{
    createApp,
    defineComponent,
    ref,
    h
} from "vue"
import {
    useSessionStore
} from '@/stores/sessionStore';
const sessionStore = useSessionStore();
import {
    ScrollSpy
} from 'bootstrap';
import {
    Modal
} from "bootstrap";
import VueTableLite from 'vue3-table-lite/ts'
import { error } from "console";
import { LineElement } from "chart.js";

interface ErrorResponse{
  message:string,
  error:string,
}

export default {
    data: function () {
        return {
            hostPanelModal: null,
            panelSettingsModal: null,
            panelAccessModal: null,
            panelUniquelinksModal: null,
            panelFileManagerModal: null,
            currentPanelSettings: null,
            currentPanelAccessList: null,
            currentPanelAccessUser: null,
            currentPanelUniquelinks: null,
            selectedPanel: null,
            selectedNode: null,
            selectedNodeDomain: null,
            showOutput: false,
            hostPanelHost: "",
            hostPanelUsername: "",
            hostPanelPassword: "",
            loadPanelDataError: false,
            loadPanelUniquelinksError: false,
            loadPanelAccessListError: null,
            loadFileManagerError:null,
            currentUserSelected: null,
            currentAccessModalSection: null,
            settingSaveMessage: "",
            addUserUsername: "",
            addUserPassword: "",
            addUserAccess: "view",
            editUserPassword: "",
            removeUserPassword: "",
            panelAccessSaveError: "",
            uniqueLinksFiltered:[],
            uniqueLinkTable:{
                isLoading:false,
                columns:[
                    {
                        label: "Link",
                        field: "linkId"
                    },
                    {
                        label: "Field",
                        field: "field"
                    },
                    {
                        label: "Data",
                        field: "data",
                        columnClasses:["datacol"]
                    },
                    {
                        label: "Actions",
                        field: "actions",
                        columnClasses:["actionscol"]
                    },
                ],
                rows:[],
                total:0
            },
            newUniquelinkData:"",
            newUniquelinkField:"email_address",
            editUniquelinkData:"",
            uniqueLinkAddMessage:"",
            uniqueLinkSaveMessage:"",
            selectedUniquelink:null,
            searchBar:null,
            fileManagerToken:null,
            
        }
    },
    methods: {
        initLinkTable: function ({
                el: tableComponent
            }){
                let headerTr = tableComponent.getElementsByClassName("vtl-thead-tr");
                if (! headerTr[0]) {
                    return;
                }
                let cloneTr = headerTr[0].cloneNode(true); // Clone first <tr>
                let childTh = cloneTr.getElementsByClassName("vtl-thead-th");
                for (let i = 0; i < childTh.length; i++) {
                    // Clear <th>'s HTML
                    childTh[i].innerHTML = "";
                }

                const SearchBar = defineComponent(
                    (props) => {
                        return () => {
                            let node=h("input", {
                                class: "searchBar form-control",
                                placeholder:"Search..",
                                ref:"search",
                                onInput: function (e) { 
                                    props.filterLinks(e.target.value)
                                },
                            });
                            props.searchBar = node;
                            return node;   
                        }
                    },
                    // extra options, e.g. declare props and emits
                    {
                        props: {
                            filterLinks:{
                                type:Function
                            },
                            searchBar:{
                                type:Object
                            }
                        }
                    }
                )

                // Create a input element and append to first <th>
                createApp(
                   SearchBar,{filterLinks:this.filterLinks,searchBar:this.searchBar}
                ).mount(childTh[2]);

                // append cloned element to the header after first <tr>
                headerTr[0].after(cloneTr)
        },
        resetFilter: function(){
            this.uniqueLinksFiltered = this.currentPanelUniquelinks;
            this.uniqueLinkTable.rows = this.uniqueLinksFiltered
            this.uniqueLinkTable.total = this.uniqueLinksFiltered.length;
        },
        initLinkTableRows: function(rowData){
            this.currentPanelUniquelinks = rowData;
            this.uniqueLinksFiltered = rowData;
            this.uniqueLinkTable.rows = rowData.slice(0,10);
            this.uniqueLinkTable.total = this.uniqueLinksFiltered.length;
        },
        filterLinks: function(term){
            this.uniqueLinksFiltered=this.currentPanelUniquelinks.filter(i => i.data.includes(term))
            this.uniqueLinkTable.rows = this.uniqueLinksFiltered.slice(0,10)
            this.uniqueLinkTable.total = this.uniqueLinksFiltered.length;
        },
        doSearchLinks: function (offset, limit, order, sort) {
            this.uniqueLinkTable.rows=this.uniqueLinksFiltered.slice(offset,offset+limit)
        },
        loadPanelList: async function () {
            await sessionStore.getPanelList(false,false);
        },
        loadPanelSettings: function () {
            sessionStore.panels.forEach(async function (panel) {
                let data = await sessionStore.getPanelSettings(panel.panelId, panel.nodeID)
                if (data.status == "ok") {
                    panel.settings = data.settings;
                } else {
                    this.loadPanelDataError = true
                }
            });
        },
        updateRedirectAll: async function (panelId, nodeID) {
            let panel = sessionStore.panels.find(item => item.panelId == panelId && item.nodeID == nodeID)
            let newValue = panel.settings.Redirect_All ? 0 : 1;
            let result = await sessionStore.savePanelSettings(panelId, panel.NodeName, {"Redirect_All":newValue});
            if (result.status == "ok") {
                panel.settings.Redirect_All = newValue;
            }
        },
        checkEnabled: function (panelId:string, nodeID:string) : boolean{
            let panel = sessionStore.panels.find(item => item.panelId == panelId && item.nodeID == nodeID)
            if(!panel || !panel.settings){
                return false
            }
            return panel.settings.Redirect_All as boolean
        },
        checkDisabled: function (panelId, nodeID) {
            let panel = sessionStore.panels.find(item => item.panelId == panelId && item.nodeID == nodeID)
            return Array.isArray(panel.settings);
        },
        daysLeft: function (dateStr) {
            try {
                const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                const firstDate = new Date();
                const secondDate = new Date(dateStr);
                const diffDays = Math.round(Math.abs((firstDate.getMilliseconds() - secondDate.getMilliseconds()) / oneDay));
                return `(${diffDays} days left)`
            }
            catch (e) {
                return "";
            }
        },
        openHostModal: function (panel, nodeName) {
            this.selectedPanel = panel;
            this.selectedNode = nodeName;
            this.showOutput = false;
            this.hostPanelModal.show()
        },
        openPanelSettingsModal: async function (panelName, nodeName) {
            this.loadPanelDataError = false
            this.currentPanelSettings = null;
            this.settingSaveMessage = "";
            this.selectedPanel = panelName;
            this.selectedNode = nodeName;
            let panel = sessionStore.panels.find(item => item.panelId == panelName && item.NodeName == nodeName)
            try {
                let data = await sessionStore.getPanelSettings(panel.panelId, panel.nodeID)
                if (data.status == "ok") {
                    this.currentPanelSettings = data.settings;
                } else {
                    this.loadPanelDataError = true
                }
            }
            catch (e) {
                this.loadPanelDataError = true
            }
            this.panelSettingsModal.show()
        },
        openPanelUniquelinksModal: async function (panelName, nodeName) {
            this.loadPanelDataError = false
            this.currentPanelSettings = null;
            this.settingSaveMessage = "";
            this.selectedPanel = panelName;
            this.selectedNode = nodeName;
            this.panelUniquelinksModal.show()
            let panel = sessionStore.panels.find(item => item.panelId == panelName && item.NodeName == nodeName)
            try {
                let result = await sessionStore.getUniqueLinks(panel.panelId, panel.nodeID)
                if("status" in result && result.status == "ok"){
                    this.initLinkTableRows(result.uniqueLinks)
                } 
                else {
                    this.loadPanelUniquelinksError= true
                }
            }
            catch (e) {
                this.loadPanelUniquelinksError = true
            }
        },
        loadAccessList: async function () {
            try {
                let data = await sessionStore.getPanelAccessList(this.selectedPanel)
                if (data.status == "ok") {
                    this.currentPanelAccessList = data.users;
                } else {
                    this.loadPanelAccessListError = true
                }
            }
            catch (e) {
                this.loadPanelAccessListError = true
            }
        },
        openPanelAccessModal: async function (panelName, nodeName) {
            this.panelAccessSaveError = ""
            this.selectedPanel = panelName;
            this.selectedNode = nodeName;
            this.currentAccessModalSection = "list"
            this.currentPanelAccessUser = null;
            this.loadAccessList();
            this.panelAccessModal.show()
        },
        loadFileManagerCreds: async function () {
            try {
                let data = await sessionStore.getPanelFMCreds(this.selectedPanel,this.selectedNode)
                if (data.status == "ok") {
                    this.fileManagerToken = data.fm_token
                } else {
                    this.loadFileManagerError = true
                }
            }
            catch (e) {
                this.loadFileManagerError = true
            }
        },
        openPanelFileManagerModal: async function (panelName, nodeName, domain) {
            this.panelAccessSaveError = ""
            this.fileManagerToken = "";
            this.selectedPanel = panelName;
            this.selectedNode = nodeName;
            this.selectedNodeDomain = domain;
            this.loadFileManagerCreds()
            this.panelFileManagerModal.show()
        },
        addUser: async function () {
            this.addUserUsername = "",
                this.addUserPassword = "",
                this.currentAccessModalSection = 'addUser'
            this.panelAccessSaveError = ""
        },
        addUserSave: async function () {
            if (!this.addUserUsername || !this.addUserPassword) {
                this.panelAccessSaveError = "Missing username to add or password"
                return
            }
            try {
                let data = await sessionStore.addPanelUser(this.selectedPanel, this.addUserUsername, this.addUserPassword, this.addUserAccess)
                if (data.status == "ok") {
                    this.returnToList()
                } else {
                    this.panelAccessSaveError = data.message
                }
            }
            catch (e) {
                this.panelAccessSaveError = "Error saving new panel user"
            }
        },
        editUser: async function (user) {
            this.currentPanelAccessUser = Object.assign(user, {});
            this.currentAccessModalSection = 'editUser'
            this.editUserPassword = "";
        },
        editUserSave: async function () {
            if (!this.editUserPassword) {
                this.panelAccessSaveError = "Missing password"
                return
            }
            try {
                let username = this.currentPanelAccessUser.username;
                let access = this.currentPanelAccessUser.access
                let data = await sessionStore.editPanelUser(this.selectedPanel, username, this.editUserPassword, access);
                if (data.status == "ok") {
                    this.returnToList()
                } else {
                    this.panelAccessSaveError = data.message
                }
            }
            catch (e) {
                this.panelAccessSaveError = "Error saving access settings"
            }
        },
        removeUser: async function (user) {
            this.currentPanelAccessUser = Object.assign(user, {});
            this.removeUserPassword = ""
            this.currentAccessModalSection = 'removeUser'
        },
        removeUserConfirm: async function () {
            if (!this.removeUserPassword) {
                this.panelAccessSaveError = "Missing password"
                return
            }
            try {
                let username = this.currentPanelAccessUser.username;
                let data = await sessionStore.removePanelUser(this.selectedPanel, username, this.removeUserPassword);
                if (data.status == "ok") {
                    this.returnToList()
                } else {
                    this.panelAccessSaveError = data.message
                }
            }
            catch (e) {
                this.panelAccessSaveError = "Error removing user from panel"
            }
        },
        returnToList: async function () {
            this.loadAccessList()
            this.panelAccessSaveError = ""
            this.currentPanelAccessUser = null;
            this.currentAccessModalSection = 'list'
        },
        hostPanel: function () {
            sessionStore.hostPanel(this.selectedPanel, this.selectedNode, this.hostPanelHost, this.hostPanelUsername, this.hostPanelPassword)
            this.showOutput = true;
        },
        getPanelNode: function(panelId){
            if(!this.panels){
                return ""
            }
            let panels=this.panels.filter(panel => panel.panelId == panelId);
            if(!panels || panels.length==0){
                return ""
            }
            return panels[0].nodeID;
        },
        saveSettings: async function () {
            this.settingSaveMessage = "Saving...";
            if(this.currentPanelSettings.Enable_Turnstile){
                if(!this.currentPanelSettings.CFSiteKey || !this.currentPanelSettings.CFSiteSecret){
                    this.settingSaveMessage = "Site Key and Secret must be set"
                    return
                }
            }
            let result = await sessionStore.savePanelSettings(this.selectedPanel, this.selectedNode, this.currentPanelSettings)
            if (result.status == "ok") {
                this.settingSaveMessage = "Saved"
                let panel = sessionStore.panels.find(item => item.panelId == this.selectedPanel && item.NodeName == this.selectedNode);
                if(panel){
                    panel.settings = this.currentPanelSettings;
                }
            } else {
                this.settingSaveMessage = result.message ? result.message : "error"
            }
        },
        readOnlySettings: function () {
            return this.currentPanelSettings.access && this.currentPanelSettings.access != "full"
        },
        editUniqueLink: function(link){
            this.selectedUniquelink = link
            this.editUniquelinkData = link.data;

        },
        cancelUniqueLink: function(){
            this.selectedUniquelink = null
        },
        saveUniqueLink: async function(){
            this.uniqueLinkSaveMessage = "Saving...";
            if(!this.editUniquelinkData){
                this.uniqueLinkSaveMessage="Error: No value for link data."
                return;
            }
            if(this.editUniquelinkData.includes(",")){
                this.uniqueLinkSaveMessage="lists are not supported when editing, please add new links above";
                return;
            }

            let result = await sessionStore.saveUniqueLink(this.selectedPanel, this.selectedNode,this.selectedUniquelink.linkId,this.editUniquelinkData)
            if ("status" in result ) {
                this.uniqueLinkSaveMessage = "Saved"
                this.selectedUniquelink.data = this.editUniquelinkData
                this.selectedUniquelink = null
            } else {
                this.uniqueLinkSaveMessage = result.message ? result.message : "error"
            }
        },
        addUniqueLink: async function(){
            this.uniqueLinkAddMessage = "Creating...";
            if(!this.newUniquelinkData){
                this.uniqueLinkAddMessage="Error: No value for link data."
                return;
            }
            let result = await sessionStore.addUniqueLink(this.selectedPanel, this.selectedNode,this.newUniquelinkData,this.newUniquelinkField)
            if ("status" in result ) {
                for(let link of result.uniqueLinks){
                    this.uniqueLinkAddMessage = "Created"
                    link.field = this.newUniquelinkField;
                    this.currentPanelUniquelinks.unshift(link)
                    this.resetFilter();
                    this.doSearchLinks(0,10,"asc","linkId")
                
                }
            } else {
                this.uniqueLinkAddMessage = result.message ? result.message : "error"
            }
        },
        deleteUniqueLink: async function(linkId){
            let result = await sessionStore.deleteUniqueLink(this.selectedPanel, this.selectedNode,linkId)
            if ("status" in result){
                this.currentPanelUniquelinks=this.currentPanelUniquelinks.filter(i => i.linkId != linkId);
                this.uniqueLinksFiltered=this.uniqueLinksFiltered.filter(i => i.linkId != linkId);
                this.uniqueLinkTable.rows=this.uniqueLinkTable.rows.filter(i => i.linkId != linkId);
                this.uniqueLinkSaveMessage="Deleted"
            }
            else{
                this.uniqueLinkAddMessage = result.message ? result.message : "error"
            }
        },
        exportUniqueLinks: async function(){
            let url=`/portal/uniqueLinks.php?panelId=${this.selectedPanel}&nodeId=${this.selectedNode}&dl=1`
            var link=document.createElement('a');
            link.href = url
            link.download = `${this.selectedPanel}-links.csv`
            link.click()
        }
    },
    computed: {
        panels: function () {
            return sessionStore.panels
        },
        scriptOutput: function () {
            return sessionStore.scriptOutput
        },
        Enable_Captcha: {
           get(){
                return this.currentPanelSettings.Enable_Captcha
           },
           set(val){
              if(val){
                this.currentPanelSettings.Enable_Turnstile = 0;
              }
              this.currentPanelSettings.Enable_Captcha = val;
           }
        },
        Enable_Turnstile: {
           get(){
                return this.currentPanelSettings.Enable_Turnstile
           },
           set(val){
              if(val){
                this.currentPanelSettings.Enable_Captcha = 0;
              }
              this.currentPanelSettings.Enable_Turnstile = val;
           }
        }
    },
    components: {
        VueTableLite: VueTableLite,
    },
    async mounted() {
        await this.loadPanelList();
        await this.loadPanelSettings();

        new ScrollSpy(document.body, {
            target: '#sidebar-bootstrap',
            offset: 200
        })
        this.hostPanelModal = new Modal(this.$refs.hostPanelModal)
        this.panelSettingsModal = new Modal(this.$refs.panelSettingsModal)
        this.panelAccessModal = new Modal(this.$refs.panelAccessModal)
        this.panelUniquelinksModal= new Modal(this.$refs.panelUniquelinksModal)
        this.panelFileManagerModal= new Modal(this.$refs.panelFileManagerModal)
    }
}
</script>
<style>
.script-output {
    background: rgb(40, 40, 40, 0.6);
    color: lightgray;
    width: 100%;
}

.online {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: lightgreen;
    white-space: nowrap;
    display: inline-block;
    vertical-align: middle;
    margin-right: 10px;

}

.panelAccessSelect {
    background-color: transparent;
    color: white;
    width: 100%;
    padding: 0.25em;
    border-radius: 5px;
}

.panelAccessSelect option {
    background-color: rgba(0, 0, 0, 1);
}
.cmd{
    background: rgba(0,0,0,0.75);
    color: white; 
    width:100%; 
    resize:none;
}
.datacol{
    width: 100%
}
.actionscol{
    display: flex;
    width: max-content;
}
.dataedit{
    width: 100%;
    background: rgba(0, 0, 0, 0.3);
    color: white;
    border: none;
    border-bottom: 1px solid lightgray;
    border-radius: 0.25em;
    padding: 0.5em;
}

@media (max-width:720px) {
    .dialogLarge{
        max-width: 90%;
    }
}
@media (min-width:720px) {
    .dialogLarge{
        max-width: 50%;
    }
}
select{
    appearance: menulist;
}
option{
    background:rgb(0,0,0)
}
</style>
<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
        <li class="breadcrumb-item active">Panels</li>
    </ul>
    <h4>Panels overview</h4>
    <!-- TABLE start -->
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Panel ID</th>
                <th scope="col">Operations</th>
                <th scope="col">Valid until</th>
                <th scope="col">Traffic</th>
            </tr>
        </thead>
        <tbody v-if="panels">
            <tr v-for="panel in panels">
                <td>
                    <div class="online"></div>
                    <b>{{ panel.panelId }}@{{ panel.NodeName }}</b>
                </td>
                <td>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-outline-primary btn-sm"
                                    @click="openHostModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-upload"></i></button>
                                <button class="btn btn-outline-success btn-sm"
                                    @click="openPanelSettingsModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-gear"></i></button>
                                <button class="btn btn-outline-success btn-sm"
                                    @click="openPanelUniquelinksModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-link"></i></button>
                                <button class="btn btn-outline-success btn-sm"
                                    @click="openPanelFileManagerModal(panel.panelId, panel.NodeName,panel.nodeID)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-file"></i></button>
                                <button class="btn btn-outline-warning btn-sm" disabled
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-redo"></i></button>
                                <button v-if="!panel.access" class="btn btn-outline-primary btn-sm"
                                    @click="openPanelAccessModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px; display: flex; justify-content: center;align-items: center;">
                                    <i class="fas fa-users"></i></button>
                            </div>
                        </div>
                    </div>
                </td>
                <td>{{ panel.expires }} {{ daysLeft(panel.expires) }}</td>
                <td>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input" type="checkbox" role="switch"
                            :checked="checkEnabled(panel.panelId, panel.nodeID)"
                            @change="updateRedirectAll(panel.panelId, panel.nodeID)"
                            :disabled="checkDisabled(panel.panelId, panel.nodeID)" :true-value="1" :false-value="0">

                        <label class="form-check-label" for="flexSwitchCheckDefault">Redirect all</label>
                    </div>
                </td>
            </tr>

        </tbody>
        <div class="modal fade" id="modalHostPanel" ref="hostPanelModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Host for panel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Panel: {{ selectedPanel }} @ {{ selectedNode }}
                        </p>
                        <div v-if="!showOutput">
                            <div class="mb-3">
                                <label class="form-label">Server Hostname</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" v-model="hostPanelHost">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Server Username</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" v-model="hostPanelUsername">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Server Password</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" v-model="hostPanelPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span>
                            Manual Setup: Run the following command on the server to install.
                        </span>
                        <div>
                            <textarea disabled class="cmd">wget -O - http://{{selectedPanel}}.{{getPanelNode(selectedPanel) }}/setup.sh | bash -s - "yourdomain.com"</textarea>
                        </div>
                        <div v-if="showOutput">
                            <textarea id="i" rows="15" class="script-output">
                                    {{ scriptOutput }}
                                </textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-theme" v-if="!showOutput"
                            @click="hostPanel">Send</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPanelSettings" ref="panelSettingsModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Panel Settings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Panel: {{ selectedPanel }} @ {{ selectedNode }}
                        </p>
                        <div v-if="currentPanelSettings">
                            <div v-if="'Enable_Captcha' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="Enable_Captcha" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label">Enable reCaptcha</label>
                                </div>
                            </div>
                            <div v-if="'Enable_Turnstile' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="Enable_Turnstile" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label">Enable CF Turnstile</label>
                                </div>
                            </div>
                            <div v-if="Enable_Turnstile">
                                <div class="mb-3">
                                    <label class="form-label">Turnstile Site Key</label>
                                    <div class="row row-space-10">
                                        <div class="col-8">
                                            <input class="form-control" type="text" v-model="currentPanelSettings.CFSiteKey">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Turnstile Site Secret</label>
                                    <div class="row row-space-10">
                                        <div class="col-8">
                                            <input class="form-control" type="text" v-model="currentPanelSettings.CFSiteSecret">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="'Mobile_Only' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="currentPanelSettings.Mobile_Only" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label">Mobile Only</label>
                                </div>
                            </div>
                            <div v-if="'Redirect_All' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="currentPanelSettings.Redirect_All" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label">Redirect All</label>
                                </div>
                            </div>

                            <div v-if="'Enable_Panel_ChatId' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="currentPanelSettings.Enable_Panel_ChatId"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label">Set Separate TG Chat Id for this panel</label>
                                </div>
                            </div>

                            <div v-if="currentPanelSettings.Enable_Panel_ChatId">
                                <div class="mb-3">
                                    <label class="form-label">Chat ID</label>
                                    <div class="row row-space-10">
                                        <div class="col-8">
                                            <input class="form-control" type="text" v-model="currentPanelSettings.ChatId">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <span>{{ this.settingSaveMessage }}</span>
                        </div>
                        <div v-else>
                            <div v-if="!loadPanelDataError">
                                <h4>Loading settings...</h4>
                            </div>
                            <div v-else>
                                <h4>Error loading settings</h4>
                            </div>
                        </div>
                        <div v-if="Array.isArray(currentPanelSettings)">
                            <h5>This panel does not support settings or is not deployed</h5>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                        <div v-if="currentPanelSettings">
                            <button v-if="!this.readOnlySettings()" type="button" class="btn btn-outline-theme"
                                @click="saveSettings">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPanelUniquelinks" ref="panelUniquelinksModal">
            <div class="modal-dialog dialogLarge">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Panel Uniquelinks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Panel: {{ selectedPanel }} @ {{ selectedNode }}
                        </p>
                        <div v-if="currentPanelUniquelinks">
                            <div class="mb-3">
                                <h5>Add link</h5>
                                <label class="form-label">Field</label>
                                <div class="row row-space-10">
                                    <div class="col-6">
                                        <select class="form-control" v-model="newUniquelinkField">
                                            <option value="email_address">email</option>
                                            <option value="cardnumber">cardnumber</option>
                                            <option value="phone">phone</option>
                                        </select>
                                    </div>
                                </div>
                                <label class="form-label"> Value</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <textarea class="form-control" type="text" rows="3" v-model="newUniquelinkData"></textarea>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-theme"
                                        @click="addUniqueLink()">Add</button>
                                    </div>
                                </div>
                            </div>
                            <span>{{ this.uniqueLinkAddMessage }}</span>
                            <div class="mb-3">
                                <h5>Links</h5>
                                <button type="button" class="ml-2 btn btn-outline-theme" @click="exportUniqueLinks()">Export</button>
                            </div>
                            <div class="mb-3">
                                <span>{{ this.uniqueLinkSaveMessage }}</span>
                                <div class="row row-space-10">
                                    <div class="col-12">
                                        <vue-table-lite class="vue-table"
                                            :is-slot-mode="true"
                                            :columns="uniqueLinkTable.columns"
                                            :rows="uniqueLinkTable.rows"
                                            :total="uniqueLinkTable.total"
                                            @VnodeMounted="initLinkTable"
                                            @do-search="doSearchLinks">                     
                                            <template v-slot:data="data">
                                                <div v-if="selectedUniquelink && selectedUniquelink.linkId == data.value.linkId">
                                                    <div class="row row-space-10">
                                                        <input type="text" class="dataedit" v-model="editUniquelinkData"/>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    {{data.value.data}}
                                                </div>
                                            </template>
                                            <template v-slot:actions="data">         
                                                <div v-if="selectedUniquelink && selectedUniquelink.linkId == data.value.linkId">
                                                    <button type="button" class="btn btn-outline-theme" @click="saveUniqueLink()">Save</button>
                                                    <button type="button" class="btn btn-outline-danger mx-2" @click="cancelUniqueLink()">Cancel</button>
                                                </div>
                                                <div v-else>
                                                    <button type="button" class="btn btn-outline-theme" @click="editUniqueLink(data.value)">Edit</button>
                                                    <button type="button" class="btn btn-outline-danger mx-2" @click="deleteUniqueLink(data.value.linkId)">Delete</button>
                                                </div>
                                            </template>
                                        </vue-table-lite>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <div v-else>
                            <div v-if="!loadPanelUniquelinksError">
                                <h4>Loading links..</h4>
                            </div>
                            <div v-else>
                                <h4>Error loading links</h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalPanelAccess" ref="panelAccessModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Panel Collaborators</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Panel: {{ selectedPanel }} @ {{ selectedNode }}
                        </p>
                        <div v-if="currentAccessModalSection == 'list'">
                            <div v-if="currentPanelAccessList != null">
                                <table class="table">
                                    <thead>
                                        <th>
                                            Username
                                        </th>
                                        <th>
                                            Permissions
                                        </th>
                                    </thead>
                                    <tbody>
                                        <div v-if="currentPanelAccessList.length == 0">
                                            <span>No users given access to panel</span>
                                        </div>
                                        <tr v-for="user in currentPanelAccessList">
                                            <td style=" word-break: break-all;">
                                                {{ user.username }}
                                            </td>
                                            <td style="text-align: center">{{ user.access }}</td>
                                            <td>
                                                <button type="button" @click="editUser(user)"
                                                    class="btn btn-sm btn-outline-theme">Change Access</button>
                                            </td>
                                            <td>
                                                <button type="button" @click="removeUser(user)"
                                                    class="btn btn-sm btn-outline-warning">Remove Access</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                <div v-if="!loadPanelAccessListError">
                                    <h4>Loading users...</h4>
                                </div>
                                <div v-else>
                                    <h4>Error loading users</h4>
                                </div>
                            </div>
                        </div>
                        <div v-if="currentAccessModalSection == 'addUser'">
                            <h5>Give user access</h5>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" v-model="addUserUsername">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enter your password</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" type="password" v-model="addUserPassword">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <select class="panelAccessSelect" v-model="addUserAccess">
                                            <option value="caller">Caller</option>
                                            <option value="view">View Logs/Sessions</option>
                                            <option value="full">Full Access</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="currentAccessModalSection == 'editUser'">
                            <h5>Change access level</h5>
                            <div class="mb-3">
                                <span>User: {{ this.currentPanelAccessUser.username }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Access</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <select class="panelAccessSelect" v-model="this.currentPanelAccessUser.access">
                                            <option value="caller">Caller</option>
                                            <option value="view">View Logs/Sessions</option>
                                            <option value="full">Full Access</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enter your password</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" type="password" v-model="editUserPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="currentAccessModalSection == 'removeUser'">
                            <h5>Remove user from panel</h5>
                            <div class="mb-3">
                                <span>User: {{ this.currentPanelAccessUser.username }}</span>
                            </div>
                            <div class="mb-3">
                                <span>This user wont be able to see any logs or panel settings any more</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enter your password</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <input class="form-control" type="password" v-model="removeUserPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-error">
                            <div v-if="panelAccessSaveError" class="alert alert-warning">
                                <span v-if="panelAccessSaveError">{{ panelAccessSaveError }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                        <div v-if="currentAccessModalSection == 'list'">
                            <button type="button" class="btn btn-outline-theme" @click="addUser">Add User</button>
                        </div>
                        <div v-if="currentAccessModalSection != 'list'">
                            <button type="button" class="btn btn-outline-warning" @click="returnToList">Cancel</button>
                        </div>
                        <div v-if="currentAccessModalSection == 'addUser'">
                            <button type="button" class="btn btn-outline-theme" @click="addUserSave">Save</button>
                        </div>
                        <div v-if="currentAccessModalSection == 'editUser'">
                            <button type="button" class="btn btn-outline-theme" @click="editUserSave">Save</button>
                        </div>
                        <div v-if="currentAccessModalSection == 'removeUser'">
                            <button type="button" class="btn btn-outline-theme" @click="removeUserConfirm">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalFileManager" ref="panelFileManagerModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">File Manager</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Panel: {{ selectedPanel }} @ {{ selectedNode }}
                        </p>
                        <div v-if="fileManagerToken">
                            <a target="_blank" v-bind:href="`http://${this.selectedPanel}.${this.selectedNodeDomain}/scripts/fm.php?auth_token=${this.fileManagerToken}`">
                                <button type="button" class="btn btn-outline-theme">Open File Manager</button>
                            </a>
                        </div>
                        <div v-else>
                            <div v-if="!loadFileManagerError">
                                <h4>Loading credentials...</h4>
                            </div>
                            <div v-else>
                                <h4>Error loading credential.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </table>

    <!-- TABLE end -->
</template>