<template>
    <div class="card">
      <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-chart-bar mr-1"></i> Durée d'arret
      </h3>
      <div class="card-tools">
        <ul class="nav nav-pills ml-auto">
          <li class="nav-item pt-2">
              <a class="nav-link active" href="#" @click="setChoice('zone')" data-toggle="tab">Zone</a>
          </li>
          <li class="nav-item pt-2">
              <a class="nav-link" href="#" @click="setChoice('equipement')" data-toggle="tab">Equipement</a>
          </li>
          <li class="nav-item pt-2">
              <a class="nav-link" href="#" @click="setChoice('categorie')" data-toggle="tab">Categorie</a>
          </li>
          <li class="nav-item pt-2">
              <a class="nav-link" href="#" @click="setChoice('codePanne')" data-toggle="tab">Code pannes</a>
          </li>
          <li class="nav-item pt-2">
            <a @click="exportData()" class="nav-link">
              <i class="fas fa-upload" title="Exporter les données"></i>
            </a>
          </li>
        </ul>
      </div>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content p-0">
          <template v-if="choice == 'zone'">
            <BarChart :chartData="datacollection" :options="options"></BarChart>
          </template>
          <template v-if="choice == 'equipement'">
            <PieChart :chartData="datacollectionequip"></PieChart>
          </template>
          <template v-if="choice == 'categorie'">
            <PieChart :chartData="datacollectionequip"></PieChart>
          </template>
          <template v-if="choice == 'codePanne'">
            <PieChart :chartData="datacollectionequip"></PieChart>
          </template>
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
      choice : 'zone',
      datacollection: null,
      datacollectionequip: null,
      options : null,
      array: [],
      exportedData: []
    }
  },
  created() {
    axios.post('/analyse/duree-arret/zone')
    .then(response => {
      this.fillData(response.data);
      this.array = response.data;
    })
    .catch(error => console.log(error))
  },
  methods: {
    exportData(){
      this.exportedData = [];
      for(var i = 0 ; i<this.array[1].length ; i++){
        var object = {'label':this.array[0][i], "duree d'arret":this.array[1][i]};
        this.exportedData.push(object);
      }
      axios.post('/export-data',{data : this.exportedData}, {responseType: 'blob'})
      .then(response => {
            const url = URL.createObjectURL(new Blob([response.data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', "dureearret")
            document.body.appendChild(link)
            link.click()
        })
      .catch(error => console.log(error))
    },
    getDataFromLaravel(){
        axios.post('/analyse/duree-arret',{
        type : this.choice
      })
      .then(response => {
        this.fillDataForEquipement(response.data);
        this.array = response.data;
      })
      .catch(error => console.log(error))
    },
    setChoice(c){
      this.choice = c
      this.getDataFromLaravel();
    },
    fillData(ar){
      this.datacollection = {
        labels: ar[0],
        datasets:[{
          label: "Durée d'arret par zone",
          data: ar[1],
          backgroundColor: [
            '#ff6384',
            '#36a2eb',
            '#cc65fe',
            '#ffce56',
            '#68B0AB',
          ]
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
      }
    },
    fillDataForEquipement(ar){
      this.datacollectionequip = {
        labels: ar[0],
        datasets:[{
          data: ar[1],
          backgroundColor: this.randomColorsGenerator(ar[0].length)
        }]
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