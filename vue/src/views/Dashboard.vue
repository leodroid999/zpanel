<script>
import { useAppVariableStore } from '@/stores/app-variable';
import { useStatStore } from '@/stores/statStore';
import { useSessionStore } from '@/stores/sessionStore';
import apexchart from '@/components/plugins/Apexcharts.vue';
import jsVectorMap from 'jsvectormap';
import 'jsvectormap/dist/maps/world.js';
import 'jsvectormap/dist/css/jsvectormap.min.css';
import { ref } from 'vue'
import { useUserStore } from '@/stores/userStore';
import { useSiteWidgetStore } from '@/stores/siteWidgetStore';
import { useAppOptionStore } from '@/stores/app-option';

const appVariable = useAppVariableStore();
const sessionStore = useSessionStore();
const statStore = useStatStore();
const userStore = useUserStore();
const siteWidgetStore = useSiteWidgetStore();
const appOption = useAppOptionStore();

export default {
	components: {
		apexchart: apexchart
	},
	data() {
		return {
			renderComponent: true,
			osChartKey: 0,
			chartCfg: {
				"osChart": {
					height: 70,
					options: {
						chart: {
							id: 'osChart',
							type: 'donut',
							sparkline: { enabled: true }
						},
						theme: {
							monochrome: {
								enabled: true,
								color: appVariable.color.theme,
								shadeTo: 'dark',
								shadeIntensity: 0.65
							}
						},
						stroke: {
							show: false,
							curve: 'smooth',
							lineCap: 'butt',
							colors: 'rgba(' + appVariable.color.blackRgb + ', .25)',
							width: 2, dashArray: 0
						},
						plotOptions: {
							pie: {
								donut: { background: 'transparent' }
							}
						}
					},
				}
			}
		}
	},
	methods: {
		async setThemeClass(themeClass) {
			for (var x = 0; x < document.body.classList.length; x++) {
				var targetClass = document.body.classList[x];
				if (targetClass.search('theme-') > -1) {
					document.body.classList.remove(targetClass);
				}
			}

			document.body.classList.add(themeClass);


			let result = await userStore.saveThemeColor(themeClass);
			if (!result || (result.status != "ok" && !result.message)) {
				this.saveThemecolorError = "There was an error, try again"
			}
			if (result && result.message) {
				this.saveThemecolorError = result.message
			}

			// this.emitter.emit('theme-reload', true);
			// this.reloadVariable();
		},

		getOsData() {
			if (statStore.stats && statStore.stats.os) {
				return statStore.stats.os.map(os => os.Count)
			}
			return []
		},
		getMarkersData() {
			let results = []
			if (statStore.stats && statStore.stats.coords) {
				for (let coord of statStore.stats.coords) {
					results.push({ name: "", coords: [coord.lat, coord.lon] })
				}
			}
			return results;
		},
		getSelectedCountries() {
			if (statStore.stats && statStore.stats.locations) {
				let countries = statStore.stats.locations.map(loc => loc.Country)
				return [...new Set(countries)]
			}
			return []
		},

		loadSiteWidgetList() {
			siteWidgetStore.getWidgetSites();
		},

		renderMap() {
			document.getElementById('map-container').innerHTML = '<div id="map"></div>';
			var map = new jsVectorMap({
				selector: '#map',
				map: 'world',
				zoomButtons: true,
				normalizeFunction: 'polynomial',
				hoverOpacity: 0.5,
				hoverColor: false,
				zoomOnScroll: false,
				series: { regions: [{ normalizeFunction: 'polynomial' }] },
				labels: { markers: { render: (marker) => marker.name } },
				focusOn: { x: 0.5, y: 0.5, scale: 1 },
				markers: this.getMarkersData(),
				markerStyle: { initial: { fill: appVariable.color.theme, stroke: 'none', r: 5 }, hover: { fill: appVariable.color.theme } },
				markerLabelStyle: {
					initial: {
						fill: 'rgb(200,200,200)',
						stroke: 'rgb(170,170,170)',
						strokeWidth: '0.5px'
					}
				},
				regionStyle: {
					initial: {
						fill: 'rgba(255, 255, 255, 0.75)', fillOpacity: 0.35, stroke: 'none', strokeWidth: 0.4, strokeOpacity: 1
					},
					hover: { fillOpacity: 0.5 },
					selected: {
						fill: appVariable.color.theme, fillOpacity: 0.6, stroke: 'none', strokeWidth: 0.4, strokeOpacity: 1
					},
				},
				backgroundColor: 'transparent',
				selectedRegions: this.getSelectedCountries()
			});
		},
		onPanelChanged() {
			let panel = sessionStore.selectedPanel;
			statStore.getStats(panel.panelId, panel.nodeID);
		},
		timeAgo: function (str) {
			;
			const date = new Date(str + "Z").getTime()
			const seconds = Math.floor(new Date().getTime() - date) / 1000;

			let interval = Math.floor(seconds / 31536000);
			if (interval > 1) {
				return interval + ' years ago';
			}

			interval = Math.floor(seconds / 2592000);
			if (interval > 1) {
				return interval + ' months ago';
			}

			interval = Math.floor(seconds / 86400);
			if (interval > 1) {
				return interval + ' days ago';
			}

			interval = Math.floor(seconds / 3600);
			if (interval > 1) {
				return interval + ' hours ago';
			}

			interval = Math.floor(seconds / 60);
			if (interval > 1) {
				return interval + ' minutes ago';
			}

			if (seconds < 10) return 'just now';

			return Math.floor(seconds) + ' seconds ago';
		},
		calcPercent(location) {
			try {
				let total = statStore.stats.locations.reduce((a, b) => a + b.Count, 0)
				let percent = (location.Count * 100 / total).toFixed(2)
				return percent
			}
			catch {
				return "?";
			}
		},
		renderOSChart() {
			this.osChartKey + 1;
		},
		onPanelSelect(ev) {
			let selectedValue = ev.target.value;
			if (selectedValue == "") {
				sessionStore.sessions = []
				return
			}
			let valueParts = selectedValue.split("@");
			let panelId = valueParts[0];
			let nodeId = valueParts[1];
			sessionStore.selectedPanel = sessionStore.panels.find((panel) => panel.panelId == panelId && panel.nodeID == nodeId)
			sessionStore.selectedPanelName = selectedValue;
			this.onPanelChanged();
		}
	},
	computed: {
		panels: function () {
			return sessionStore.panels
		},
		stats: function () {
			return statStore.stats
		},
		user: function () {
			return userStore.user
		},
		balance: function () {
			const userStore = useUserStore()
			if (userStore.user && (userStore.user.balance !== null)) {
				let cents = parseFloat(userStore.user.balance)
				let usd = (cents / 100).toFixed(2);
				let str = `${usd}\$`;
				return str;
			}
			return "-"
		},
		selectedPanelName: function () {
			return sessionStore.selectedPanelName
		},
		sitesWidget: function () {
			return siteWidgetStore.sites
		}
	},
	mounted() {

		// Theme Color from database
		if (userStore.authenticated && !userStore.user) {
			userStore.getUserInfo();
		}
		console.log(userStore.themeClass);
		appOption.appThemeClass = userStore.themeClass;
		localStorage.appThemeClass = appOption.appThemeClass;
		
		this.setThemeClass(localStorage.appThemeClass);


		sessionStore.getPanelList(false);
		sessionStore.$subscribe((mutation) => {
			let ev = mutation.events;
			if (ev && ev.key == "selectedPanel" && ev.oldValue != ev.newValue) {
				this.onPanelChanged()
			}
		})
		statStore.$subscribe((mutation) => {
			this.renderMap();
			this.renderOSChart();
		})
		this.loadSiteWidgetList()
	},
	created() {
		this.emitter.on('theme-reload', (evt) => {
			this.renderComponent = false;

			this.$nextTick(() => {
				this.renderComponent = true;
				setTimeout(() => {
					this.renderMap();
				}, 50);
			});
		})

		window.addEventListener("resize", (event) => {
			this.$nextTick(() => {
				setTimeout(() => {
					this.renderMap();
				}, 50);
			});
		});
	}
}
</script>
<style>
.panel-select {
	background: rgba(0, 0, 0, 0.3);
	color: white;
	padding: 0.5em;
	border-radius: 0.5em;
	font-size: 1.15em;
	margin-bottom: 0.5em;
}

.dashtable {
	font-size: 12.5px;
	margin-bottom: 0rem;
	border-bottom-style: hidden;

}


.dashtable> :not(caption)>*>* {
	padding: 0.25rem 0.15rem;
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
	<select class="panel-select" @change="onPanelSelect($event)" v-model="selectedPanelName" :value="selectedPanelName">
		<option v-if="panels" v-for="panel in panels" :value="panel.panelId + '@' + panel.nodeID">{{ panel.panelId }} @
			{{ panel.nodeID }}</option>
		<option disabled v-if="!panels" value="no panels"></option>
	</select>
	<div class="row" v-if="renderComponent">
		<!-- START CARDS -->
		<div class="col-sm-4 mb-3">
			<div class="card h-100">
				<div class="card-body">
					<p class="card-text">
						Spendable balance:<small style="float: right"></small>
					</p>
					<h2 class="card-title">{{ balance }} </h2>
					<br />
					<div style="padding: 0px; margin: 0px">
						<a href="#" style="float: right; bottom: 0; margin-left: 5px" class="btn btn-outline-theme">Top
							up</a>
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
		<div class="col-sm-8 mb-3">
			<div class="card h-100">
				<div class="card-body">
					<table class="table dashtable table-striped" v-if="sitesWidget">
						<thead>
							<tr>
								<th scope="col">Status <i class="bi bi-hdd-stack-fill"></i></th>
								<th scope="col">Host <i class="bi bi-globe"></i></th>
								<th scope="col">PanelId <i class="bi bi-laptop"></i></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="site in sitesWidget">
								<th scope="row">
									<div class="online" v-if="site.hostStatus == 'ONLINE'"></div>
									<div class="warning" v-if="site.hostStatus == 'SSL_DOWN'"></div>
									<div class="warning" v-if="site.hostStatus == 'PENDING_CHECK'"></div>
									<div class="offline" v-if="site.hostStatus == 'DOMAIN_DOWN'"></div>
									<div class="offline" v-if="site.hostStatus == 'SITE_DOWN'"></div>
								</th>
								<td>{{ site.domain }}</td>
								<td>{{ site.panelId }}</td>
							</tr>
						</tbody>
					</table>
					<h5 v-else>No sites available</h5>
				</div>
				<div class="card-arrow">
					<div class="card-arrow-top-left"></div>
					<div class="card-arrow-top-right"></div>
					<div class="card-arrow-bottom-left"></div>
					<div class="card-arrow-bottom-right"></div>
				</div>
			</div>
		</div>

		<!-- END CARDS -->
		<!-- BEGIN stats -->
		<div style="flex-grow: 1"></div>
		<!-- END stats -->

		<!-- END-->

		<!-- BEGIN traffic-analytics -->
		<div class="col-xl-6">
			<card class="mb-3">
				<card-body>
					<div class="d-flex fw-bold small mb-3">
						<span class="flex-grow-1">TRAFFIC ANALYTICS</span>
						<card-expand-toggler />
					</div>
					<div class="ratio ratio-21x9 mb-3">
						<div class="jvm-without-padding" id="map-container">
							<div id="map"></div>
						</div>
					</div>

					<div class="row gx-4">
						<div class="col-lg-6 mb-3 mb-lg-0">
							<table class="w-100 small mb-0 text-truncate text-inverse text-opacity-60">
								<thead>
									<tr class="text-inverse text-opacity-75">
										<th class="w-25">COUNTRY</th>
										<th class="w-25 text-end flex-grow">City / Region</th>
										<th class="w-25 text-end">VISITS</th>
										<th class="w-25 text-end">%</th>
									</tr>
								</thead>
								<tbody>
									<tr v-if="stats && stats.locations" v-for="location in stats.locations.slice(0, 5)">
										<td>{{ location.Country ? location.Country : "Unknown" }}</td>
										<td>{{ location.City ? location.City : "Unknown" }}</td>
										<td class="text-end">{{ location.Count }}</td>
										<td class="text-end">{{ this.calcPercent(location) }}</td>
									</tr>
									<tr v-else>
										<td colspan="3">No records found</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-lg-6">
							<card>
								<card-body class="py-2">
									<div class="d-flex align-items-center">
										<div class="w-70px">
											<apexchart key="osChartKey" ref="osChartElem" :height="chartCfg.osChart.height"
												:options="chartCfg.osChart.options" :series="this.getOsData()"></apexchart>
										</div>
										<div class="flex-1 ps-2">
											<table class="w-100 small mb-0 text-inverse text-opacity-60">
												<tbody>
													<tr v-if="stats && stats.os"
														v-for="(os, index) in stats.os.slice(0, 5)">
														<td>
															<div class="d-flex align-items-center">
																<div class="w-6px h-6px rounded-pill me-2 bg-theme"
																	:style="'opacity:0.' + (95 - (20 * index))"></div>
																{{ os.OS ? os.OS : "Unknown" }}
															</div>
														</td>
														<td class="text-end">{{ os.Count }}</td>
													</tr>
													<tr v-else>
														<td colspan="2">No records found</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</card-body>
							</card>
						</div>
					</div>
				</card-body>
			</card>
		</div>
		<!-- END traffic-analytics -->

		<!-- BEGIN activity-log -->
		<div class="col-xl-6">
			<card class="mb-3">
				<card-body>
					<div class="d-flex fw-bold small mb-3">
						<span class="flex-grow-1">ACTIVITY LOG</span>
						<card-expand-toggler />
					</div>
					<div class="table-responsive">
						<table class="table table-striped table-borderless mb-2px small text-nowrap">
							<tbody>
								<tr v-if="stats && stats.activity" v-for="item in stats.activity">
									<td>
										<i :class="'bi bi-' + item.icon"></i>
										{{ item.content }}
									</td>
									<td><small>{{ timeAgo(item.time) }}</small></td>
									<td>
										<span class="badge d-block rounded-0 pt-5px w-70px"
											v-bind:class="{ 'bg-theme text-theme-900': item.isAlerted, 'bg-inverse bg-opacity-25': !item.isAlerted }"
											style="min-height: 18px">
											{{ item.type.toUpperCase() }}
										</span>
									</td>
									<td><a href="#" class="text-decoration-none text-inverse"><i
												class="bi bi-search"></i></a></td>
								</tr>
								<tr v-else>
									<td colspan="4">
										No records found
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</card-body>
			</card>
		</div>
		<!-- END activity-log -->


	</div>
	<!-- END row -->
</template>