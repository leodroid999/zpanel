<script>
import { defineComponent, reactive, computed } from 'vue';
import highlightjs from '@/components/plugins/Highlightjs.vue';
import vueTable from '@/components/plugins/VueTable.vue';
import navscrollto from '@/components/app/NavScrollTo.vue';
import axios from 'axios';
import { useAppVariableStore } from '@/stores/app-variable';
import { ScrollSpy } from 'bootstrap';

const appVariable = useAppVariableStore();

export default {
	data () {
		const data = reactive([]);
		function generateRandomCode(length) {
  const characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
  let code = '';
  
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    code += characters[randomIndex];
  }
  
  return code;
}

for (let i = 0; i < 126; i++) {
  const randomCode = generateRandomCode(5);
  data.push({
    id: randomCode,
    name: " -  " ,
    email: "test" + i + "@example.com",
	status: 'BNP Paribas',
	next_token: '➥'
  });
}

    
    // Table config
    const table = reactive({
      columns: [
		
	  {
          label: "Status",
          field: "status",
          width: "10%",
          sortable: true,
		  display: function (row) {
            return (
              'Coinbase / Online <div class="spinner-grow spinner-grow-sm" role="status"><span class="sr-only">Loading...</span></div>'
            );
          },
        },
        {
          label: "SessionID",
          field: "id",
          width: "0%",
          sortable: true,
          isKey: false,
		   display: function (row) {
            return (
              '<button type="button" class="w-100 btn btn-outline-theme btn-sm">'+row.id+'</button>'
            );
          },
        },
        {
          label: "Info",
          field: "name",
          width: "3.5%",
          sortable: true,
		  display: function (row) {
            return (
              'NL - <i class="fa fa-safari" aria-hidden="true"></i> <i class="fa fa-apple" aria-hidden="true"></i>'
            );
          },
        },
		{
          label: "Input",
          field: "Input",
          width: "15%",
          sortable: true,
        },
		{
          label: "Memo",
          field: "memo",
          width: "2%",
          sortable: true,
        },
        {
          label: "Panel",
          field: "panel",
          width: "2%",
          sortable: true,
          display: function (row) {
            return (
              '<small>Starfish1@xyz</small>'
            );
          },
        },
		{
          label: "Next token",
          field: "next_token",
          width: "4%",
          sortable: true,
        },
        {
          label: "Operations",
          field: "operation",
          width: "1%",
          sortable: true,
        },
      ],
      rows: data,
      totalRecordCount: computed(() => {
        return table.rows.length;
      }),
      sortable: {
        order: "id",
        sort: "asc",
      },
    });
    
		return {
			code1: '',
			table
		}
	},
	components: {
		highlightjs: highlightjs,
		navScrollTo: navscrollto,
		vueTable: vueTable
	},
	mounted() {
		axios.get('@/assets/data/table/plugin-code-1.json').then((response) => {
			this.code1 = response.data;
		});
		
		new ScrollSpy(document.body, {
			target: '#sidebar-bootstrap',
			offset: 200
		})
	}
}
</script>
<template>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Z-panel</a></li>
		<li class="breadcrumb-item active">Panels</li>
	</ul>
	
	<h1 class="page-header">
	</h1>
		<!-- BEGIN #vue3TableLite -->
		<div id="vue3TableLite" class="mb-5">
							<h4>[starfish1]@[xyz]</h4>
							<p></p>
							
								<card-body>
									<vue-table class="vue-table"
										:is-static-mode="true"
										:columns="table.columns"
										:rows="table.rows"
										:total="table.totalRecordCount"
										:sortable="table.sortable" />
								</card-body>
								
							
						</div>
						<!-- END #vue3TableLite -->


	<p>
	
	</p>
</template>


