<template>
    <MainLayout>
        <!-- Add and Edit User Modal-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{form_data.id ? (form_data.readonly ? 'View User' : 'Edit User') : 'New User'}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="d-flex gap-5">
                            <div style="width: 65%">
                                <div  class="d-flex gap-5">
                                    <div class="w-50">
                                        <div class="mb-3">
                                            <label for="fname" class="form-label">First Name</label>
                                            <input type="text" v-model="form_data.firstname" :readonly="form_data.readonly" tabindex="1" class="form-control" :class="{'is-invalid':errors.firstname !== ''}" id="fname" placeholder="Enter First Name*">
                                            <div class="invalid-feedback">
                                                {{errors.firstname}}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input type="email" :readonly="form_data.readonly" tabindex="3" v-model="form_data.email" class="form-control" :class="{'is-invalid':errors.email !== ''}" id="email" placeholder="Enter Email Address*">
                                            <div class="invalid-feedback">
                                                {{errors.email}}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="company" class="form-label">Company</label>
                                            <select v-model="form_data.company_id"  tabindex="5" id="company" class="form-control" :class="{'is-invalid':errors.company_id !== ''}" :disabled="form_data.readonly">
                                                <option value="" disabled>Choose Company</option>
                                                <option :value="item.id" v-for="(item,index) in companies" :key="'company'+index">{{item.name}}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{errors.company_id}}
                                            </div>
                                        </div>

                                        <div v-if="!form_data.readonly" class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" v-model="form_data.password"  tabindex="7" class="form-control" :class="{'is-invalid':errors.password !== ''}" id="password" :placeholder="form_data.id ? 'Leave blank if you don\'t want to change password' : 'Enter Password*'">
                                            <div class="invalid-feedback">
                                                {{errors.password}}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <select v-model="form_data.role" id="role" tabindex="9" class="form-control" :class="{'is-invalid':errors.role !== ''}" :disabled="form_data.readonly">
                                                <option value="" disabled>Choose Role</option>
                                                <option value="ceo">CEO</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{errors.role}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-50">
                                        <div class="mb-3">
                                            <label for="lname" class="form-label">Last Name</label>
                                            <input type="text" :readonly="form_data.readonly" v-model="form_data.lastname" tabindex="2" class="form-control" :class="{'is-invalid':errors.lastname !== ''}" id="lname" placeholder="Enter Last Name*">
                                            <div class="invalid-feedback">
                                                {{errors.lastname}}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input type="text" :readonly="form_data.readonly" tabindex="4" v-model="form_data.phone_number" @input="formatPhone" class="form-control" :class="{'is-invalid':errors.phone_number !== ''}"  id="phone_number" placeholder="012 345 6789">
                                            <div class="invalid-feedback">
                                                {{errors.phone_number}}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select v-model="form_data.gender" tabindex="6" id="gender" class="form-control" :class="{'is-invalid':errors.gender !== ''}" :disabled="form_data.readonly">
                                                <option value="" disabled>Choose Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{errors.gender}}
                                            </div>
                                        </div>
                                        <div v-if="!form_data.readonly" class="mb-3">
                                            <label for="cf_password" class="form-label">Confirm Password</label>
                                            <input type="password" tabindex="8" v-model="form_data.confirm_password" class="form-control" :class="{'is-invalid':errors.confirm_password !== ''}" id="cf_password" :placeholder="form_data.id ? 'Leave blank if you don\'t want to change password' : 'Enter Confirm Password*'">
                                            <div class="invalid-feedback">
                                                {{errors.confirm_password}}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select v-model="form_data.status" tabindex="10" id="status" class="form-control" :class="{'is-invalid':errors.status !== ''}" :disabled="form_data.readonly">
                                                <option value="" disabled>Choose Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{errors.status}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="width: 35%" class="d-flex justify-content-center align-items-center">
                                <div class="mb-3">
                                    <div id="preview-wrapper">
                                        <img v-if="form_data.old_photo" :src="'/images/users/'+form_data.old_photo" id="logo-preview" alt="">
                                        <img v-else src="@/assets/No-Image-Placeholder.svg.png" id="logo-preview" alt="">
                                    </div>
                                    <label v-if="!form_data.readonly" for="logo" class="form-label">Profile</label>
                                    <input
                                        v-show="!form_data.readonly"
                                        tabindex="11"
                                        type="file"
                                        class="form-control"
                                        id="logo"
                                        @change="onChangeFile"
                                        onchange="document.getElementById('logo-preview').src = window.URL.createObjectURL(this.files[0])"
                                    >
                                </div>
                            </div>

                        </form>
                    </div>
                    <div v-if="!form_data.readonly" class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-if="!form_data.id" @click="save" type="button" class="btn btn-primary">Save</button>
                        <button tabindex="12" v-else @click="saveEdit" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add and Edit User Modal-->

        <!-- Delete User Modal-->
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete User?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Are you sure delete this users?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button @click="onCfDelete" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete End Delete Modal-->
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
                            :disabled="!this.filters.search && users.data.length == 0"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <button class="primary-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;New User</button>
            </div>
            <!-- success message alert-->
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert" v-if="success.show">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{success.message}}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <!-- success message alert-->
            <div class="table-container" v-if="users.data.length > 0">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in users.data" :key="'company_list'+index">
                        <td>{{index+1}}</td>
                        <td>
                            <div class="company-logo">
                                <img v-if="!item.photo" src="@/assets/no-image.jpg" alt="">
                                <img v-else :src="'/images/users/'+item.photo" onerror="this.src='error-image.jpg'"
                                     alt="">
                            </div>
                        </td>
                        <td>{{item.firstname+" "+item.lastname}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.company.name}}</td>
                        <td class="text-uppercase">{{item.roles[0].name}}</td>
                        <td>
                            <span :class="item.status == 'active' ? 'status-badge-success' : 'status-badge-danger'"></span>
                        </td>
                        <td>
                            <button @click="onDelete(item)" class="btn btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button @click="onEdit(item)" class="btn btn-outline-success mx-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button @click="onView(item)" class="btn btn-outline-primary">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="users.data.length > 0" class="pagination">
                <button
                    v-for="link in users.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    class="px-2 pagination-item"
                    :class="{'pagination-item-active':link.active}"
                />
            </div>

            <span class="not-found-msg" v-if="this.filters.search && users.data.length == 0">No record founded.</span>
            <span class="not-found-msg" v-if="!this.filters.search && users.data.length == 0">No record.</span>
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
        users:Array,
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
                readonly:false,
                id:null,
                firstname:"",
                lastname:"",
                email:"",
                phone_number:"",
                gender:"",
                company_id:"",
                role:"",
                photo:null,
                status:"active",
                old_photo:null,
                password:"",
                confirm_password:"",
            }),
            errors:{
                firstname:"",
                lastname:"",
                email:"",
                phone_number:"",
                gender:"",
                company_id:"",
                role:"",
                status:"",
                password:"",
                confirm_password:"",
            },
            search: this.filters.search || '',
            debounceTimer: null,
        }
    },
    methods:{
        searchItems() {
            clearTimeout(this.debounceTimer)

            this.debounceTimer = setTimeout(() => {
                router.get(route('user'), { search: this.search }, {
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
            this.form_data.post(route('createUser'),{
                onSuccess:function(data){
                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    vm.success.show = true;
                    vm.success.message = "User created successfully";
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
                }
            })
            $.LoadingOverlay("hide");
        },
        saveEdit(){
            $.LoadingOverlay("show",{
                background:"rgb(0 0 0 / 35%)",
                imageColor:"#ffffff"
            });
            let vm = this;
            this.clearErrors()
            this.form_data.post(route('saveUser'),{
                onSuccess:function(data){
                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    vm.success.show = true;
                    vm.success.message = "User updated successfully";
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
                }
            })
            $.LoadingOverlay("hide");
        },
        onView(item){
            this.form_data.readonly = true;
            this.form_data.id = item.id;
            this.form_data.firstname = item.firstname;
            this.form_data.lastname = item.lastname;
            this.form_data.email = item.email;
            this.form_data.phone_number = item.phone_number;
            this.form_data.gender = item.gender;
            this.form_data.role = item.roles[0].name;
            this.form_data.company_id = item.company_id;
            this.form_data.old_photo = item.photo;
            this.form_data.status = item.status;
            
            $("#staticBackdrop").modal('show');
        },
        onEdit(item){
            this.form_data.id = item.id;
            this.form_data.firstname = item.firstname;
            this.form_data.lastname = item.lastname;
            this.form_data.email = item.email;
            this.form_data.phone_number = item.phone_number;
            this.form_data.gender = item.gender;
            this.form_data.role = item.roles[0].name;
            this.form_data.company_id = item.company_id;
            this.form_data.old_photo = item.photo;
            this.form_data.status = item.status;
            
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
            form.post(route('deleteUser'),{
                onSuccess:()=> {
                    $("#deleteModal").modal('hide');
                    vm.success.show = true;
                    vm.success.message = "User deleted successfully";
                    setTimeout(() => {
                        vm.success.show = false;
                        vm.success.message = "";
                    }, 3000);
                },
                onError:(err) =>{
                    console.log(err)
                }
            })
            $.LoadingOverlay("hide");
        },
        onChangeFile(evt){
            let file = evt.target.files[0];
            if(file){
                this.form_data.photo = file
            }
        },
        clearErrors(){
            this.errors.firstname = "";
            this.errors.lastname = "";
            this.errors.email = "";
            this.errors.phone_number = "";
            this.errors.gender = "";
            this.errors.company_id = "";
            this.errors.role = "";
            this.errors.password = "";
            this.errors.confirm_password = "";
        },
        clearForms(){
            this.form_data.reset();
        },
        goToPage(url) {
            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
            })
        },
        formatPhone() {
            let digits = this.form_data.phone_number.replace(/\D/g, '');
            if (digits.length > 0 && digits[0] !== '0') {
                digits = '0' + digits;
            }
            digits = digits.substring(0, 10);

            if (digits.length <= 3) {
                this.form_data.phone_number = digits;
            } else if (digits.length <= 6) {
                this.form_data.phone_number = `${digits.slice(0, 3)} ${digits.slice(3)}`;
            } else {
                this.form_data.phone_number = `${digits.slice(0, 3)} ${digits.slice(3, 6)} ${digits.slice(6)}`;
            }
        }
    }
}
</script>

<style>
.status-badge-success{
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: seagreen;
}
.status-badge-danger{
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: #FF0000;
}
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
