<template>
    <MainLayout>
        <div class="container">
            <div class="row">

                <!-- add and edit employee modal -->
                <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="form-container">

                                    <!-- left side -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="firstname" class="form-label mb-2">First Name</label>
                                            <input v-model="form_data.firstname" type="text" class="form-control" :class="{'is-invalid':errors.firstname}" id="firstname" name="firstname">
                                            <div v-if="errors.firstname" class="invalid-feedback">
                                                {{ errors.firstname }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="lastname" class="form-label mb-2">Last Name</label>
                                            <input v-model="form_data.lastname" type="text" class="form-control" :class="{'is-invalid':errors.lastname}" id="lastname" name="lastname">
                                            <div v-if="errors.lastname" class="invalid-feedback">
                                                {{ errors.lastname }}
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="gender" class="form-label mb-2">Gender</label>
                                            <select v-model="form_data.gender" class="form-select" :class="{'is-invalid':errors.gender}" id="gender" name="gender">
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <div v-if="errors.gender" class="invalid-feedback">
                                                {{ errors.gender }}
                                            </div>
                                        </div>


                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label mb-2">Email</label>
                                            <input v-model="form_data.email" type="email" class="form-control" :class="{'is-invalid':errors.email}" id="email" name="email">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>


                                        <div class="form-group mb-3">
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
                                            <input v-model="form_data.password" type="password" class="form-control" :class="{'is-invalid':errors.password}" id="password" name="password">
                                            <div v-if="errors.password" class="invalid-feedback">
                                                {{ errors.password }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- right side -->

                                    <div class="col-md-6">
                                        <div id="photo-container">
                                            <div class="form-group my-3">
                                                <label id="photo-preview" for="photo" class="form-label mb-2">
                                                    
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
                                            <input v-model="form_data.phone_number" type="text" class="form-control" :class="{'is-invalid':errors.phone_number}" id="phone_number"
                                                name="phone_number">
                                            <div v-if="errors.phone_number" class="invalid-feedback">
                                                {{ errors.phone_number }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label mb-2">Address</label>
                                            <input v-model="form_data.address" type="text" class="form-control" :class="{'is-invalid':errors.address}" id="address" name="address">
                                            <div v-if="errors.address" class="invalid-feedback">
                                                {{ errors.address }}
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="confirmation_password" class="form-label mb-2">Confirmation Password</label>
                                            <input v-model="form_data.confirmation_password" type="password" class="form-control" :class="{'is-invalid':errors.confirmation_password}" id="confirmation_password" name="confirmation_password">
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


                <!-- delete employee modal -->

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Employee</h5>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addEmployeeModal">Add Employee</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    components: { MainLayout },
    props: {
        employees: Array,
        positions: Array,
        errors: Object
    },
    mounted(){
        $('#addEmployeeModal').on('hidden.bs.modal',()=>{
            this.resetForm();
            this.clearError();
        });
    },
    data() {
        return {
            form_data:useForm({
                firstname:null,
                lastname:null,
                gender:"",
                email:null,
                position_id:"",
                hired_date:new Date().toISOString().split('T')[0],
                photo:null,
                dob:null,
                phone_number:null,
                address:null,
                password:null,
                confirmation_password:null,
            })
        }
    },
    methods:{
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
            this.form_data.post(route('employee.store'),{
                onSuccess:()=>{
                    $('#addEmployeeModal').modal('hide');
                    this.form_data.reset();
                    document.getElementById('photo-preview').style.backgroundImage = `url('/no-image.png')`;
                }
            });
        }
    }
}

</script>

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