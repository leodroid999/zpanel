<script>
import { useShopStore } from '@/stores/shopStore';
import { useUserStore } from '@/stores/userStore';
import { Modal } from "bootstrap";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import axios from 'axios';


const shopStore = useShopStore();
export default {
    components: { Datepicker },
    data: function () {
        return {
            selectedProduct: null,
            selectedDates: [],
            selectedStartTime: null,
            buyProductModal: null,
            buyErrorMessage: null,
            processingPurchase: false,
            postPurchase: false,
            searchTags: [],
            searchType: '',
            countries: ['NL', 'BE', 'FR', 'USA', 'DE', 'IT', 'AT'],
            searchCountries: [],
            selectedType: ""
        }
    },
    methods: {
        setTags(type) {
            this.searchType = type;
            if (type != undefined && type != 'all')
                this.searchTags = [type];
            else
                this.searchTags = [];
        },
        loadProducts: function () {
            shopStore.getProducts()
        },
        dayDifference: function (start, end) {
            let timeDifference = end.getTime() - start.getTime();
            let days = timeDifference / (1000 * 3600 * 24);
            return days;
        },
        openBuyProductModal: function (product) {
            this.processingPurchase = false;
            this.selectedProduct = product;
            let now = new Date()
            let nextDay = new Date();
            nextDay.setDate(now.getDate() + 1);
            this.selectedDates = [now, nextDay]
            this.selectedStartTime = {
                hours: new Date().getHours(),
                minutes: new Date().getMinutes()
            }
            this.buyProductModal.show();
        },
        buyProduct: async function () {
            this.processingPurchase = true;
            if (!this.selectedProduct.isDownload) {
                let start = this.selectedDates[0];
                let end = this.selectedDates[1];
                let days = this.dayDifference(start, end);
                if (days < 1) {
                    return;
                }
                start.setHours(this.selectedStartTime.hours)
                start.setMinutes(this.selectedStartTime.minutes)
                start.setSeconds(0)
                let response = await shopStore.buyProduct(false, this.selectedProduct.productID, start.getTime(), days);
            }
        },
        onBuy: async function (result) {
            if (result.error) {
                this.buyErrorMessage = result.message;
            } else {
                this.buyErrorMessage = null;
                const userStore = useUserStore();
                userStore.getUserInfo();
                this.loadProducts();
                let data = new FormData();
                data.append('userID', userStore.user.userId);
                data.append('content', `Successfully bought "${result['title']}" - $${result['price']} subtracted from balance.`);
                let options = {
                    method: "POST",
                    body: data,
                    credentials: 'include'
                };
                try {
                    let response = await fetch('https://dolph.app/portal/pushNotification.php', options);
                }
                catch (err) {
                    console.error(err);
                }
            }
        },
        renderTagColor(tagColor) {
            return "bg-" + tagColor;
        },
        addCountry(event) {
            var value = event.target.value;
            if (this.searchTags.indexOf(value) == -1)
                this.searchTags.push(value);
        },
        configTagType() {
            var menus = [undefined, "leads", "panels", "accounts", "cosmetics"];

            var selectedId = this.$route.params.id;
            this.selectedType = selectedId;
            console.log(selectedId);

            if (menus.indexOf(selectedId) != -1)
                this.setTags(selectedId);
        }
    },
    computed: {
        availableCountries() {
            return this.countries.filter(opt => this.searchCountries.indexOf(opt) === -1)
        },
        products() {
            console.log(shopStore.products);

            var products = shopStore.products;
            var self = this;
            this.searchCountries = [];

            if (this.searchTags.length) {
                for (var j = 0; j < self.searchTags.length; j++) {

                    if (self.countries.indexOf(self.searchTags[j]) != -1) {
                        this.searchCountries.push(self.searchTags[j]);
                        continue;
                    }

                    products = products.filter(function (product) {
                        var matched = false;
                        for (var i = 0; i < product.tags.length; i++) {

                            if (product.tags[i].tagLabel == self.searchTags[j]) {
                                matched = true;
                                break;
                            }
                        }
                        return matched;
                    })
                }
            }

            if (this.searchCountries.length) {
                for (var j = 0; j < self.searchCountries.length; j++) {
                    products = products.filter(function (product) {
                        var matched = false;
                        for (var i = 0; i < product.tags.length; i++) {

                            if (product.tags[i].tagLabel == self.searchCountries[j]) {
                                matched = true;
                                break;
                            }
                        }
                        return matched;
                    })
                }
            }

            return products
        },

        totalPrice() {
            if (this.selectedProduct) {
                let days = this.dayDifference(this.selectedDates[0], this.selectedDates[1])
                if (days < 1) {
                    return 0;
                }
                else {
                    return Math.round(this.selectedProduct.price * days * 100) / 100
                }
            }
            return 0;
        }
    },
    mounted() {
        this.configTagType();

        this.loadProducts()
        this.buyProductModal = new Modal(this.$refs.buyProductModal)
        shopStore.$onAction(({ name, store, args, after, onError }) => {
            let handler = () => { }
            if (name == "buyProduct") { handler = this.onBuy }
            after((result) => handler(result))
            onError((err) => handler(err))
        })
    },
    watch: {
        $route(to, from) {
            this.configTagType();
            // this.show = false;
        }
    }
}
</script>
<style>
.float-right {
    float: right;
}

.card-img-top {
    height: 10rem;

}

h4,
h5 {
    display: inline;
    padding-right: 5px;
}
</style>

<template>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Z-panel</a></li>
        <li class="breadcrumb-item">Shop</li>
        <li class="breadcrumb-item active">Panels</li>
    </ul>

    <ul class="nav nav-tabs nav-tabs-v2">
        <li class="nav-item me-4"><a href="#home" class="nav-link" :class="selectedType == undefined ? 'active' : ''"
                data-bs-toggle="tab" @click="setTags('all')">All</a></li>
        <li class="nav-item me-4"><a href="#profile" class="nav-link" :class="selectedType == 'leads' ? 'active' : ''"
                data-bs-toggle="tab" @click="setTags('leads')">Leads</a></li>
        <li class="nav-item me-4"><a href="#profile" class="nav-link" :class="selectedType == 'panels' ? 'active' : ''"
                data-bs-toggle="tab" @click="setTags('panels')">Panels</a></li>
        <li class="nav-item me-4 dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                :class="selectedType == 'accounts' || selectedType == 'cosmetics' ? 'active' : ''">
                Other
            </a>
            <div class="dropdown-menu">
                <a href="#dropdown1" class="dropdown-item" data-bs-toggle="tab" @click="setTags('accounts')">Accounts</a>
                <a href="#dropdown2" class="dropdown-item" data-bs-toggle="tab" @click="setTags('cosmetics')">Cosmetics</a>
            </div>
        </li>
    </ul>
    <div class="tab-content pt-3">
        <div class="tab-pane fade show active" id="home">

        </div>
        <div class="tab-pane fade" id="profile"></div>
        <div class="tab-pane fade" id="dropdown1"></div>
        <div class="tab-pane fade" id="dropdown2"></div>
    </div>
    <div>

    </div>

    <select class="form-select" aria-label="Default select example" @change="addCountry($event)">
        <option selected disabled>Filter on Country</option>
        <option v-for="country in availableCountries" :value="country">{{ country }}</option>
    </select>

    <!-- START tags-->
    <h1 class="page-header"><small></small></h1>
    <p class="tags-input-container">

    <div>
        <label for="tags-basic"><b>Filter based on tags:</b><br></label>
        <b-form-tags input-id="tags-basic" v-model="searchTags"></b-form-tags>
    </div>
    </p>
    <!-- <button type="button" class="btn btn-outline-theme btn-lg" @click="filter">Filter</button> -->

    <!-- END tags-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
        <div class="col" v-if="products" v-for="product in products">
            <div class="card">
                <div class="card-arrow">
                    <div class="card-arrow-top-left"></div>
                    <div class="card-arrow-top-right"></div>
                    <div class="card-arrow-bottom-left"></div>
                    <div class="card-arrow-bottom-right"></div>
                </div>
                <img :src="'/portal@/assets/products/' + product.productID + '.png'" class="card-img-top"
                    style="z-index:-2" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ product.title }}</h5>
                    <span class="float-right text-purple border-purple border rounded px-2"><b><i
                                class="bi bi-person"></i>{{ product.creatorName }}</b></span>
                    <p class="card-text">{{ product.shortDescription }}</p>
                    <div class="button-container">
                        <a href="#" class="btn btn-danger float-right">
                            <i class="float-right fas fa-shopping-cart" @click="openBuyProductModal(product)"></i>
                        </a>
                    </div>
                    <h4>{{ product.price }}$</h4>
                    <span class="badge" v-if="product.tags" v-for="tag in product.tags"
                        :class="renderTagColor(tag.tagColor)">{{ tag.tagLabel }}</span>
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
                        <h2>Panel: {{ this.selectedProduct.title }}</h2>
                        <div class="mb-4">
                            <h4 style="color:lightgray">{{ this.selectedProduct.price }}$/d</h4>
                        </div>
                        <div v-if="selectedType != 'accounts' && selectedType !='leads' && selectedType != 'file'">
                            <div class="mb-3">
                                <label class="form-label">Select Panel Active Dates:</label>
                                <div class="row row-space-10">
                                    <div class="col-8">
                                        <Datepicker v-model="selectedDates" range placeholder="Select date..."
                                            :min-date="new Date()" :enable-time-picker="false" :partial-range="false" dark>
                                        </Datepicker>
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
                            <span>Setting the time to any previous time today will start the panel immediately, the time
                                will be
                                adjusted to current time for expiry</span>
                            <div class="mt-4">
                                <h4 style="color:lightgray">Total: {{ totalPrice }}$</h4>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="!buyErrorMessage">
                            <h5 style="color:lightgray;display:inline-block">Your purchase is being processed. Please wait.
                            </h5>
                        </div>
                        <div v-else>
                            <h5 style="color:lightgray;display:inline-block">{{ buyErrorMessage }}</h5>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-outline-theme" @click="buyProduct"
                        v-if="!processingPurchase">Buy</button>
                </div>
            </div>
        </div>
    </div>
</template>