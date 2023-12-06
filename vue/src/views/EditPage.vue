<script>
import { useEditorStore } from "@/stores/editorStore";
const editorStore = useEditorStore();

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
                enable_redirectpulse: false
            },
            tokenButtonTypes: [
                "standard",
                "error",
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
            issues: []
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

            console.log(this.blueprint.tokens);
            this.checkIssues();
        },
        async updateBlueprint(event) {
            let response = await editorStore.saveBlueprint(this.blueprint);
            console.log(response);
            if (response.status == "ok")
                this.loadBlueprintsInfo();
        },

        saveBlueprintToken: async function () {
            let response = await editorStore.addBlueprintToken(this.blueprintToken);
            console.log(response);
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
            console.log(this.logs);
            this.responses = await editorStore.loadResponses(this.blueprint);
            console.log(this.responses);

            setTimeout(() => {
                this.loadEditorData()
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
        }
    },
    async mounted() {
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
                            <h5 class="card-title">Last status:</h5>
                            <p>{{ logs.Last_Online }}</p>
                            <h5 class="card-title">Next Redirect:</h5>
                            <p>{{ logs.Next_Redirect }}</p>
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
                            <div class="table-responsive">
                                <!-- START RESPONSES-->
                                <table class="table table-striped table-borderless mb-2px large text-nowrap">
                                    <tbody>
                                        <tr v-for="item in responses">
                                            <th scope="row">{{ item.respons }}</th>
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
                </div>
                <br />
                <h4>demo_v1.0 tokens</h4>
                <div class="col">
                    <div class="card cardh h-100 container">
                        <div class="card-body">
                            <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End">
                                End
                            </button>
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
                <h4>Main tokens:</h4>
                <div class="col">
                    <div class="card cardh h-100 container">
                        <div class="card-body">
                            <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="name">
                                request name
                            </button>
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
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End">
                        Test page <i class="bi bi-caret-right-square-fill"></i>
                    </button>
                    <button type="button" class="btn-h btn gap btn-outline-theme btn-sm" data-token="End"
                        data-bs-toggle="modal" data-bs-target="#modalSave">
                        Save <i class="bi bi-cloud-download-fill"></i>
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
                    <h5 class="card-title">File manager:</h5>
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
                </div>
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
            </div>
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
                    <button @click="checkIssues" class="btn gap btn-outline-theme btn-md float-end">Check for problems</button>
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
                                <input class="form-control" type="text" v-model="blueprint.default_backlink" placeholder="Backlink" />
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
    <!-- END #modalSave -->
</template>
