<template>
  <div class="card">
      <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-chart-bar mr-1"></i>
        Nombre d'arret
      </h3>
      <div class="card-tools">
        <ul class="nav nav-pills ml-auto">
          <li class="nav-item pt-2">
                <div class="form-group">
                    <label>Zone</label>
                    <select class="form-control" v-model="zone" @change="countCategorieParZone()">
                        <option value="" selected>Vide</option>
                        <option value="Assemblage">Assemblage</option>
                        <option value="Sertissage">Sertissage</option>
                        <option value="Préparation">Préparation</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Controle éléctrique">Controle éléctrique</option>
                    </select>
              </div>
          </li>
          <template v-if="zone!=''">
            <li class="nav-item pt-2">
                <div class="form-group">
                    <label>Categorie</label>
                    <select class="form-control" v-model="categorie" @change="countEquipement()">
                        <option value="" selected>Vide</option>
                        <option v-for="cat in cats" :key="cat" :value="cat">{{ cat }}</option>
                    </select>
                </div>
            </li>
          </template>
        </ul>
      </div>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content p-0">
            <BarChart :chartData="datacollection" :options="options"></BarChart>
        </div>
      </div>
      <!-- /.card-body -->
  </div>
</template>
<script>
import BarChart from '../Charts/BarChart';
import PieChart from '../Charts/PieChart';
export default {
  components: {
    BarChart,
    PieChart
  },
  data(){
    return {
        equips:[],
        cats:[],
        datacollection: null,
        options : null,
        zone:'',
        categorie:'',
        equipement:''
    }
  },
    created() {
        this.getNbrCat();
    },
    methods: {
        getNbrCat(){
            axios.post('/analyse/nbr-categorie/zone')
            .then(response => {this.fillData(response.data);})
            .catch(error => console.log(error))
        },
        countEquipement(){
            if(this.categorie == ''){
                this.countCategorieParZone();
            }
            else{
                axios.post('/analyse/count-equipement',{
                zone : this.zone,
                categorie : this.categorie
                })
                .then(response => this.fillDataForEquipement(response.data))
                .catch(error => console.log(error));
            }
        },
        countCategorieParZone(){
            if(this.zone == ''){
                this.getNbrCat();
            }
            else{
                axios.post('/analyse/count-categorie/zone',{
                zone:this.zone
                })
                .then(response => {
                    this.fillData(response.data);
                    this.cats = response.data[0]; 
                })
                .catch(error => console.log(error))
            }
        },
        fillData(ar){
            this.datacollection = {
                labels: ar[0],
                datasets:[{
                    label: ar[2],
                    data: ar[1],
                    backgroundColor: this.randomColorsGenerator(ar[0].length)
                }]
            };
            this.options = {
                scales: {
                    yAxes: [{
                        stacked: true,
                        ticks: {
                        beginAtZero: true,
                        min: 0,
                        },
                    }],
                    xAxes: [{
                        stacked: true,
                        ticks: {
                        beginAtZero: true,
                        categoryPercentage: 0.5,
                        barPercentage: 1,
                        },
                    }],
                },
                responsive: true,
                maintainAspectRatio: false,
            };
        },
        fillDataForEquipement(ar){
            this.datacollection = {
                labels: ar[0],
                datasets:[{
                    label: ar[3],
                    data: ar[2],
                    backgroundColor: this.randomColorsGenerator(ar[0].length)
                }]
            };
             this.options = {
                scales: {
                    yAxes: [{
                        stacked: true,
                        ticks: {
                        beginAtZero: true,
                        min: 0,
                        },
                    }],
                    xAxes: [{
                        stacked: true,
                        ticks: {
                        beginAtZero: true,
                        categoryPercentage: 0.5,
                        barPercentage: 1,
                        },
                    }],
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += ar[1][tooltipItem.index];
                            return label;
                        }
                    }
                },
                
                responsive: true,
                maintainAspectRatio: false,
            };
        
        },
        randomColorsGenerator(length){
            var COLORS = [];
            while (COLORS.length < length) {
                COLORS.push(`rgb(${rand(0, 255)}, ${rand(0, 255)}, ${rand(0, 255)})`);
            }
            function rand(frm, to) {
                return ~~(Math.random() * (to - frm)) + frm;
            }
            return COLORS;
        }
    }
}
</script>