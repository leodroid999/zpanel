
<script>
import { useAStore } from '@/stores/aStore';
const aStore = useAStore();
export default {
    data: function(){
		return {
			selectedScript:"createPanel",
            selectedUser:null,
            selectedPanel:null,
            selectedNode:null,
            selectedPage:null,
            serverDomain:"",
            serverUsername:"",
            serverPassword:"",
            panelName:null,
            folderName:null,
		}
	},
    methods:{
        loadPanelList:function(){
            aStore.getPanelList()
        },
        loadNodeList:function(){
            aStore.getNodeList()
        },
        loadUserList:function(){
            aStore.getUserList()
        },
        loadPageList:function(){
            aStore.getPageList()
        },
        runScript:function(){
            if(this.selectedScript=="hostPanel"){
                aStore.hostPanel(this.selectedPanel.panelId,this.selectedPanel.nodeId,this.serverDomain,this.serverUsername,this.serverPassword)
            }
            if(this.selectedScript=="createPanel"){
                aStore.createPanel(this.panelName,this.selectedNode.nodeId,this.selectedUser.userId)
            }
            if(this.selectedScript=="deletePanel"){
                aStore.deletePanel(this.selectedPanel.panelId,this.selectedPanel.nodeId)
            }
            if(this.selectedScript=="reinstallPanel"){
                aStore.reinstallPanel(this.selectedPanel.panelId,this.selectedPanel.nodeId)
            }
            if(this.selectedScript=="deployPage"){
                aStore.deployPage(this.selectedPanel.panelId,this.selectedPanel.nodeId,this.selectedPage.blueprint,this.folderName)
            }
        }
    },
    computed:{
        panels: function(){
			return aStore.panels
		},
        pages: function(){
			return aStore.pages
		},
        nodes: function(){
			return aStore.nodes
		},
        users: function(){
			return aStore.users
		},
        scriptOutput: function(){
			return aStore.scriptOutput
		},
        selectedUserModel:{
            get() {
                if(!this.users){
                    return null;
                }
                if(this.users && this.users.length>0 && this.selectedUser==null){
                    this.selectedUser=this.users[0]
                    return this.users[0].username
                }
                return this.selectedUser.username;
            },
            set(val) {
                this.selectedUser=this.users.find(user=>user.username==val)
            },
        },
        selectedPanelModel:{
            get() {
                if(!this.panels){
                    return null;
                }
                if(this.panels && this.panels.length>0 && this.selectedPanel==null){
                    this.selectedPanel=this.panels[0]
                    return this.panels[0].panelId
                }
                return this.selectedPanel.panelId
            },
            set(val) {
                this.selectedPanel=this.panels.find(panel=>panel.panelId==val)
            },
        },
        selectedNodeModel:{
            get() {
                if(!this.nodes){
                    return null;
                }
                if(this.nodes && this.nodes.length>0 && this.selectedNode==null){
                    this.selectedNode=this.nodes[0]
                    return this.nodes[0].nodeId
                }
                return this.selectedNode.nodeId
            },
            set(val) {
                this.selectedNode=this.nodes.find(node=>node.nodeId==val)
            },
        },
        selectedPageModel:{
            get() {
                if(!this.pages){
                    return null;
                }
                if(this.pages && this.pages.length>0 && this.selectedPage==null){
                    this.selectedPage=this.pages[0]
                    return this.pages[0].blueprint;
                }
                return this.selectedPage.blueprint;
            },
            set(val) {
                this.selectedPage=this.pages.find(page=>page.blueprint==val)
            },
        }
    },
    mounted(){
        this.loadPanelList()
        this.loadNodeList()
        this.loadUserList()
        this.loadPageList()
    }
}
</script>
<style>
    .script-select{
		background: rgba(0,0,0,0.3);
		color: white;
		padding: 0.5em;
		border-radius: 0.5em;
		font-size: 1.15em;
		margin-bottom: 0.5em;
	}
    input[type="text"] , textarea{
        background-color: rgb(0,0,0,0.5);
        color: lightgray;
        appearance: none;
    }
    .script-select option{
        background-color: black;
    }
    .float-right {
        float: right;
    }

	.card-img-top
	{
		height:10rem;

	}
	h4, h5{
		display:inline;
		padding-right:5px;
	}
</style>
<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active">Scripts</li>
    </ul>
    <h1 class="page-header"><small></small></h1>
    <p></p>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div class="col">
            <div class="card">
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Panel Scripts</h5>
                    <div class="mb-3">
                        <label class="form-label">Select Script</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <select class="script-select" v-model="selectedScript">
                                    <option value="createPanel">Create Panel</option>
                                    <option value="hostPanel">Host Panel</option>
                                    <option value="deletePanel">Delete Panel</option>
                                    <option value="reinstallPanel">Reinstall Panel</option>
                                    <option value="deployPage">Deploy Page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedScript == 'createPanel'">
                        <div class="mb-3">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Node</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedNodeModel">
                                        <option v-if="nodes" v-for="node in nodes" :value="node.nodeId">{{node.nodeId }}</option>
                                        <option disabled v-if="!nodes" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">User</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedUserModel">
                                        <option v-if="users" v-for="user in users" :value="user.username">{{user.username }}</option>
                                        <option disabled v-if="!users" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Panel Name</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="panelName" type="text"/>
                                </div>
                            </div>
                        </div>        
                    </div>
                    <div v-if="selectedScript == 'hostPanel'">
                        <div class="mb-3">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Panel</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedPanelModel">
                                        <option v-if="panels" v-for="panel in panels" :value="panel.panelId">{{panel.panelId}} @ {{panel.nodeId }}</option>
                                        <option disabled v-if="!panels" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Server Domain</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="serverDomain" type="text"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Server Username</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="serverUsername"  type="text"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Server Password</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="serverPassword" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedScript == 'deletePanel'">
                        <div class="mb-3">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Panel</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedPanelModel">
                                        <option v-if="panels" v-for="panel in panels" :value="panel.panelId">{{panel.panelId}} @ {{panel.nodeId }}</option>
                                        <option disabled v-if="!panels" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedScript == 'reinstallPanel'">
                        <div class="mb-3">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Panel</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedPanelModel">
                                        <option v-if="panels" v-for="panel in panels" :value="panel.panelId">{{panel.panelId}} @ {{panel.nodeId }}</option>
                                        <option disabled v-if="!panels" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedScript == 'deployPage'">
                        <div class="mb-3">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Panel</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedPanelModel">
                                        <option v-if="panels" v-for="panel in panels" :value="panel.panelId">{{panel.panelId}} @ {{panel.nodeId }}</option>
                                        <option disabled v-if="!panels" value="no panels"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Page</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="selectedPageModel">
                                        <option v-if="pages" v-for="page in pages" :value="page.blueprint">{{ page.blueprint }}</option>
                                        <option disabled v-if="!page" value="no pages"></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Folder Name</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="folderName" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-default w-100px mb-3" @click="runScript">Run Script</button>
                    <div class="mb-3">
                        <label class="form-label">Output</label>
                        <div class="row row-space-10">
                            <div class="col-12">
                                <textarea class="w-100" style="resize:none;height:20vh">
                                    {{scriptOutput}}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>