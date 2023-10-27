
<script>
import { useShopStore } from '@/stores/shopStore';
import { Modal } from "bootstrap";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const shopStore = useShopStore();
export default {
    components: { Datepicker },
    data: function(){
		return {
			selectedProduct:null,
            selectedDates:[],
            selectedStartTime:null,
            buyProductModal:null,
            buyErrorMessage:null,
            processingPurchase:false,
            postPurchase:false,
		}
	},
    methods:{
        loadProducts: function(){
            shopStore.getProducts()
        },
        dayDifference: function(start,end){
            let timeDifference = end.getTime() - start.getTime();
            let days = timeDifference / (1000 * 3600 * 24);
            return days;
        },
        openBuyProductModal: function(product){
           this.processingPurchase=false;
           this.selectedProduct=product;
           let now=new Date()
           let nextDay=new Date();
           nextDay.setDate(now.getDate()+1);
           this.selectedDates=[now,nextDay]
           this.selectedStartTime={
            hours: new Date().getHours(),
            minutes: new Date().getMinutes()
           }
           this.buyProductModal.show();
        },
        buyProduct:function(){
            this.processingPurchase=true;
            if(!this.selectedProduct.isDownload){
                let start =this.selectedDates[0];
                let end=this.selectedDates[1];
                let days = this.dayDifference(start,end);
                if(days<1){
                    return;
                }
                start.setHours(this.selectedStartTime.hours)
                start.setMinutes(this.selectedStartTime.minutes)
                start.setSeconds(0)
                shopStore.buyProduct(false,this.selectedProduct.productID,start.getTime(),days)
            }
        },
        onBuy:function(result){
            if(result.error){
                this.buyErrorMessage=result.message;
            }
        }
    },
    computed:{
        products(){
            return shopStore.products
        },
        totalPrice(){
            if(this.selectedProduct){
                let days=this.dayDifference(this.selectedDates[0],this.selectedDates[1])
                if(days<1){
                    return 0;
                }
                else { 
                    return Math.round(this.selectedProduct.price*days*100)/100
                }
            }
            return 0;
        },
    },
    mounted(){
        this.loadProducts()
        this.buyProductModal = new Modal(this.$refs.buyProductModal)
        shopStore.$onAction(({name, store, args, after, onError})=>{
			let handler=()=>{}
			if(name=="buyProduct"){handler=this.onBuy}
			after((result)=>handler(result))
			onError((err)=>handler(err))
		})
    }
}
</script>
<style>
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
        <li class="breadcrumb-item">Shop</li>
        <li class="breadcrumb-item active">Panels</li>
    </ul>
    <h1 class="page-header"><small></small></h1>
    <p></p>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div class="col" v-if="products" v-for="product in products">
            <div class="card">
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <img :src="'//z-panel.io/portal/assets/products/'+product.productID+'.png'" class="card-img-top" style="z-index:-2" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ product.title }}</h5>
                    <span class="float-right text-purple border-purple border rounded px-2"><b><i class="bi bi-person"></i>{{ product.creatorName }}</b></span>
                    <p class="card-text">{{ product.shortDescription }}</p>
                    <div class="button-container">
                        <a href="#" class="btn btn-danger float-right">
                            <i class="float-right fas fa-shopping-cart" @click="openBuyProductModal(product)"></i>
                        </a>
                    </div>
                    <h4>{{product.price}}$</h4> <span class="badge bg-dark">Bank</span> <span class="badge bg-light">Live</span> 
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="buyProductModal" ref="buyProductModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Buy Panel</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body" v-if="this.selectedProduct">
                     
                     <div v-if="!processingPurchase">
                        <h2>Panel: {{this.selectedProduct.title}}</h2>
                        <div class="mb-4">
                        <h4 style="color:lightgray">{{this.selectedProduct.price}}$/d</h4>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Panel Active Dates:</label>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <Datepicker v-model="selectedDates" range 
                                    placeholder="Select date..." :min-date="new Date()" :enable-time-picker="false" :partial-range="false" dark></Datepicker>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Start Time:</label>
                            <div class="row row-space-10">
                                <div class="col-8">
                                    <Datepicker v-model="selectedStartTime" time-picker
                                    placeholder="Select start time..." dark></Datepicker>
                                </div>
                            </div>
                        </div>
                        <span>Setting the time to any previous time today will start the panel immediately, the time will be adjusted to current time for expiry</span>
                        <div class="mt-4">
                            <h4 style="color:lightgray">Total: {{totalPrice}}$</h4>
                        </div>
                     </div>
                     <div v-else>
                        <div v-if="!buyErrorMessage">
                            <h5 style="color:lightgray;display:inline-block">Your purchase is being processed. Please wait.</h5>
                        </div>
                        <div v-else>
                            <h5 style="color:lightgray;display:inline-block">{{ buyErrorMessage }}</h5>
                        </div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-default"  data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-outline-theme"  @click="buyProduct" v-if="!processingPurchase">Buy</button>
				</div>
			</div>
		</div>
	</div>
</template>