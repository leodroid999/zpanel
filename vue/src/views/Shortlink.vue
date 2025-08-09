<script>
import { useSiteStore } from '@/stores/siteStore';
const siteStore = useSiteStore();
export default {
	data: function(){
    return {
      newUrlToAdd:"",
      newUrlDomain:"",
      newUrlShortlink:"",
      statusMessage:"",
      statusMessageEdit:"",
      shortLinkSelectedEdit:null
    }
  },
  methods:{
    loadDomainList: function(){
      siteStore.getShortlinkDomains()
		},
    loadShortlinks: function(){
      siteStore.getShortlinks()
		},
    addShortLink: async function(e){
      e.preventDefault()
      this.statusMessage=""
      if(!this.newUrlToAdd || !this.newUrlDomain || !this.newUrlShortlink){
        this.statusMessage="Please check all data is filled"
        return false
      }
      let result= await siteStore.addShortLink(this.newUrlDomain,this.newUrlToAdd,this.newUrlShortlink)
      if(result.status=="ok"){
        siteStore.getShortlinks()
      }
      if(result.message){
        this.statusMessage=result.message
      }
    },
    deleteShortLink: async function(link){
      if(!link){
        return false
      }
      let result= await siteStore.deleteShortLink(link)
      if(result.status=="ok"){
        siteStore.getShortlinks()
      }
      if(result.message){
        this.statusMessage=result.message
      }
    },
    editShortLink: function(link){
      this.statusMessageEdit=""
      this.shortLinkSelectedEdit= Object.assign({}, link)
    },
    cancelEditShortLink: function(){
      this.shortLinkSelectedEdit= null
    },
    saveShortLink: async function(e){
      e.preventDefault()
      if(!this.shortLinkSelectedEdit || !this.shortLinkSelectedEdit.destinationUrl){
        return false
      }
      let shortLink=this.shortLinkSelectedEdit;
      let result= await siteStore.editShortLink(shortLink.link,shortLink.destinationUrl)
      this.shortLinkSelectedEdit=null;
      if(result.status=="ok"){
        siteStore.getShortlinks()
      }
      if(result.message){
        this.statusMessageEdit=result.message
      }
    },
    isShortLinkInEdit: function(link){
      return this.shortLinkSelectedEdit!=null && this.shortLinkSelectedEdit.link == link.link
    }
  },
  computed:{
		domains: function(){
				return siteStore.shortlinkDomains
		},
    shortlinks: function(){
				return siteStore.shortlinks
		},
	},
	mounted() {
		this.loadDomainList()
    this.loadShortlinks();
  }
}
</script>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">東-panel</a></li>
		<li class="breadcrumb-item active">Shortlinks</li>
	</ul>
	<h1 class="page-header">
		 
	</h1>
<div class="card mb-3">
  <div class="card-body">

	<form class="row g-3" v-on:sumbit.prevent="send" v-on:submit="addShortLink">
		<div class="col-12">
  <h2>Add a shortlink</h2>
  <label for="inputAddress2" class="form-label"><b>Paste a long url</b></label>
    <input type="text" class="form-control" id="inputAddress2" v-model="newUrlToAdd" placeholder="example.com/loooonggurl">
  </div>
  <div class="col-md-4"> 
    <label for="inputState" class="form-label" ><b>Domain</b></label>
    <select id="inputState" class="form-select" v-model="newUrlDomain" placeholder="Select domain">
      <option disabled value="" selected>Select domain..</option>
      <option v-for="domain in domains">{{ domain.domain }}</option>
    </select>
  </div> 
  <div class="col-md-8">
    <label for="inputPassword4" class="form-label"><b>Enter short name</b></label>
    <input type="text" v-model="newUrlShortlink" placeholder="app-verifieer" class="form-control" id="inputPassword4">
  </div> 
  <div class="col-md-8 "><fieldset class="row mb-3">
    <legend class="col-form-label">Add protection?</legend>
    <div class="col-sm-10">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="protection" id="protection1" value="no" checked>
        <label class="form-check-label" for="protection1">
          No.
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="protection" id="protection2" value="blackTds">
        <label class="form-check-label" for="protection2">
          BlackTDS
        </label>
      </div>
      <div class="form-check disabled">
        <input class="form-check-input" type="radio" name="protection" id="protection3" value="zTds" disabled>
        <label class="form-check-label" for="protection3">
          Z-TDS
        </label>
      </div>
    </div>
  </fieldset>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-success">Add shortlink</button>
  </div>
  <span>{{ statusMessage }}</span>
</form>
</div>
  <div class="card-arrow">
    <div class="card-arrow-top-left"></div>
    <div class="card-arrow-top-right"></div>
    <div class="card-arrow-bottom-left"></div>
    <div class="card-arrow-bottom-right"></div>
  </div>
</div>
<table class="table">
  <thead>
    <th>Shortlink</th>
    <th>Destination</th>
  </thead>
  <tbody>
    <tr v-for="link in shortlinks">
      <td><a :href="'https://'+link.PreferedDomain+'/'+link.link" target="_blank">{{link.PreferedDomain}}/{{ link.link }}</a></td>
      <td v-if="!isShortLinkInEdit(link)"><a :href="'https://'+link.destinationUrl">{{ link.destinationUrl }}</a></td>
      <td v-if="isShortLinkInEdit(link)">
        <input type="text" class="form-control" v-model="this.shortLinkSelectedEdit.destinationUrl" placeholder="example.com/loooonggurl">
      </td>
      <td style="
            text-align: end;
            width: max-content;
        ">
        <button type="button" v-if="!isShortLinkInEdit(link)" v-on:click="editShortLink(link)" style="margin-right:1em" class="btn btn-warning btn-sm">Edit</button>
        <button type="button" v-if="isShortLinkInEdit(link)" v-on:click="cancelEditShortLink" style="margin-right:1em" class="btn btn-warning btn-sm">Cancel</button>
        <button type="button" v-if="isShortLinkInEdit(link)" v-on:click="saveShortLink" style="margin-right:1em" class="btn btn-warning btn-sm">Save</button>
        <button type="button" v-on:click="deleteShortLink(link.link)" class="btn btn-warning btn-sm">Delete</button>
      </td>
    </tr>
  </tbody>
</table>
<span>{{ statusMessageEdit }}</span>

	
</template>