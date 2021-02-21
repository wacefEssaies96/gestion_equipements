<template>
    <div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">Add new product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-groupe">
                        <div class="row">
                            <div class="col-12  mb-3">
                                <label for="name">Name : </label>
                                <input class="form-control" type="text" v-model="productToEdit.name" id="name">
                                <label for="price">Price : </label>
                                <input class="form-control" type="number"  id="price" v-model="productToEdit.price">
                                <label for="quantity">Quantity : </label>
                                <input class="form-control" type="number"  id="quantity" v-model="productToEdit.quantity">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12  mb-3">
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" data-dismiss="modal" @click="update()">Confirm</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
    export default {
        
        props: ['productToEdit'],

        methods: {
            update(){
                axios.patch('http://localhost:8000/products/edit/' + this.productToEdit.id, {
                    name: this.productToEdit.name,
                    price: this.productToEdit.price,
                    quantity: this.productToEdit.quantity
                })
                .then(response => this.$emit('product-updated',response))
                .catch(error => console.log(error));
            }
        }

    }
</script>