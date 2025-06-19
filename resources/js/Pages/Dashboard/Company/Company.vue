<template>
    <MainLayout>
        <!-- Add and Edit Company Modal-->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">{{form_data.id ? 'Edit Company' : 'New Company'}}</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="d-flex gap-5">
                                <div class="w-50">
                                    <div class="mb-3">
                                        <label for="company-name" class="form-label">Company Name</label>
                                        <input type="text" v-model="form_data.name" class="form-control" :class="{'is-invalid':errors.name !== ''}" id="company-name" placeholder="Enter Company Name*">
                                        <div class="invalid-feedback">
                                            {{errors.name}}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" v-model="form_data.email" class="form-control" :class="{'is-invalid':errors.email !== ''}" id="email" placeholder="Leave blank if you don't want to add email">
                                        <div class="invalid-feedback">
                                            {{errors.email}}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" v-model="form_data.phone_number" class="form-control" :class="{'is-invalid':errors.phone_number !== ''}"  id="phone_number" placeholder="Leave blank if you don't want to add phone number">
                                        <div class="invalid-feedback">
                                            {{errors.phone_number}}
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50">
                                    <div class="mb-3">
                                        <div id="preview-wrapper">
                                            <img v-if="form_data.old_logo" :src="'/images/logo/'+form_data.old_logo" id="logo-preview" alt="">
                                            <img v-else src="@/assets/No-Image-Placeholder.svg.png" id="logo-preview" alt="">
                                        </div>
                                        <label for="logo" class="form-label" :class="{'is-invalid':errors.logo !== ''}">Logo</label>
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="logo"
                                            accept="image/png, image/webp, image/jpeg, image/jpg"
                                            @change="onChangeFile"
                                            onchange="document.getElementById('logo-preview').src = window.URL.createObjectURL(this.files[0])"
                                        >
                                        <div class="invalid-feedback">
                                            {{errors.logo}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button v-if="!form_data.id" @click="save" type="button" class="btn btn-primary">Save</button>
                            <button v-else @click="saveEdit" type="button" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End Add and Edit Company Modal-->

        <!-- Delete Company Modal-->
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Company?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Are you sure delete this company?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button @click="onCfDelete" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete Company Modal-->
        <div class="Report-wrapper">
            <div class="d-flex justify-content-between align-items-center">
                <div class="search-container">
                    <div class="txt-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="text"
                            v-model="search"
                            @input="searchItems"
                            placeholder="Search name, email or phone number"
                            :disabled="!this.filters.search && companies.data.length == 0"
                        >
                    </div>
                </div>
                <button class="primary-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;New Company</button>
            </div>
            <!-- success message alert-->
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert" v-if="success.show">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{success.message}}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <!-- end success message alert-->
            <div class="table-container" v-if="companies.data.length > 0">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in companies.data" :key="'company_list'+index">
                        <td>{{index+1}}</td>
                        <td>
                            <div class="company-logo">
                                <img v-if="!item.logo" src="@/assets/no-image.jpg" alt="">
                                <img v-else :src="'/images/logo/'+item.logo" onerror="this.src='error-image.jpg'"
                                     alt="">
                            </div>
                        </td>
                        <td>{{item.name}}</td>
                        <td :class="{'text-muted':!item.email}">{{item.email ? item.email : '(null)'}}</td>
                        <td :class="{'text-muted':!item.phone_number}">{{item.phone_number ? item.phone_number : '(null)'}}</td>
                        <td>
                            <button @click="onDelete(item)" class="btn btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button @click="onEdit(item)" class="btn btn-outline-primary mx-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="companies.data.length > 0" class="pagination">
                <button
                    v-for="link in companies.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    class="px-2 pagination-item"
                    :class="{'pagination-item-active':link.active}"
                />
            </div>

            <span class="not-found-msg" v-if="this.filters.search && companies.data.length == 0">No record founded.</span>
            <span class="not-found-msg" v-if="!this.filters.search && companies.data.length == 0">No record.</span>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useForm,router} from "@inertiajs/vue3";
import noImage from "@/assets/No-Image-Placeholder.svg.png"

export default{
    components: {MainLayout},
    props: {
        companies:Array,
        filters: Object
    },
    mounted() {
        let vm = this;
        $("#staticBackdrop").on("hide.bs.modal", function(){
            vm.clearForms()
            vm.clearErrors()
            document.getElementById("logo-preview").src = noImage
            document.querySelector("input[type=file]").value = "";
        })

        $("#deleteModal").on("hide.bs.modal", function(){
            vm.form_data.id = null
        })
    },
    data(){
        return{
            success:{
                show:false,
                message:""
            },
            form_data:useForm({
                id:null,
                name:"",
                email:"",
                phone_number:"",
                logo:null,
                old_logo:null,
            }),
            errors:{
                name:"",
                email:"",
                phone_number:"",
                logo:""
            },
            search: this.filters.search || '',
            debounceTimer: null,
        }
    },
    methods:{
        searchItems() {
            clearTimeout(this.debounceTimer)

            this.debounceTimer = setTimeout(() => {
                router.get(route('company'), { search: this.search }, {
                    preserveState: true,
                    replace: true
                })
            }, 300)
        },
        save(){
            $.LoadingOverlay("show",{
                background:"rgb(0 0 0 / 35%)",
                imageColor:"#ffffff"
            });
            let vm = this;
            this.clearErrors()
            this.form_data.post(route('createCompany'),{
                onSuccess:function(data){
                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    vm.success.show = true;
                    vm.success.message = "Company created successfully";
                    $.LoadingOverlay("hide");
                    setTimeout(() => {
                        vm.success.show = false;
                        vm.success.message = "";
                    }, 3000);
                },
                onError:(err) =>{
                    const keys = Object.keys(err);
                    keys.forEach(key => {
                        this.errors[key] = err[key];
                    })
                    console.log(this.errors);
                    $.LoadingOverlay("hide");
                }
            })

        },
        saveEdit(){
            $.LoadingOverlay("show",{
                background:"rgb(0 0 0 / 35%)",
                imageColor:"#ffffff"
            });
            let vm = this;
            this.clearErrors()
            this.form_data.post(route('saveEditCompany'),{
                onSuccess:function(data){
                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    vm.success.show = true;
                    vm.success.message = "Company updated successfully";
                    $.LoadingOverlay("hide");
                    setTimeout(() => {
                        vm.success.show = false;
                        vm.success.message = "";
                    }, 3000);
                },
                onError:(err) =>{
                    const keys = Object.keys(err);
                    keys.forEach(key => {
                        this.errors[key] = err[key];
                    })
                    console.log(this.errors);
                    $.LoadingOverlay("hide");
                }
            })
        },
        onEdit(item){
          this.form_data.id = item.id;
          this.form_data.name = item.name;
          this.form_data.email = item.email;
          this.form_data.phone_number = item.phone_number;
          this.form_data.old_logo = item.logo;
          $("#staticBackdrop").modal('show');
        },
        onDelete(item){
            this.form_data.id = item.id;
            console.log(this.form_data.id);
            $("#deleteModal").modal('show');
        },
        onCfDelete(){
            $.LoadingOverlay("show",{
                background:"rgb(0 0 0 / 35%)",
                imageColor:"#ffffff"
            });
            let vm = this;
            let form = useForm({id:this.form_data.id})
            form.post(route('deleteCompany'),{
                onSuccess:()=> {
                    $("#deleteModal").modal('hide');
                    vm.success.show = true;
                    vm.success.message = "Company deleted successfully";
                    $.LoadingOverlay("hide");
                    setTimeout(() => {
                        vm.success.show = false;
                        vm.success.message = "";
                    }, 3000);
                },
                onError:(err) =>{
                    $.LoadingOverlay("hide");
                    console.log(err)
                }
            })
        },
        onChangeFile(evt){
          let file = evt.target.files[0];
          if(file){
              this.form_data.logo = file
          }
        },
        clearErrors(){
            this.errors.name = "";
            this.errors.email = "";
            this.errors.phone_number = "";
            this.errors.logo = "";
        },
        clearForms(){
            this.form_data.id = null;
            this.form_data.name = "";
            this.form_data.email = "";
            this.form_data.phone_number = "";
            this.form_data.logo = null;
            this.form_data.old_logo = null;
        },
        goToPage(url) {
            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
            })
        }
    }
}
</script>

<style>
.company-logo{
    & img{
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 5px
    }
}

#preview-wrapper{
    text-align: center;
    & img{
        width: 137px;
        object-fit: cover;
        aspect-ratio: 2 / 2.5;
        box-shadow: 0 0 1px dimgray;
    }
}

.pagination{
    display: inline-block;
    margin: 0 auto;
}
.pagination-item{
    color: black;
    float: left;
    padding: 8px 16px !important;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
}

.pagination-item-active{
    background-color: #0092E1;
    color: white;
    border: 1px solid #0092E1;
}
.pagination-item:hover:not(.pagination-item-active) {background-color: #ddd;}


.Report-wrapper {
    padding: 0 20px;
    font-family: "Poppins", sans-serif;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.table-col{
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.8rem;
    width: 100%;
}

.table-col .txtBox{
    width: 100%;
    max-width: 250px;
}

.check-Table {
    background-color: #D9D9D9;
    display: inline-block;
    padding: 8px;
    width: 100%;
    max-width: 1440px;
}

.txtBox{
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1px;
}


.txtBox-header{
    background-color: white;
    width: 100%;
    max-width: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 35px;
    border-radius: 3px;
    color: #0092E1;
}

.txtBox-body{
    width: 250px;
    border: none;
    height: 35px;
    border-radius: 3px;
}

.table-container{
    background-color: #FFFFFF;
    box-shadow: 1px 1px 4px 0 rgb(0, 0, 0, 0.25);
    border-radius: 20px;
    width: 100%;
    max-width: 1440px;
    overflow: hidden;
}

table {
    width: 100%;
    max-width: 1440px;
    border-collapse: collapse;
    border: none;

    & thead{
        background: #0092E1;
        color: white;
    }
}


th, td {
    border-bottom: 2px solid black; /* Horizontal line */
    border-right: 2px solid black;
    padding: 8px;
    text-align: center;
    height: 35px;
}

th:last-child, td:last-child {
    border-right: none;
}

tr:last-child td {
    border-bottom: none;
}

.receipt-container{
    margin-top: 16px;
    width: 100%;
    max-width: 308px;
    height: 290px;
    display: flex;
    flex-direction: column;
    padding: 10px;
    box-shadow: 0px 4px 4px 0 rgb(0, 0, 0,0.25);
    font-size: 18px;
}

.total-header {
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.total-body {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.total-bottom{
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 22px;
}

@media (max-width: 950px){
    .table-col{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;

    }

}
</style>
