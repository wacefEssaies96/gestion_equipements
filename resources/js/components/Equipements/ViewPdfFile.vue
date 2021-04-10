<template>
        <!-- Modal -->
  <div class="modal fade" id="viewpdf" tabindex="-1" role="dialog" aria-labelledby="viewpdfTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">

      <div class="modal-header">
          <h5 class="modal-title" id="viewpdfTitle">Document</h5>
          <button type="button" class="close" @click="closeModal" data-dismiss='modal' aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
          <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Document pdf</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
          <div class="card-body">

        <pdf
            v-for="i in numPages"
            :key="i"
            :src="loadingTask"
            :page="i"
        ></pdf>
        
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
          </div>
      </div>
      <!-- /.card -->
      </div>
      <div class="modal-footer">
            <button id="closeViewPdf" type="button" class="btn btn-secondary" data-dismiss='modal' @click="closeModal">Fermer</button>
      </div>
      </div>
  </div>
  </div>
</template>
 
<script>
 
import pdf from 'vue-pdf'

export default {
    props:['path'],
    components: {
        pdf
    },
    data() {
        return {
            loadingTask:null,
            numPages: 0,
        }
    },
    watch:{
        path(newVal){
            if(this.path == '') {
                alert('Vide !');
                document.getElementById('closeViewPdf').click();
            }
            else{
                this.loadingTask = pdf.createLoadingTask(newVal);
                this.loadingTask.promise.then(pdf => {
                    this.numPages = pdf.numPages;
                });
            }
        }
    },

    methods:{
        closeModal(){
            this.loadingTask = null;
            this.$emit('closed');
            $('#viewpdf').on('click', '[data-dismiss="modal"]', function(e) { e.stopPropagation(); });
        },
    }
}
 
</script>