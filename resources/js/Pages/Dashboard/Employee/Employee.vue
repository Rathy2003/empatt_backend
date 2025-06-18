<template>
    <MainLayout>
        <div class="container">
            <div class="row">
                <!-- add and edit employee modal -->
                <div class="modal fade" id="addEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLabel">{{form_data.id ? 'Edit Employee' : 'Add Employee'}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="form-container">

                                    <!-- left side -->
                                    <div class="col-md-6">
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="form-group w-50">
                                                <label for="firstname" class="form-label mb-2">First Name</label>
                                                <input v-model="form_data.firstname" type="text" class="form-control" placeholder="Enter first name*" :class="{'is-invalid':errors.firstname}" id="firstname" name="firstname">
                                                <div v-if="errors.firstname" class="invalid-feedback">
                                                    {{ errors.firstname }}
                                                </div>
                                            </div>

                                            <div class="form-group w-50">
                                                <label for="lastname" class="form-label mb-2">Last Name</label>
                                                <input v-model="form_data.lastname" type="text" class="form-control" placeholder="Enter last name*" :class="{'is-invalid':errors.lastname}" id="lastname" name="lastname">
                                                <div v-if="errors.lastname" class="invalid-feedback">
                                                    {{ errors.lastname }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="salary" class="form-label mb-2">Salary</label>
                                            <input v-model="form_data.salary" type="number" min="0" class="form-control" :class="{'is-invalid':errors.salary}" id="salary" name="salary">
                                            <div v-if="errors.salary" class="invalid-feedback">
                                                {{ errors.salary }}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="gender" class="form-label mb-2">Gender</label>
                                            <select v-model="form_data.gender" class="form-select" :class="{'is-invalid':errors.gender}" id="gender" name="gender">
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <div v-if="errors.gender" class="invalid-feedback">
                                                {{ errors.gender }}
                                            </div>
                                        </div>


                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label mb-2">Email</label>
                                            <input v-model="form_data.email" type="email" class="form-control" placeholder="Enter email*" :class="{'is-invalid':errors.email}" id="email" name="email">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>


                                        <div v-if="role === 'ceo'" class="d-flex gap-3 mb-3">
                                            <div class="form-group w-50">
                                                <label for="position_id" class="form-label mb-2">Position</label>
                                                <select v-model="form_data.position_id" class="form-select" :class="{'is-invalid':errors.position_id}" id="position_id" name="position_id">
                                                    <option value="" disabled selected>Select Position</option>
                                                    <option v-for="position in positions" :value="position.id">{{
                                                        position.name }}</option>
                                                </select>
                                                <div v-if="errors.position_id" class="invalid-feedback">
                                                    {{ errors.position_id }}
                                                </div>
                                            </div>
                                            <div class="form-group w-50">
                                                <label for="role" class="form-label mb-2">Role</label>
                                                <select v-model="form_data.role" class="form-select" :class="{'is-invalid':errors.role}" id="role" name="role">
                                                    <option value="" disabled selected>Select Role</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="employee">Employee</option>
                                                </select>
                                                <div v-if="errors.position_id" class="invalid-feedback">
                                                    {{ errors.position_id }}
                                                </div>
                                            </div>
                                        </div>

                                        <div v-else class="form-group mb-3">
                                            <label for="position_id" class="form-label mb-2">Position</label>
                                            <select v-model="form_data.position_id" class="form-select" :class="{'is-invalid':errors.position_id}" id="position_id" name="position_id">
                                                <option value="" disabled selected>Select Position</option>
                                                <option v-for="position in positions" :value="position.id">{{
                                                    position.name }}</option>
                                            </select>
                                            <div v-if="errors.position_id" class="invalid-feedback">
                                                {{ errors.position_id }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="hired_date" class="form-label mb-2">Hired Date</label>
                                            <input v-model="form_data.hired_date" type="date" class="form-control" :class="{'is-invalid':errors.hired_date}" id="hired_date" name="hired_date">
                                            <div v-if="errors.hired_date" class="invalid-feedback">
                                                {{ errors.hired_date }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label mb-2">Password</label>
                                            <input v-model="form_data.password" type="password" class="form-control" :placeholder="form_data.id ? 'Leave blank if you don\'t want to change password':'Enter password*'" :class="{'is-invalid':errors.password}" id="password" name="password">
                                            <div v-if="errors.password" class="invalid-feedback">
                                                {{ errors.password }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- right side -->

                                    <div class="col-md-6">
                                        <div id="photo-container">
                                            <div class="form-group my-3">
                                                <label id="photo-preview" for="photo" class="form-label mb-2" :style="{backgroundImage: `url(${form_data.old_photo ? '/images/users/'+form_data.old_photo : '/no-image.png'})`}">

                                                </label>
                                                <input @change="onChangePhoto" type="file" class="form-control" :class="{'is-invalid':errors.photo}" accept="image/*" id="photo" name="photo">
                                                <div v-if="errors.photo" class="invalid-feedback">
                                                    {{ errors.photo }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3" id="dob-container">
                                            <label for="dob" class="form-label mb-2">Date of Birth</label>
                                            <input v-model="form_data.dob" type="date" class="form-control" :class="{'is-invalid':errors.dob}" id="dob" name="dob">
                                            <div v-if="errors.dob" class="invalid-feedback">
                                                {{ errors.dob }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="phone_number" class="form-label mb-2">Phone Number</label>
                                            <input v-model="form_data.phone_number" type="text" class="form-control" @input="formatPhone" placeholder="012 345 6789" :class="{'is-invalid':errors.phone_number}" id="phone_number"
                                                name="phone_number"

                                            >
                                            <div v-if="errors.phone_number" class="invalid-feedback">
                                                {{ errors.phone_number }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label mb-2">Address</label>
                                            <input v-model="form_data.address" type="text" class="form-control" placeholder="Enter address*" :class="{'is-invalid':errors.address}" id="address" name="address">
                                            <div v-if="errors.address" class="invalid-feedback">
                                                {{ errors.address }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="confirmation_password" class="form-label mb-2">Confirmation Password</label>
                                            <input v-model="form_data.confirmation_password" type="password" :placeholder="form_data.id ? 'Leave blank if you don\'t want to change password':'Enter confirmation password*'" class="form-control" :class="{'is-invalid':errors.confirmation_password}" id="confirmation_password" name="confirmation_password">
                                            <div v-if="errors.confirmation_password" class="invalid-feedback">
                                                {{ errors.confirmation_password }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" @click="saveEmployee">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end add and edit employee modal -->

                <!-- view employee modal -->
                <div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewEmployeeModalLabel">View Employee</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Left side -->
                                    <div class="col-md-6">
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="form-group w-50">
                                                <label for="firstname" class="form-label mb-2">First Name</label>
                                                <input v-model="view_data.firstname" type="text" class="form-control" id="firstname" name="firstname" readonly>
                                            </div>
                                            <div class="form-group w-50">
                                                <label for="lastname" class="form-label mb-2">Last Name</label>
                                                <input v-model="view_data.lastname" type="text" class="form-control" id="lastname" name="lastname" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="salary" class="form-label mb-2">Salary</label>
                                            <input v-model="view_data.salary" type="text" class="form-control" id="salary" name="salary" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="gender" class="form-label mb-2">Gender</label>
                                            <input v-model="view_data.gender" type="text" class="form-control" id="gender" name="gender" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label mb-2">Email</label>
                                            <input v-model="view_data.email" type="email" class="form-control" id="email" name="email" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone_number" class="form-label mb-2">Phone Number</label>
                                            <input  v-model="view_data.phone_number" type="text" class="form-control" id="phone_number" name="phone_number" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="position" class="form-label mb-2">Position</label>
                                            <input v-model="view_data.position" type="text" class="form-control" id="position" name="position" readonly>
                                        </div>
                                    </div>
                                    <!-- Right side -->
                                    <div class="col-md-6">
                                        <div id="photo-container">
                                            <div class="form-group my-3">
                                                <label id="photo-preview" for="photo" class="form-label mb-2" :style="{backgroundImage: `url(${view_data.photo ? '/images/employee/'+view_data.photo : '/no-image.png'})`}">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3" style="margin-top: 36px;">
                                            <label for="dob" class="form-label mb-2">Date of Birth</label>
                                            <input v-model="view_data.dob" type="text" class="form-control" id="dob" name="dob" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="hired_date" class="form-label mb-2">Hired Date</label>
                                            <input v-model="view_data.hired_date" type="texr" class="form-control" id="hired_date" name="hired_date" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label mb-2">Address</label>
                                            <input v-model="view_data.address" type="text" class="form-control" id="address" name="address" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- view employee modal -->

                <!-- delete employee modal -->
                <div class="modal fade" id="deleteEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteEmployeeModalLabel">Delete Employee</h5>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this employee?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger" @click="deleteEmployeeConfirm">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- delete employee modal -->

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
                                    :disabled="!this.filters.search && employees.data.length == 0"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <button class="primary-button" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;New Employee</button>
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
                    <div class="table-container" v-if="employees.data.length > 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,index) in employees.data" :key="'employee_list'+index">
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
                                <td>{{item.position ? item.position.name : 'N/A'}}</td>
                                <td>{{item.phone_number}}</td>
                                <td>{{item.address}}</td>
                                <td>
                                    <button @click="deleteEmployee(item)" class="btn btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    <button @click="editEmployee(item)" class="btn btn-outline-primary mx-2">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button @click="viewEmployee(item)" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

            <div v-if="employees.data.length > 0" class="pagination">
                <button
                    v-for="link in employees.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    class="px-2 pagination-item"
                    :class="{'pagination-item-active':link.active}"
                />
            </div>

            <span class="not-found-msg" v-if="this.filters.search && employees.data.length == 0">No record founded.</span>
            <span class="not-found-msg" v-if="!this.filters.search && employees.data.length == 0">No record.</span>
        </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm,router } from "@inertiajs/vue3";
import dayjs from "dayjs";

export default {
    components: { MainLayout },
    props: {
        employees: Array,
        positions: Array,
        errors: Object,
        filters: Object,
        role: String
    },
    mounted(){
        $('#addEmployeeModal').on('hidden.bs.modal',()=>{
            this.resetForm();
            this.clearError();
        });

        $('#viewEmployeeModal').on('hidden.bs.modal',()=>{
            this.view_data.reset();
        });
    },
    data() {
        return {
            success:{
                show:false,
                message:''
            },
            search: this.filters.search || '',
            debounceTimer: null,
            temp_employee_id:null,
            form_data:useForm({
                id:null,
                firstname:null,
                lastname:null,
                salary:0,
                gender:"",
                email:null,
                position_id:"",
                hired_date:new Date().toISOString().split('T')[0],
                old_photo:null,
                photo:null,
                role:"",
                dob:null,
                phone_number:null,
                address:null,
                password:null,
                confirmation_password:null,
            }),
            view_data:useForm({
                firstname:null,
                lastname:null,
                salary:0,
                gender:"",
                email:null,
                position:null,
                hired_date:null,
                photo:null,
                dob:null,
                phone_number:null,
                address:null,
            })
        }
    },
    methods:{
        searchItems(){
            clearTimeout(this.debounceTimer)

            this.debounceTimer = setTimeout(() => {
                router.get(route('employee.index'), { search: this.search }, {
                    preserveState: true,
                    replace: true
                })
            }, 300)
        },
        onChangePhoto(e){
            if(e.target.files[0]){
                this.form_data.photo = e.target.files[0];
                document.getElementById('photo-preview').style.backgroundImage = `url(${URL.createObjectURL(e.target.files[0])})`;
            }
        },
        clearError(){

        },
        resetForm(){
            this.form_data.reset();
            document.getElementById('photo-preview').style.backgroundImage = `url('/no-image.png')`;
        },
        saveEmployee(){
            let url = this.form_data.id ? route('employee.update',this.form_data.id) : route('employee.store');
            this.form_data.post(url,{
                onSuccess:()=>{
                    let message = this.form_data.id ? "Employee updated successfully" : "Employee added successfully";
                    $('#addEmployeeModal').modal('hide');
                    this.form_data.reset();
                    document.getElementById('photo-preview').style.backgroundImage = `url('/no-image.png')`;
                    this.success.show = true;
                    this.success.message = message;
                    setTimeout(()=>{
                        this.success.show = false;
                        this.success.message = '';
                    },3000);
                }
            });
        },
        editEmployee(item){
            this.form_data.id = item.id;
            this.form_data.firstname = item.firstname;
            this.form_data.lastname = item.lastname;
            this.form_data.salary = item.salary;
            this.form_data.gender = item.gender;
            this.form_data.email = item.email;
            this.form_data.position_id = item.position_id;
            this.form_data.hired_date = item.hire_date;
            this.form_data.old_photo = item.photo;
            this.form_data.photo = null;
            this.form_data.dob = item.dob;
            this.form_data.phone_number = item.phone_number;
            this.form_data.address = item.address;
            this.form_data.role = item.roles[0].name;
            $('#addEmployeeModal').modal('show');
        },
        deleteEmployee(item){
            this.temp_employee_id = item.id;
            $('#deleteEmployeeModal').modal('show');
        },
        deleteEmployeeConfirm(){
            $.LoadingOverlay("show");
            let form = useForm({
                id:this.temp_employee_id
            });
            form.post(route('employee.destroy',this.temp_employee_id),{
                onSuccess:()=>{
                    $('#deleteEmployeeModal').modal('hide');
                    this.temp_employee_id = null;
                    $.LoadingOverlay("hide");
                    this.success.show = true;
                    this.success.message = "Employee deleted successfully";
                    setTimeout(()=>{
                        this.success.show = false;
                        this.success.message = '';
                    },3000);
                },
                onError:(error)=>{
                    alert("Something went wrong");
                    $.LoadingOverlay("hide");
                }
            });
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
        },
        viewEmployee(item){
            this.view_data.firstname = item.firstname;
            this.view_data.lastname = item.lastname;
            this.view_data.salary = item.salary+" $";
            this.view_data.gender = item.gender;
            this.view_data.email = item.email;
            this.view_data.position = item.position ? item.position.name : 'N/A';
            this.view_data.hired_date = this.formatDate(item.hire_date);
            this.view_data.photo = item.photo;
            this.view_data.dob = this.formatDate(item.dob);
            this.view_data.phone_number = item.phone_number;
            this.view_data.address = item.address;

            $('#viewEmployeeModal').modal('show');
        },
        goToPage(url) {
            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
            })
        },
        formatDate(date){
            return dayjs(date).format('DD-MMM-YYYY');
        }
    }
}

</script>

<style scoped>
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

<style scoped>

#dob-container{
    margin-top: 36px;
}

#form-container:has(#photo-container input.is-invalid){
    #dob-container{
        margin-top: -40px !important;
    }
}

#photo-container {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;

    & input {
        display: none;
    }

    & label {
        cursor: pointer;
        background-color: #f0f0f0;
        padding: 10px;
        border-radius: 50%;
        width: 180px;
        height: 180px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        color: #000;
        border: 2px dashed #000;
        transition: all 0.3s ease;
        background-image: url('/no-image.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        &:hover {
            background-color: #e0e0e0;
        }
    }

    &:has(input.is-invalid) label{
        border-color: #dc3545;
        background-color: #f8d7da;
        color: #dc3545;
    }
}
</style>
