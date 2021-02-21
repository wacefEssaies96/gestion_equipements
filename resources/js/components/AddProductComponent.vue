<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
        Add new product
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add new product</h5>
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
                                <input class="form-control" type="text" v-model="name" id="name">
                                <label for="price">Price : </label>
                                <input class="form-control" type="number" v-model="price" id="price">
                                <label for="quantity">Quantity : </label>
                                <input class="form-control" type="number" v-model="quantity" id="quantity">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12  mb-3">
                                <label for="categorie">Categorie : </label>
                                <select id="categorie" name="categorie" v-model="categorie">
                                    <option value="">Aucun categorie</option>
                                    <option value="Informatique">Informatique</option>
                                    <option value="Alimentaire">Alimentaire</option>
                                    <option value="Bureatique">Bureatique</option>
                                    <option value="Vehicule">Vehicule</option>
                                    <option value="Electroménager">Electroménager</option>
                                    <option value="Maison">Maison</option>
                                    <option value="Bricolage">Bricolage</option>
                                </select>
                                <label for="description">Description : </label>
                                <textarea rows="4" cols="30" name="description" v-model="description" id="description" class="form-control w-100"  placeholder="Entrer La description de produit"></textarea>
                                <!-- <label>Image : </label>
                                <input type="file"  name="photo" class="form-control" v-model="image" placeholder="Entrer La Photo de produit"> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" @click="productStore" data-dismiss="modal">Confirm</button>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return{
                name: '',
                price: '',
                quantity: '',
                categorie: '',
                description: ''
            }
        },
        methods:{
            productStore(){
                axios.post('http://localhost:8000/products',{
                    name: this.name,
                    price: this.price,
                    quantity: this.quantity,
                    categorie: this.description,
                    description: this.description
                })
                .then(response => this.$emit('product-added',response))
                .catch(error => console.log(error));
            }
        }
    }
</script>