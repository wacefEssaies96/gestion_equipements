<template>
    <div class="modal fade" id="modal-danger">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p>Voulez-vous vraiment supprimer ce equipement ?&hellip;</p>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
            <button type="button" class="btn btn-success" data-dismiss="modal" @click="deleteEquipement(id)">Oui</button>
            </div>
        </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</template>
<script>
export default {
    props:['id'],
    methods:{
        deleteEquipement(id){
            axios.delete('/equipements/' + id)
            .then(response => this.$emit('equipement-deleted',response))
            .catch(error => console.log(error));               
        },
    }
}
</script>