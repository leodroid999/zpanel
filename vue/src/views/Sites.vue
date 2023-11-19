<script>
import { useSiteStore } from '@/stores/siteStore';
const siteStore = useSiteStore();
import { ScrollSpy } from 'bootstrap';
import { Modal } from "bootstrap";
export default {
  data: function () {
    return {}
  },
  methods: {
    loadSiteList: function () {
      siteStore.getSites();
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
    }
  },
  computed: {
    sites: function () {
      return siteStore.sites
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
    <a href="#" style="float: right; bottom: 0; margin-left: 5px" class="btn btn-outline-theme">Add Site
      up</a>
  </div>

<div style="padding: 0px; margin: 0px">
  <a href="#" style="float: right; bottom: 0; margin-left: 5px" class="btn btn-outline-theme">Clear Sites</a>
</div></template>