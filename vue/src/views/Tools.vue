
<script>
import { useAStore } from '@/stores/aStore';
const aStore = useAStore();
export default {
    data: function(){
		return {
			selectedScript:"erc20flasher",
            erc20flasher:{
                token:"DAI",
                from:"?",
                destination:"",
                amount:""
            }
		}
	},
    methods:{

    },
    computed:{

    },
    mounted(){
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
        <li class="breadcrumb-item"><a href="#">東-panel</a></li>
        <li class="breadcrumb-item active">Tools</li>
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
                    <h5 class="card-title">Tools</h5>
                    <div class="mb-3">
                        <label class="form-label">Select Tool</label>
                        <div class="row row-space-10">
                            <div class="col-8">
                                <select class="script-select" v-model="selectedScript">
                                    <option value="erc20flasher">ERC-20 Tx Flasher</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div v-if="selectedScript == 'erc20flasher'">
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Token</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <select class="script-select" v-model="erc20flasher.token">
                                        <option value="DAI">DAI</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Your address</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <span class="w-100">{{erc20flasher.from}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Destination</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="erc20flasher.destination" type="text"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <label class="form-label">Amount</label>
                                </div>
                            </div>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <input class="w-100" v-model="erc20flasher.destination" type="text"/>
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