<script>
import { useEditorStore } from "@/stores/editorStore";
import { useSessionStore } from "@/stores/sessionStore";
import { Modal } from "bootstrap";
const editorStore = useEditorStore();
const sessionStore = useSessionStore();
const modalPushData = null;

export default {
    data() {
        return {
            blueprint: Object,
            blueprintToken: {
                blueprint: '',
                pagefile: '',
                exception_antibot: false,
                tokenButtonName: "",
                tokenButtonType: 'standard',
                isMainRow: false,
                SendTokenWithError: false,
                tokenName: '',
                wait_lag: false,
                enable_redirectpulse: false,
                groupId: null,
            },
            comboInputs:{
				1:"",
				2:"",
				3:"",
				4:"",
				5:""
			},
            tokenButtonTypes: [
                "standard",
                "error",
                "combo",
                "pushdata1",
                "pushdata2",
                "pushdata3",
                "pushdata4",
                "pushdata1-2",
                "pushdata1-2-3",
                "pushdata1-2-3-4",
                "pushdata2-3",
                "pushdata2-4",
            ],
            removeTokenId: '',
            removeTokenError: '',
            logs: Object,
            responses: Object,
            issues: [],
            saveNotificationError: "",
            activeModal: null,
            activeModalName: null,
            activeModalToken: null,
            sendDataModal: null,
            sendError: false,
            modalShowSendWithError: false,
            fields: {},
            message: '',
            thumbFile: '',
        };
    },
    methods: {
        loadBlueprintsInfo: async function () {
            var blueprints = [];
            if (editorStore.blueprints == null) {
                let response = await editorStore.loadBlueprints();
                console.log(response);
                if ("blueprints" in response) {
                    blueprints = response.blueprints;
                }
            } else blueprints = editorStore.blueprints;

            console.log(editorStore.blueprints);

            if (this.$route.params.id) {
                var blueprint_id = this.$route.params.id;
                for (var i = 0; i < blueprints.length; i++) {
                    if (blueprints[i].blueprint == blueprint_id) {
                        this.blueprint = blueprints[i];
                        break;
                    }
                }
            }
            console.log("blueprint : " + this.blueprint.MainField);

            this.blueprintToken.blueprint = this.blueprint.blueprint;
            let blueprintTokens = await editorStore.loadBlueprintTokens(this.blueprint);
            this.blueprint.tokens = blueprintTokens;

            let blueprintIndex = await editorStore.loadBlueprintIndex(this.blueprint);
            this.blueprint.index = blueprintIndex;

            console.log(this.blueprint)
            let blueprintFiles = await editorStore.loadBlueprintFiles(this.blueprint);
            this.blueprint.files = blueprintFiles;

            console.log(this.blueprint.tokens);
            console.log(this.blueprint.index);
            console.log(this.blueprint.files);

            this.checkIssues();
        },
        async updateBlueprint(event) {
            let response = await editorStore.saveBlueprint(this.blueprint, this.thumbFile);
            console.log(response);
            if (response.status == "ok")
                this.loadBlueprintsInfo();
        },

        saveBlueprintToken: async function () {
            let prevType=this.blueprintToken.tokenButtonType;
            if(this.blueprintToken.tokenButtonType.startsWith("combo")){
                let groupId = this.blueprintToken.groupId;
                if(!groupId){
                    return;
                }
                let buttonId = 1;
                if(this.comboOptions[groupId]){
                    for(let option of this.comboOptions[groupId]){
                        if(option.order >= buttonId){
                            buttonId = option.order + 1;
                        }
                    }
                }
                this.blueprintToken.tokenButtonType = "combo_" + groupId + "_" + buttonId
            }
            let response = await editorStore.addBlueprintToken(this.blueprintToken); 
            console.log(response);
            this.blueprintToken.tokenButtonType=prevType;
            if (response.status == "ok")
                this.loadBlueprintsInfo();
        },


        async removeBlueprintToken() {
            if (this.removeTokenId == '')
                return;

            let response = await editorStore.deleteBlueprintToken(this.removeTokenId);
            this.removeTokenError = response.message;

            console.log(response);
            if (response.status == "ok") {
                this.loadBlueprintsInfo();
            }
        },

        async archiveBlueprint() {
            let response = await editorStore.archiveBlueprint(this.blueprint);
            console.log(response);
            if (response.status == "ok")
                this.loadBlueprintsInfo();
        },

        async loadEditorData() {
            this.logs = await editorStore.loadLogs(this.blueprint);
            this.responses = await editorStore.loadResponses(this.blueprint);

            setTimeout(async () => {
                await this.loadEditorData()
            }, 1000);
        },

        checkIssues() {
            this.issues = [];

            if (this.blueprint.assetDir == null || this.blueprint.assetDir == '')
                this.issues.push(" No .zip attached to blueprint or zip does not exist.");

            if (this.blueprint.tokens.length == 0)
                this.issues.push(" No tokens added, please add tokens if you need them.");

            if (this.blueprint.startpage == null || this.blueprint.startpage == '')
                this.issues.push(" No startpage selected.");

            if (this.blueprint.MainField == null || this.blueprint.MainField == '')
                this.issues.push(" No input field chosen, please choose (username, cardnumber or email_address");

            if (this.blueprint.default_backlink == null || this.blueprint.default_backlink == '')
                this.issues.push(" No default backlink chosen (example: /activate /app)");

            if (this.blueprint.thumbnail == null || this.blueprint.thumbnail == '')
                this.issues.push(" No icon / image file chosen");

            for (var i = 0; i < this.blueprint.index.length; i++) {
                var existed = false;
                for (var j = 0; j < this.blueprint.tokens.length; j++) {
                    if (this.blueprint.index[i].pagefile == this.blueprint.tokens[j].pagefile) {
                        existed = true;
                        break;
                    }
                }

                var existed_file = false;
                for (var k = 0; k < this.blueprint.files.length; k++) {
                    if (this.blueprint.index[i].pagefile == this.blueprint.files[k]) {
                        existed_file = true;
                        break;
                    }
                }

                if (existed && !existed_file) {
                    this.issues.push(" There are no files for index, but can't remove because it was related to the token existing.");
                    break;
                }
            }
        },

        async reindex() {
            let response = await editorStore.reindexBlueprint(this.blueprint);
            console.log(response);
            if (response.status == "ok")
                this.loadBlueprintsInfo();
        },

        onOptionClick: function (ev) {
            let btn = ev.currentTarget;
            if (btn.dataset && btn.dataset.token) {
                let token = btn.dataset.token;
                let selectedToken = this.blueprint.tokens.find(option => {
                    return option.tokenName === token;
                })
                console.log(selectedToken)
                if (selectedToken) {
                    if (selectedToken.tokenButtonType && selectedToken.tokenButtonType.includes("pushdata")) {
                        this.activeModal = selectedToken.tokenButtonType;
                        this.activeModalName = selectedToken.tokenButtonName;
                        this.activeModalToken = selectedToken.tokenName;
                        this.modalShowSendWithError = (selectedToken.SendTokenWithError == "1" || selectedToken.SendTokenWithError == 1);
                        this.fields = {}
                        let fieldIds = selectedToken.tokenButtonType.replace("pushdata", "").split("-")
                        for (let field of fieldIds) {
                            this.fields[field] = "";
                        }
                        this.sendDataModal.show()
                        return;
                    }
                    if(selectedToken.tokenButtonType.startsWith("combo")){
					    let parts=selectedToken.tokenButtonType.split("_")
					    if(parts.length==3){
							let input = parts[1]
							let field = parts[2]
							let isError = (selectedToken.SendTokenWithError == "1" || selectedToken.SendTokenWithError == 1);
							if(isError){
								field=1;
							}
					        let value=this.comboInputs[input]
							let fieldValues={}
							for(let i=1;i<=5;i++){
								let existingField = ""
								if(i==1){
									existingField = "sentcode"
								}
								else{
									existingField = "sentcode" + i;
								}
								if(this.logs[existingField]){
									fieldValues[i]=this.logs[existingField]
								}
							}
							fieldValues[field]=value
							console.log(fieldValues)
							sessionStore.sendData(fieldValues, selectedToken.tokenName, isError, true)
							return;
					    }
					}
                    let sendError = (selectedToken.SendTokenWithError == "1" || selectedToken.SendTokenWithError == 1);
                    sessionStore.updateRedirect(selectedToken.tokenName, sendError, true);
                }
            }
        },

        sendData: function () {
            sessionStore.sendData(this.fields, this.activeModalToken, this.sendError, true)
            this.sendDataModal.hide()
        },

        copyClipboard: async function (text) {
            var TempText = document.createElement("input");
            TempText.value = text;
            document.body.appendChild(TempText);
            TempText.select();

            document.execCommand("copy");
            document.body.removeChild(TempText);

            event.target.innerHTML = 'Copied';
        },

        thumbFileSelected: function (e) {
            this.thumbFile = e.target.files[0];
        },

        showOptionButton: function (buttonName) {
            if (buttonName == '' || buttonName == undefined)
                return 'd-none';
        },

        btnStyle: function (option) {
			let isError=(option.SendTokenWithError == "1" || option.SendTokenWithError == 1);
			if(option.tokenButtonType.startsWith("combo")){
			    if(isError){
					return "btn-h btn gap btn-outline-danger btn-sm btn-combo"
				}
				else{
					return "btn-h btn gap btn-outline-theme btn-sm btn-combo"
				}
			}
			if (option.tokenButtonType == "error") {
				return "btn-h btn gap btn-outline-danger btn-sm"
			}
			return "btn-h btn gap btn-outline-theme btn-sm"
		},

        async clearResponses() {
            let response = await editorStore.deleteBlueprintResponses(this.blueprint);
            console.log(response);
        }
    },
    computed: {
        normalTokens: function () {
            if (!this.blueprint || !this.blueprint.tokens) {
                return null;
            }
            return this.blueprint.tokens.filter(item => item.isMainRow != "1" && !item.tokenButtonType.startsWith("combo"))
        },
        mainTokens: function () {
            if (!this.blueprint || !this.blueprint.tokens) {
                return null
            }
            return this.blueprint.tokens.filter(item => item.isMainRow == "1" && !item.tokenButtonType.startsWith("combo"))
        },
        modalFields: function () {
            if (this.activeModal) {
                let fields = []
                let fieldIds = this.activeModal.replace("pushdata", "").split("-")
                for (let field of fieldIds) {
                    fields.push({ id: parseInt(field), label: "sendcode" + parseInt(field) })
                }
                return fields
            }
            return []
        },
        comboOptions: function () {
			if (this.blueprint && this.blueprint.tokens) {
				let options = this.blueprint.tokens
				let filteredOptions = options.filter(option => {
					return option.tokenButtonType.startsWith("combo")
				})
				
				let groupedOptions={}

				for(let option of filteredOptions){
					let parts=option.tokenButtonType.split("_");
					if(parts.length == 3){
						let group = parts[1]
						if(!groupedOptions[group]){
							groupedOptions[group]=[]
						}
                        let order = parts[2];
                        option.order = parseInt(order);
						groupedOptions[group].push(option)
					}
				}

                for(let groupId of Object.keys(groupedOptions)){
                    let groupOpt = groupedOptions[groupId]
                    function compareOrder(a, b) {
                        return a.order - b.order;
                    }
                    groupOpt.sort(compareOrder)
                }
				return groupedOptions
			}
			return []
		},

        comboFields: function(){
		    return this.comboInputs;
		},

        lastOnlineStatus: function () {
            var now = Date.now() / 1000;
            if (now - this.logs.Last_Online > 10)
                return 'Last status: Offline';
            else
                return 'Last status: <span class="text-theme"><b>Online</b> </span>';
        },
    },
    async mounted() {
        this.sendDataModal = new Modal(this.$refs.modalPushData)
        await this.loadBlueprintsInfo();
        await this.loadEditorData();
    },
};
</script>
<style>
.f-r {
    float: right;
}

.container {
    overflow-x: auto;
    white-space: nowrap;
    padding: 0;
}

.cardh {
    background-color: rgba(0, 0, 0, 0.45);
}

.inl {
    display: inline;
    color: rgb(233, 217, 236);
}

h5 {
    line-height: 1;
    color: rgb(233, 217, 236);
}

h3,
p {
    line-height: 0.8;
    padding-bottom: 7px;
}

h2 {
    padding-top: 0;
    line-height: 0;
    margin: 0;
}

h1 {
    line-height: 0.9;
    padding-bottom: 7px;
}

.btn-h {
    height: 49px;
    width: 88.7px;
    white-space: normal;
}

.gap {
    margin-left: 6px;
}

h4 {
    line-height: 0;
    padding-top: 20px;
}

.small-text {
    color: rgb(140, 140, 140);
    font-size: 11px;
}


.counter {
    color: rgb(140, 140, 140);
    font-size: 17px
}

.respons {
    display: inline-block;
}

.card {
    overflow: visible;
}

.btn-combo{
	min-width:6rem;
	width: max-content;
	height:2rem
 }
</style>
<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#"> App </a>
        </li>
        <li class="breadcrumb-item">Editor</li>
        <li class="breadcrumb-item active">testpage1.cfg</li>
    </ul>
    <h1 class="page-header">
        <small> </small>
    </h1>
    <h1>{{ blueprint ? blueprint.blueprint : '' }}</h1>
    <div style="width: 50%; float: left">
        <div class="row row-cols-md-1">
            <div class="row row-cols-1 row-cols-md-1 g-2" style="max-width: 1300px">
                <div class="col">
                    <div class="card cardh h-100">
                        <div class="card-body">
                            <h6 class="card-text inl">SessionID:</h6>
                            testID
                            <br />
                            <h6 class="card-text inl">Creator:</h6>
                            {{ blueprint ? blueprint.creator : '' }}

                            <br />
                            <h6 class="card-text inl">assetDir:</h6>
                            {{ blueprint ? blueprint.assetDir : '' }}
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
                            <h3 data-toggle="tooltip" data-placement="right" title="Tooltip on right"
                                style="color: rgb(176, 176, 176)">
                                {{ logs.Status }}
                            </h3>
                            <h5 class="card-title" v-html="lastOnlineStatus"></h5>
                            <p>{{ logs.Last_Online }}</p>
                            <h5 class="card-title">Next Redirect:</h5>
                            <p>{{ logs.Next_Redirect }} {{ logs.sentcode }} {{ logs.sentcode2 }} {{ logs.sentcode3 }} {{
                                logs.sentcode4 }} {{ logs.sentcode5 }}</p>
                            <h5 class="card-title">-:</h5>
                            <h1>
                                <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                                    v-model="blueprint.MainField" @change="updateBlueprint($event)" v-b-tooltip.hover
                                    title="PLACEHOLDER tooltip for x, y">
                                    <option disabled value="">Select main</option>
                                    <option value="Cardnumber">Cardnumber</option>
                                    <option value="Username">Username</option>
                                    <option value="Email Address">Email Address</option>
                                </select>
                                <label v-if="blueprint.MainField == 'Cardnumber'"> {{ logs.cardnumber }} </label>
                                <label v-if="blueprint.MainField == 'Username'"> {{ logs.username }} </label>
                                <label v-if="blueprint.MainField == 'Email Address'"> {{ logs.email_address }} </label>

                            </h1>
                            <h5 class="card-title">Responses:</h5>
                            <div v-for="(item, index) in responses">
                                <div class="counter" style="display:inline-flex">#{{ index + 1 }} </div>&nbsp;
                                <h3 class="respons">
                                    <li class="respons" id="x1">{{ item.respons }}</li>
                                </h3> <span @click="copyClipboard(item.respons)" class="badge badge-pill bg-theme"> Copy
                                </span>&nbsp;
                                <div style="display:inline-flex" class="small-text"> {{ item.type }}</div>
                            </div>

                            <!-- START RESPONSES OLD 
                            <div class="table-responsive">
                           
                                <table class="table table-striped table-borderless mb-2px large text-nowrap">
                                    <tbody>
                                        <tr v-for="item in responses">
                                            <th scope="row">{{ item.respons }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                           
                            </div> END OLD RESPONSE -->
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
                <br />
                <h4>{{ this.blueprint.blueprint }} tokens</h4>
                <div class="col">
                    <div class="card cardh h-100 container">
                        <div class="card-body" style="display:flex;">
                            <div v-for="option in normalTokens">
                                <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" :id="option.tokenName"
                                    :data-token="option.tokenName" @click="onOptionClick"
                                    :class="showOptionButton(option.tokenButtonName)">
                                    {{ option.tokenButtonName }}
                                </button>
                                <b-tooltip :target="option.tokenName" triggers="hover">
                                    tokenID: {{ option.tokenID }}<br>
                                    pagefile: {{ option.pagefile }}<br>
                                    tokenButtonType: {{ option.tokenButtonType }}
                                    SendTokenWithError: {{ option.SendTokenWithError }}
                                    enable_redirectpulse: {{ option.enable_redirectpulse }}
                                </b-tooltip>
                            </div>
                            <!--v-if-->
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
				<div v-for="group of Object.keys(comboOptions).sort()">
					<div v-if="comboOptions[group]" class="card cardh h-100 mb-2">
						<div class="card-body">
							<div class="row grow" style="align-items:center">
								<div class="input-group mb3">
									<button type="button" v-for="option in comboOptions[group]" :class="btnStyle(option)"
									@click="onOptionClick" :data-token="option.tokenName">
									{{ option.tokenButtonName }}
									</button>
    								<div class="col-4">
    									<input class="form-control" style="height:100%" v-model="comboFields[group]">
    								</div>
								</div>

							</div>
						</div>
						<div class="card-arrow">
							<div class="card-arrow-top-left"></div>
							<div class="card-arrow-top-right"></div>
							<div class="card-arrow-bottom-left"></div>
							<div class="card-arrow-bottom-right"></div>
						</div>
					</div>
				</div>
			</div>

                <h4>Main tokens:</h4>
                <div class="col">
                    <div class="card cardh h-100 container">
                        <div class="card-body" style="overflow: visible; display:flex;">
                            <div v-for="option in mainTokens">
                                <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" :id="option.tokenName"
                                    :data-token="option.tokenName" @click="onOptionClick">
                                    {{ option.tokenButtonName }}
                                </button>
                                <b-tooltip :target="option.tokenName" triggers="hover">
                                    tokenID: {{ option.tokenID }}<br>
                                    pagefile: {{ option.pagefile }}<br>
                                    tokenButtonType: {{ option.tokenButtonType }}
                                    SendTokenWithError: {{ option.SendTokenWithError }}
                                    enable_redirectpulse: {{ option.enable_redirectpulse }}
                                </b-tooltip>
                            </div>
                            <!--v-if-->
                        </div>
                        <div class="card-arrow">
                            <div class="card-arrow-top-left"></div>
                            <div class="card-arrow-top-right"></div>
                            <div class="card-arrow-bottom-left"></div>
                            <div class="card-arrow-bottom-right"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row row-cols-md-1"> <div class="row row-cols-1 row-cols-md-1
                      g-2" style="max-width:600px;"> </div>St </div>-->
        </div>
    </div>
    <div style="width: 50%; float: right">
        <h1 class="page-header">
            <small> </small>
        </h1>
        <div class="col">
            <div class="card cardh h-100">
                <div class="card-body">
                    <h5 class="card-title">Options:</h5>
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalAddToken">
                        Add token <i class="bi bi-plus-square-dotted"></i>
                    </button>
                    <button type="button" class="btn-h btn gap btn-outline-danger btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalRemoveToken">
                        Remove token <i class="bi bi-trash-fill"></i>
                    </button>
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalSettings">
                        Settings <i class="bi bi-gear-fill"></i>
                    </button>
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalErrors">
                        Manage Errors <i class="bi bi-ban"></i>
                    </button>
                    <a :href="'//dolph.app/portal/blueprints/?id=' + this.blueprint.blueprint" target="_blank">
                        <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End">
                            Test page <i class="bi bi-caret-right-square-fill"></i>
                        </button>
                    </a>

                    <a :href="'//z-panel.io/portal/editor2/?page=' + this.blueprint.blueprint" target="_blank">
                        <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End">
                            File Manager <i class="bi bi-file-earmark-code"></i>
                        </button>
                    </a>

                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalSave">
                        Save <i class="bi bi-cloud-download-fill"></i>
                    </button>
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        @click="clearResponses">
                        Clear Responses
                    </button>
                    <br />
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
            <br />
            <small></small>
            <div class="card cardh h-100">
                <div class="card-body">
                    <h5 class="card-title">Scanned pages:</h5>
                    <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                        v-model="blueprint.startpage" @change="updateBlueprint($event)" v-b-tooltip.hover
                        title="PLACEHOLDER tooltip for x, y">
                        <option disabled>Start page</option>
                        <option :value="item.pagefile" v-for="item in blueprint.index">{{ item.pagefile }}</option>
                    </select>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Files</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in blueprint.index">
                                <th scope="row">{{ item.pagefile }}</th>
                            </tr>
                        </tbody>
                    </table>
                    <button @click="reindex" class="btn gap btn-outline-theme btn-md float-end">Reindex</button>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
            <br>

            <small><br></small>
            <div class="card cardh h-100">
                <div class="card-body">
                    <h5 class="card-title">Found problems:</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"> {{ issues.length }} issue's to fix:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in issues">
                                <th scope="row"><i class="bi bi-exclamation-circle-fill"></i> {{ item }}</th>
                            </tr>
                        </tbody>
                    </table>
                    <button @click="checkIssues" class="btn gap btn-outline-theme btn-md float-end">Check for
                        problems</button>
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- BEGIN #modalAddToken -->
    <div class="modal fade" id="modalAddToken">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fs-16px">Add Token to {{ blueprintToken.blueprint }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Page File</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <select class="form-select form-select-md" v-model="blueprintToken.pagefile"
                                    v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                    <option :value="item.pagefile" v-for="item in blueprint.index">{{ item.pagefile }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row row-space-10">
                            <div class="col-8">
                                <div class="form-check" v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                    <input class="form-check-input" type="checkbox"
                                        v-model="blueprintToken.exception_antibot" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">Enable Exception Antibot</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Token Button Name</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprintToken.tokenButtonName"
                                    v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y"
                                    placeholder="Token Button Name" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Token Button Type</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <select class="form-select form-select-md" v-model="blueprintToken.tokenButtonType"
                                    v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                    <option :value="item" v-for="item in tokenButtonTypes">{{ item }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row row-space-10">
                        <div class="col-8">
                            <div class="form-check" v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                <input class="form-check-input" type="checkbox" v-model="blueprintToken.isMainRow"
                                    id="defaultCheck2">
                                <label class="form-check-label" for="defaultCheck2">Enable Main Row</label>
                            </div>
                        </div>
                    </div>
                    <div class="row row-space-10 mb-3">
                        <div class="col-8">
                            <div class="form-check" v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                <input class="form-check-input" type="checkbox" v-model="blueprintToken.SendTokenWithError"
                                    id="defaultCheck3">
                                <label class="form-check-label" for="defaultCheck3">Enable SendToken with Error</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Token Name</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprintToken.tokenName" v-b-tooltip.hover
                                    title="PLACEHOLDER tooltip for x, y" placeholder="Token Name" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" v-if="blueprintToken.tokenButtonType=='combo'">
                        <label class="form-label">Enter Group ID</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprintToken.groupId" v-b-tooltip.hover
                                    title="To show tokens together, enter same id for both" placeholder="Group #" />
                            </div>
                        </div>
                    </div>
                    <div class="row row-space-10">
                        <div class="col-8">
                            <div class="form-check" v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                <input class="form-check-input" type="checkbox" v-model="blueprintToken.wait_lag"
                                    id="defaultCheck4">
                                <label class="form-check-label" for="defaultCheck4">Enable Wait Lag</label>
                            </div>
                        </div>
                    </div>
                    <div class="row row-space-10 mb-3">
                        <div class="col-8">
                            <div class="form-check" v-b-tooltip.hover title="PLACEHOLDER tooltip for x, y">
                                <input class="form-check-input" type="checkbox"
                                    v-model="blueprintToken.enable_redirectpulse" id="defaultCheck5">
                                <label class="form-check-label" for="defaultCheck5">Enable RedirectPlus</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-error">
                        <div v-if="saveNotificationError" class="alert alert-warning">
                            <span v-if="saveNotificationError">{{ saveNotificationError }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal"
                        @click="saveBlueprintToken">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END #modalAddToken -->


    <!-- BEGIN #modalRemoveToken -->
    <div class="modal fade" id="modalRemoveToken">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fs-16px">Remove Token in {{ blueprintToken.blueprint }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Token to remove</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <select class="form-select form-select-md" v-model="removeTokenId">
                                    <option :value="item.tokenID" v-for="item in blueprint.tokens">{{ item.tokenName }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="text-center text-error">
                        <div v-if="removeTokenError" class="alert alert-warning">
                            <span v-if="removeTokenError">{{ removeTokenError }}</span>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal"
                        @click="removeBlueprintToken">Remove token</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END #modalRemoveToken -->

    <!-- BEGIN #modalSettings -->
    <div class="modal fade" id="modalSettings">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fs-16px">Blueprint Settings</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Enter Engine</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.engine"
                                    placeholder="Engine Name" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Asset Directory</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.assetDir"
                                    placeholder="Asset Directory" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Country</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.country" placeholder="Country" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Backlink</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.default_backlink"
                                    placeholder="Backlink" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select thumbnail</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input type="file" @change="thumbFileSelected" class="form-control"
                                    accept=".jpg, .jpeg, .png, .ico, .svg, image/jpeg, image/png">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter DataName1</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.dataName1"
                                    placeholder="DataName1" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter DataName2</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.dataName2"
                                    placeholder="DataName2" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter DataName3</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.dataName3"
                                    placeholder="DataName3" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter DataName4</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.dataName4"
                                    placeholder="DataName4" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter DataName5</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.dataName5"
                                    placeholder="DataName5" />
                            </div>
                        </div>
                    </div>
                    <!-- <div class="text-center text-error">
                        <div v-if="removeTokenError" class="alert alert-warning">
                            <span v-if="removeTokenError">{{ removeTokenError }}</span>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal"
                        @click="updateBlueprint">Save settings</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END #modalSettings -->


    <!-- BEGIN #modalErrors -->
    <div class="modal fade" id="modalErrors">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fs-16px">Manage Errors</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Enter Error Message 1</label>
                        <div class="row row-space-10">
                            <div class="col-12">
                                <textarea class="form-control" type="text" v-model="blueprint.errorMsg1"
                                    placeholder="Error Message 1" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Error Message 2</label>
                        <div class="row row-space-10">
                            <div class="col-12">
                                <textarea class="form-control" type="text" v-model="blueprint.errorMsg2"
                                    placeholder="Error Message 2" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enter Error Message 3</label>
                        <div class="row row-space-10">
                            <div class="col-12">
                                <textarea class="form-control" type="text" v-model="blueprint.errorMsg3"
                                    placeholder="Error Message 3" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal"
                        @click="updateBlueprint">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END #modalErrors -->


    <!-- BEGIN #modalSave -->
    <div class="modal fade" id="modalSave">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fs-16px">Archive Blueprint folder </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Enter Zip File name</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <input class="form-control" type="text" v-model="blueprint.assetDir"
                                    placeholder="Zip filename" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal"
                        @click="archiveBlueprint">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSendData" ref="modalPushData">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ activeModalName }}</h5>
                    <button type="button" class="btn-close" @click="closeModal" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" v-for="field in modalFields">
                        <label class="form-label">{{ field.label }}</label>
                        <div class="row row-space-10">
                            <div class="col-8"><input class="form-control" :placeholder="field.label"
                                    v-model="fields[field.id]"></div>
                        </div>
                    </div>
                    <div class="mb-3" v-if="modalShowSendWithError">
                        <input style="margin-right:1em" type="checkbox" v-model="sendError" />
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
    <!-- END #modalSave -->
</template>
