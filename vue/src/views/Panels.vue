<script>
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
export default {
    data: function () {
        return {
            hostPanelModal: null,
            panelSettingsModal: null,
            panelAccessModal: null,
            currentPanelSettings: null,
            currentPanelAccessList: null,
            currentPanelAccessUser: null,
            selectedPanel: null,
            selectedNode: null,
            showOutput: false,
            hostPanelHost: "",
            hostPanelUsername: "",
            hostPanelPassword: "",
            loadPanelDataError: false,
            loadPanelAccessListError: null,
            currentUserSelected: null,
            currentAccessModalSection: null,
            settingSaveMessage: "",
            addUserUsername: "",
            addUserPassword: "",
            addUserAccess: "view",
            editUserPassword: "",
            removeUserPassword: "",
            panelAccessSaveError: ""
        }
    },
    methods: {
        loadPanelList: async function () {
            await sessionStore.getPanelList();
        },
        loadPanelSettings: function () {
            var scope = this;
            sessionStore.panels.forEach(async function (panel) {
                let data = await sessionStore.getPanelSettings(panel.panelId, panel.nodeID)
                if (data.status == "ok") {
                    panel.settings = data.settings;
                } else {
                    loadPanelDataError = true
                }
            });
            console.log(sessionStore.panels);
        },
        updateRedirectAll: async function (panelId, nodeID) {
            let panel = sessionStore.panels.find(item => item.panelId == panelId && item.nodeID == nodeID)
            panel.settings.Redirect_all = !panel.settings.Redirect_all;
            console.log(panel);
            
            let result = await sessionStore.savePanelSettings(panelId, panel.NodeName, panel.settings);
            if (result.status == "ok") {
                this.settingSaveMessage = "Saved"
            } else {
                this.settingSaveMessage = result.message ? result.message : "error"
            }
        },
        checkEnabled: function (panelId, nodeID) {
            let panel = sessionStore.panels.find(item => item.panelId == panelId && item.nodeID == nodeID)
            return panel && panel.settings ? panel.settings.Redirect_all : false;
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
                const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
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
                    console.log(this.currentPanelSettings);
                } else {
                    this.loadPanelDataError = true
                }
            }
            catch (e) {
                this.loadPanelDataError = true
            }
            this.panelSettingsModal.show()
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
        saveSettings: async function () {
            let result = await sessionStore.savePanelSettings(this.selectedPanel, this.selectedNode, this.currentPanelSettings)
            if (result.status == "ok") {
                this.settingSaveMessage = "Saved"
            } else {
                this.settingSaveMessage = result.message ? result.message : "error"
            }
        },
        readOnlySettings: function () {
            return this.currentPanelSettings.access && this.currentPanelSettings.access != "full"
        }
    },
    computed: {
        panels: function () {
            return sessionStore.panels
        },
        scriptOutput: function () {
            return sessionStore.scriptOutput
        },
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
                                    v-if="!panel.access || panel.access == 'full'"
                                    @click="openHostModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-upload"></i></button>
                                <button class="btn btn-outline-success btn-sm"
                                    @click="openPanelSettingsModal(panel.panelId, panel.NodeName)"
                                    style="width: 30px; height: 30px; margin-right:2px;">
                                    <i class="fas me-2 fa-gear"></i></button>
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
                            :disabled="checkDisabled(panel.panelId, panel.nodeID)">

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
                            <div v-if="'antibot_active' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="currentPanelSettings.antibot_active" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label" for="antibotEnabled">Enable Antibot</label>
                                </div>
                            </div>
                            <div v-if="'mobile_only' in currentPanelSettings">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input"
                                        v-model="currentPanelSettings.mobile_only" :disabled="this.readOnlySettings()"
                                        :true-value="1" :false-value="0">
                                    <label class="form-check-label" for="mobileOnly">Mobile Only</label>
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
    </table>

    <!-- TABLE end -->
</template>