<script>
import axios from "axios";

export default {
	props: {
		klantvragen: 0,
		bestelling: 0,
		retouren: 0,
		beoordelingen: 0,
		annoiem: 8,
		jan_man: 7,
		annuleringenL: 0,
		op: 0,
		trace: 0
	},
	data() {
		return {
			klantvragen: 0,
			bestelling: 0,
			retouren: 0,
			beoordelingen: 0,
			annoiem: 8,
			jan_man: 7,
			annuleringenL: 0,
			op: 0,
			trace: 0,
			// mySVG1: require('./assets/dot-graphic-contact-customer-service.svg'),
			// mySVG2: require('./assets/dot-graphic-megafoon.svg'),
			// mySVG3: require('./assets/dot-graphic-doos-retour-midden-1.svg')
		}
	},	
	created() {
	//	notificationData.data = []
		axios
		.get(`http://development.z-panel.xyz:3000/dashboard`) // development.z-panel.xyz
		.then((response) => {
			this.mailData = []
			if (response.data.success) {
				this.klantvragen = response.data.data.klantvragen;
				this.bestelling = response.data.data.bestelling;
				this.retouren = response.data.data.retouren;
				this.beoordelingen = response.data.data.beoordelingen;
				this.annoiem = response.data.data.annoiem;
				this.jan_man = response.data.data.jan_man;
				this.annuleringenL = response.data.data.annuleringenL;
				this.op = response.data.data.op;
				this.trace = response.data.data.trace;
			}
		})
		.catch((error) => {
			console.error(error);
		})
	},
}
</script>
<template>
	<div class="row">
		<div class="col-xl-4 col-lg-6">
			<card class="pb-1 mb-2">
				<card-body class="card-body d-flex align-items-center text-inverse m-5px bg-opacity-10">
					<div class="flex-fill text-center fs-5 fw-600 dashboard">
						<div class="fs-1 widget-label">{{ klantvragen }}</div>
						<div class="text-secondary">Openstaande</div>
						<div class="text-secondary" style="margin-top: -5px !important;">klantvragen</div>
					</div>
					<div class="opacity-10 margin-auto rounded" width="300">
						
						<img src="/assets/img/dashboard/dot-graphic-contact-customer-service.svg" />

					</div>
				</card-body>
				<card-body class="align-items-center text-inverse bg-opacity-10 m-auto">
					<button type="button" class="btn btn-outline-theme btn-sm fs-6">
						<i class="fa fa-fw me-2 fa-angle-right fs-6"></i> Naar klantcontact
					</button>
				</card-body>
			</card>
		</div>
		<div class="col-xl-4 col-lg-6">
			<card class="pb-1 mb-2">
				<card-body class="card-body d-flex align-items-center text-inverse m-5px bg-opacity-10">
					<div class="flex-fill text-center fs-5 fw-600 dashboard">
						<div class="fs-1 widget-label">{{ bestelling }}</div>
						<div class="text-secondary">Openstaande</div>
						<div class="text-secondary" style="margin-top: -5px !important;">bestelling</div>
					</div>
					<div class="opacity-10 margin-auto rounded" width="300">
						
						<img src="/assets/img/dashboard/dot-graphic-megafoon.svg" />

					</div>
				</card-body>
				<card-body class="align-items-center text-inverse bg-opacity-10 m-auto">
					<button type="button" class="btn btn-outline-theme btn-sm fs-6">
						<i class="fa fa-fw me-2 fa-angle-right fs-6"></i> Verwerken
					</button>
				</card-body>
			</card>
		</div>
		<div class="col-xl-4 col-lg-6">
			<card class="pb-1 mb-2">
				<card-body class="card-body d-flex align-items-center text-inverse m-5px bg-opacity-10">
					<div class="flex-fill text-center fs-5 fw-600 dashboard">
						<div class="fs-1 widget-label">{{ retouren }}</div>
						<div class="text-secondary">Openstaande</div>
						<div class="text-secondary" style="margin-top: -5px !important;">retouren</div>
					</div>
					<div class="opacity-10 margin-auto rounded" width="300">
						
						<img src="/assets/img/dashboard/dot-graphic-doos-retour-midden-1.svg" />

					</div>
				</card-body>
				<card-body class="align-items-center text-inverse bg-opacity-10 m-auto">
					<button type="button" class="btn btn-outline-theme btn-sm fs-6">
						<i class="fa fa-fw me-2 fa-angle-right fs-6"></i> Naar retouren
					</button>
				</card-body>
			</card>
		</div>
	</div>
	<div class="row mt-5">
		<div class="col-xl-4 col-lg-6">
			<card class="pt-2 mb-2">
				<card-body class="align-items-center text-inverse bg-opacity-10 pt-5 pb-4 pe-4 ps-4 mt-3 mb-3 ms-3 me-3">
					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pb-2 border-light border-bottom">
						<div class="flex-fill">
							<div class="fs-5 fw-800">Beoordelingen</div>
						</div>
						<div>
							<button type="button" class="btn btn-light btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(233, 232, 232);">
								<span class="fw-600 text-primary fs-6" style="color: darkblue !important;">{{ beoordelingen }}</span>
							</button>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pt-4 pb-2 border-light border-bottom">
						<div class="flex-fill">
							<div class="fs-6 fw-bold">Anoniem</div>
						</div>
						<div>
							<button type="button" class="btn btn-light btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(233, 232, 232);">
								<span class="fw-600 text-primary fs-6" style="color: darkblue !important;">{{ annoiem }}</span>
							</button>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pt-2 pb-2 border-light border-bottom">
						<div class="flex-fill">
							<div class="fs-6 fw-bold">Jan Man</div>
						</div>
						<div>
							<button type="button" class="btn btn-light btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(233, 232, 232);">
								<span class="fw-600 text-primary fs-6" style="color: darkblue !important;">{{ jan_man }}</span>
							</button>
						</div>
					</a>
				</card-body>
			</card>
		</div>
		

		<div class="col-xl-4 col-lg-6">
			<card class="pt-2 mb-2">
				<card-body class="align-items-center text-inverse bg-opacity-10 pt-5 pb-4 pe-4 ps-4 mt-3 mb-3 ms-3 me-3">
					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pb-5 ">
						<div class="flex-fill">
							<div class="fs-5 fw-800">Jouw prestaties</div>
						</div>
						<div>
							<button type="button" class="btn btn-light btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(233, 232, 232);">
								<span class="fw-600 text-dark fs-6">Week 27</span>
							</button>
						</div>
					</a>
					<div class="d-flex pt-2">
						<a href="#" class="flex-fill list-group-item list-group-item-action align-items-center text-center pt-4 pb-3  border-light border-bottom border-end">
							
							<div>
								<button type="button" class="btn btn-success btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(232, 247, 223);">
									<span class="fw-700 text-primary fs-5" style="color: green !important;">{{ annuleringenL }}%</span>
								</button>
							</div>

							<div class="fs-6 fw-bold">Annuleringen</div>
							<div class="fs-6 fw-200">2.0% onder de</div>
							<div class="fs-6 fw-200">norm</div>
						</a>
						<a href="#" class="flex-fill list-group-item list-group-item-action align-items-center text-center pt-4 pb-3  border-light border-bottom border-start">
							
							<div>
								<button type="button" class="btn btn-success btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(253, 195, 211);">
									<span class="fw-700 text-primary fs-5" style="color: rgb(249, 17, 17) !important;">{{ op }}%</span>
								</button>
							</div>

							<div class="fs-6 fw-bold">Op tijd geleverd</div>
							<div class="fs-6 fw-200">33.0% onder de</div>
							<div class="fs-6 fw-200">norm</div>
						</a>
					</div>
					<div class="d-flex">
						<a href="#" class="flex-fill list-group-item list-group-item-action align-items-center text-center pt-4 pb-3  border-light border-end">
							
							<div>
								<button type="button" class="btn btn-success btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(232, 247, 223);">
									<span class="fw-700 text-primary fs-5" style="color: green !important;">{{ trace }}%</span>
								</button>
							</div>

							<div class="fs-6 fw-bold">Track & trace</div>
							<div class="fs-6 fw-200">percies op de</div>
							<div class="fs-6 fw-200">norm</div>
						</a>
						<a href="#" class="flex-fill list-group-item list-group-item-action align-items-center text-center pt-4 pb-3  border-light border-start">
							
							<div>
								<button type="button" class="btn btn-success btn-sm fs-6 rounded-pill border-0 pt-3px pb-3px pe-5px ps-5px" style="background-color: rgb(232, 247, 223);">
									<span class="fw-700 text-primary fs-5" style="color: green !important;">0%</span>
								</button>
							</div>

							<div class="fs-6 fw-bold">Retouren</div>
							<div class="fs-6 fw-200">3.2% onder de</div>
							<div class="fs-6 fw-200">norm</div>
						</a>
					</div>

					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pt-5 ">
						<div class="flex-fill">
						</div>
						<div>
							<button type="button" class="btn btn-outline-theme btn-sm fs-6">
								<i class="fa fa-fw me-2 fa-angle-right fs-6"></i> Verwerken
							</button>
						</div>
					</a>
					
				</card-body>
			</card>
		</div>
		

		<div class="col-xl-4 col-lg-6">
			<card class="pt-2 mb-2">
				<card-body class="align-items-center text-inverse bg-opacity-10 pt-5 pb-4 pe-4 ps-4 mt-3 mb-3 ms-3 me-3">
					<a href="#" class="list-group-item list-group-item-action d-flex align-items-center pb-2 ">
						<div class="flex-fill">
							<div class="fs-5 fw-800">Nieuws</div>
						</div>
						<div class="w-25">
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action d-flex pt-4 pb-2 ">
						<div>
							<div class="fs-6 fw-800">11</div>
							<div class="fs-6">jul</div>
						</div>
						<div class="flex-fill w-150px ps-2">
							<div class="fs-6 fw-200">Op dit moment is het extra druk bij onze klanten-en.</div>
							<div class="fs-6 fw-200">Partnerservice, Daardoor kanhet voorkomen dat ons antwoord op jouw vraag of klantvrage, langer op zich laat wachten.</div>
						</div>
					</a>
					<a href="#" class="list-group-item list-group-item-action d-flex pt-4 pb-2 ">
						<div>
							<div class="fs-6 fw-800">5</div>
							<div class="fs-6">jul</div>
						</div>
						<div class="flex-fill w-150px ps-2">
							<div class="fs-6 fw-200">Op dit moment is het extra druk bij onze klanten-en.</div>
							<div class="fs-6 fw-200">Partnerservice, Daardoor kanhet voorkomen dat ons antwoord op jouw vraag of klantvrage, langer op zich laat wachten.</div>
						</div>
					</a>
				</card-body>
			</card>
		</div>
		
	</div>
</template>