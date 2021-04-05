<template>
    <!-- <div>
        {{currentPage}} / {{pageCount}}
        <pdf
            :src="path"
            @num-pages="pageCount = $event"
            @page-loaded="currentPage = $event"
        ></pdf>
    </div> -->
        <!-- Modal -->
  <div class="modal fade" id="viewpdf" tabindex="-1" role="dialog" aria-labelledby="viewpdfTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="viewpdfTitle">Document</h5>
          <button type="button" class="close" data-dismiss='modal' aria-label="Close">
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
  {{currentPage}} / {{pageCount}}
        <pdf
            :src="path"
            @num-pages="pageCount = $event"
            @page-loaded="currentPage = $event"
        ></pdf>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <!-- <button type="submit" class="btn btn-primary">Confirmer</button> -->
          </div>
      </div>
      <!-- /.card -->
      </div>
      <div class="modal-footer">
            <button id="close" type="button" class="btn btn-secondary" data-dismiss='modal'>Fermer</button>
            <!-- <button hidden type="submit" id="submitAddHotline" class="btn btn-primary" @click="hotlineStore" data-dismiss="modal"></button> -->
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
            currentPage: 0,
            pageCount: 0,
        }
    }
}
 
</script>