<template>
    <div class="album py-5 bg-light">
        <div class="container">
            <add-product @product-added="refresh"></add-product>
            <div class="row">
                <div class="col-md-4" v-for="product in products.data" :key="product.id">
                    <div class="card mb-4 shadow-sm">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
                        <div class="card-body">
                            <h3 class="card-text">{{ product.name }}</h3>
                            <p class="card-text">{{ product.description }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#editModal" @click="getProduct(product.id)">Edit</button>
                                    <edit-product v-bind:productToEdit="productToEdit" @product-updated="refresh"></edit-product>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" @click="deleteProduct(product.id)">Delete</button>
                                </div>
                                
                                <small class="text-muted">9 mins</small>
                            </div>
                        </div>
                    </div>
                </div>
                <pagination :data="products" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
          data(){
            return {
                products: {},
                productToEdit: ''
            }
        },
        created(){
            axios.get("http://localhost:8000/products")
            .then(response => this.products = response.data)
            .catch(error => console.log(error));
        },
        methods: {
            getResults(page = 1) {
                axios.get('http://localhost:8000/products?page=' + page)
                    .then(response => {
                        this.products = response.data;
                });
            },
            deleteProduct(id){
                axios.delete('http://localhost:8000/products/' + id)
                .then(response => this.products = response.data)
                .catch(error => console.log(error));               
            },
            refresh(products){
                this.products = products.data; 
            },
            getProduct(id){
                axios.get('http://localhost:8000/products/edit/' + id)
                .then(response => this.productToEdit = response.data)
                .catch(error => console.log(error));
            },
            mounted() {
                console.log('Component mounted.')
            }
        }
    }
</script>