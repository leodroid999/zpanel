<script>
import { useWalletStore } from '@/stores/walletStore';

const walletStore = useWalletStore();
export default {
  methods: {
    loadWalletInfo: function() {
      walletStore.loadWallet();
    },
  },
  computed: {
    walletData: function() {
      if (walletStore.walletData) {
        const balance = (walletStore.walletData.balance / 100).toFixed(2);
        const formattedBalance = balance.toString().replace('.', '.');
        return Object.assign({}, walletStore.walletData, { balance: formattedBalance });
      }
      return null;
    },
  },
  mounted() {
    this.loadWalletInfo();
  },
};
</script>

<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item active">Wallet</li>
	</ul>
	<h1 class="page-header">
	Balance: <span v-if="walletData">{{walletData.balance}} $ </span>
	</h1>
	<card>
  <card-body>
    <div class="row gx-0 align-items-center">
      <div class="col-md-3 justify-content-center">
        <img v-if="walletData" :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+walletData.wallets.btc.address" style="height:150px; width:auto; " alt="" class="card-img rounded-0">
      </div>
      <div class="col-md-auto">
        <card-body>
          <h5 class="card-title">Bitcoin</h5>
          <p class="card-text"><span v-if="walletData">{{walletData.wallets.btc.address}}</span></p>
          <p class="card-text"><small class="text-muted">You can top up any amount. we take a small 1.5% fee for processing your bitcoin top up.</small></p>
        </card-body>
      </div>
    </div>
  </card-body>
  
</card>	
<br><card>
  <card-body>
    <div class="row gx-0 align-items-center">
      <div class="col-md-3 justify-content-center">
        <img v-if="walletData" :src="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+walletData.wallets.eth.address" style="height:150px; width:auto; " alt="" class="card-img rounded-0">
      </div>
      <div class="col-md-auto">
        <card-body>
          <h5 class="card-title">Ethereum</h5>
          <p class="card-text"><span v-if="walletData">{{walletData.wallets.eth.address}}</span></p>
          <p class="card-text"><small class="text-muted">You can top up any amount. we take a small 1.5% fee for processing your ethereum top up.</small></p>
        </card-body>
      </div>
    </div>
  </card-body>
  
</card>
<br><h5>Unconfirmed BTC transactions</h5>
<!-- default table -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">TXID</th>
      <th scope="col">Address</th>
      <th scope="col">Amount BTC</th>
    </tr>
  </thead>
  <tbody v-if="walletData">
    <tr v-for="tx in walletData.wallets.btc.pending_tx" >
      <th>{{tx.hash}}</th>
      <td>{{tx.address}}</td>
      <td>{{parseFloat(tx.valueBtc/1e8)}}</td>
    </tr>
    <span v-if="!walletData.wallets.btc.pending_tx">No unconfirmed BTC txs</span>
  </tbody>
</table>
<br><h5>Unconfirmed ETH transactions</h5>
<!-- default table -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">TXID</th>
      <th scope="col">Address</th>
      <th scope="col">Amount ETH</th>
    </tr>
  </thead>
  <tbody v-if="walletData">
    <tr v-for="tx in walletData.wallets.eth.pending_tx" >
      <th>{{tx.hash}}</th>
      <td>{{tx.address}}</td>
      <td>{{parseFloat(tx.valueEth/1e18)}}</td>
    </tr>
    <span v-if="!walletData.wallets.eth.pending_tx">No unconfirmed ETH txs</span>
  </tbody>
</table>
<br><h5>BTC Transactions</h5>
<!-- default table -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">TXID</th>
      <th scope="col">Address</th>
      <th scope="col">Amount BTC</th>
      <th scope="col">Amount USD</th>
    </tr>
  </thead>
  <tbody v-if="walletData">
    <tr v-for="tx in walletData.wallets.btc.tx_history" >
      <th>{{tx.hash.substr(0, 6)}}...{{tx.hash.substr(-6)}}</th>
      <th>{{tx.address.substr(0, 6)}}...{{tx.address.substr(-6)}}</th>
      <td>{{parseFloat(tx.valueBtc/1e8)}}</td>
      <td>{{tx.valueUsd ? (tx.valueUsd / 100).toFixed(2).replace('.', '.') : '0'}}</td>
    </tr>
    <span v-if="walletData.wallets.btc.tx_history.length ==0">No confirmed BTC txs</span>
  </tbody>
</table>
<br><h5>ETH Transactions</h5>
<!-- default table -->
<table class="table">
  <thead>
    <tr>
      <th scope="col">TXID</th>
      <th scope="col">Address</th>
      <th scope="col">Amount ETH</th>
      <th scope="col">Amount USD</th>
    </tr>
  </thead>
  <tbody v-if="walletData">
    <tr v-for="tx in walletData.wallets.eth.tx_history" >
      <th>{{tx.hash.substr(0, 6)}}...{{tx.hash.substr(-6)}}</th>
      <th>{{tx.address.substr(0, 6)}}...{{tx.address.substr(-6)}}</th>
      <td>{{parseFloat(tx.valueEth/1e18)}}</td>
      <td>{{tx.valueUsd ? (tx.valueUsd / 100).toFixed(2).replace('.', '.') : '0'}}</td>
    </tr>
    <span v-if="walletData.wallets.eth.tx_history.length ==0">No confirmed ETH txs</span>
  </tbody>
</table>
<small>Note: 1 confirmation is required, come back to this page to see your payment.</small>

	<p>
	
	</p>
</template>