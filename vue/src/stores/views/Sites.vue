<script>
import { useSiteStore } from '@/stores/siteStore';
import { ScrollSpy } from 'bootstrap';
import { Modal } from "bootstrap";
export default {
  data: function () {
    const siteStore = useSiteStore();

    return {
      siteStore,
      site: {
        domain: "",
        panelId: "",
        hostStatus: "",
        currentIp: ""
      },
      statusMessage: "",
      oldSites: [],
    }
  },
  methods: {
    clearOldSites: async function () {
      var result = await this.siteStore.clearOldSites();

      console.log(result);
      if (result.status == "ok") {
        this.siteStore.getSites();
      }
      if (result.message) {
        this.statusMessage = result.message
      }
    },

    getOldSites: async function () {
      await this.siteStore.getOldSites();
      this.oldSites = this.siteStore.oldSites;
      console.log(this.oldSites);
    },

    loadSiteList: function () {
      this.siteStore.getSites();
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
    siteClass: function (site) {
      if (site.hostStatus == "ONLINE") {
        return "online"
      }
      if (site.hostStatus == "SSL_DOWN") {
        return "warning"
      }
      return "offline";
    },

    async addSite() {
      var result = await this.siteStore.addSite(this.site);

      if (result.status == "ok") {
        this.siteStore.getSites();
      }
      if (result.message) {
        this.statusMessage = result.message
      }
    }
  },
  computed: {
    sites: function () {
      return this.siteStore.sites
    },
  },
  mounted() {
    this.loadSiteList()
    new ScrollSpy(document.body, {
      target: '#sidebar-bootstrap',
      offset: 200
    })
  }
}
</script>
<style>
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

.warning {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background-color: gold;
  white-space: nowrap;
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;
}


.offline {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background-color: rgb(191, 69, 69);
  white-space: nowrap;
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;

}

.warning {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background-color: rgb(218, 218, 0);
  white-space: nowrap;
  display: inline-block;
  vertical-align: middle;
  margin-right: 10px;

}
</style>
<template>
  <ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
    <li class="breadcrumb-item active">Sites</li>
  </ul>
  <h4>Sites Active
    <small></small>
  </h4>

  <!-- TABLE start -->
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Domain</th>
        <th scope="col">PanelId</th>
        <th scope="col">NodeId</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody v-if="sites">
      <tr v-for="site in sites">
        <td>
          <div :class="siteClass(site)"></div><b>{{ site.domain }}</b>
        </td>
        <td>{{ site.panelId }}</td>
        <td>{{ site.nodeId }}</td>
        <td>{{ site.hostStatus }} </td>
      </tr>
    </tbody>
  </table>

  <!-- TABLE end -->

  <div style="padding: 0px; margin: 0px">
    <a href="#modalAddSite" data-bs-toggle="modal" style="float: left; bottom: 0; margin-left: 5px"
      class="btn btn-outline-theme">Add Site</a>
  </div>

  <div style="padding: 0px; margin: 0px">
    <a href="#modalClearOldSites" style="float: left; bottom: 0; margin-left: 5px" data-bs-toggle="modal"
      class="btn btn-outline-default" @click="getOldSites">Clear
      Old Sites</a>
  </div>

  <!-- <div class="text-center text-error">
    <div v-if="statusMessage" class="alert alert-warning">
      <span v-if="statusMessage">{{ statusMessage }}</span>
    </div>
  </div> -->

  <div class="modal fade" id="modalClearOldSites">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Clear old offline sites</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label class="form-label" v-if="!oldSites.length">
            There are no offline sites during 14 days
          </label>
          <div v-else>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Domain</th>
                  <th scope="col">PanelId</th>
                  <th scope="col">NodeId</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="site in oldSites">
                  <td>
                    <div :class="siteClass(site)"></div><b>{{ site.domain }}</b>
                  </td>
                  <td>{{ site.panelId }}</td>
                  <td>{{ site.nodeId }}</td>
                  <td>{{ site.hostStatus }} </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal" @click="clearOldSites">Clear Sites</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAddSite">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Site</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Domain</label>
            <div class="row row-space-10">
              <div class="col-8">
                <input class="form-control" placeholder="Domain" v-model="site.domain" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Panel ID</label>
            <div class="row row-space-10">
              <div class="col-8">
                <input class="form-control" placeholder="Panel ID" v-model="site.panelId" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Host Status</label>
            <div class="row row-space-10">
              <div class="col-8">
                <input class="form-control" placeholder="Host Status" v-model="site.hostStatus" />
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Current IP</label>
            <div class="row row-space-10">
              <div class="col-8">
                <input class="form-control" placeholder="Current IP" v-model="site.currentIp" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-outline-theme" data-bs-dismiss="modal" @click="addSite">Save changes</button>
        </div>
      </div>
    </div>
  </div>
</template>