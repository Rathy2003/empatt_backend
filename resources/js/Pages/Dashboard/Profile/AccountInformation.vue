<template>
    <MainLayout>

        <div class="Account-wrapper">
            <div class="title" style="color: #0092E1">
                <h1>Account Information</h1>
            </div>
            <!-- success message alert-->
            <div class="alert alert-success alert-dismissible fade show mb-0 mt-4" role="alert" v-if="success.show">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{success.message}}</span>
                </div>
            </div>
            <!-- success message alert-->
            <div class="form-container">
                <div class="txt-container">
                    <div class="txt-row">
                        <label for="">First Name</label>
                        <input type="text" id="" v-model="form_data.firstname" placeholder="Enter your first name">
                    </div>
                    <div class="txt-row">
                        <label for="">Last Name</label>
                        <input type="text" id="" v-model="form_data.lastname" placeholder="Enter your last name">
                    </div>
                    <div class="txt-row">
                        <label for="">Email</label>
                        <div class="txt-verify">
                            <input type="email" id="" v-model="form_data.email" readonly disabled placeholder="user@example.com" class="txtInput">
                        </div>

                    </div>
                    <div class="txt-row">
                        <label for="">Phone Number</label>
                        <div class="txt-verify">
                            <input type="text" id="" v-model="form_data.phone_number" @input="formatPhone" placeholder="Phone Number" class="txtInput">
                        </div>
                    </div>
                </div>
                <br>
                <div class="txt-row">
                    <label for="">Bio</label>
                    <textarea v-model="form_data.bio" name="" id="" placeholder="Write your bio"></textarea>
                </div>
            </div>
            <br/>
            <br/>
            <div id="action-button">
                <Link :href="route('dashboard')" style="text-decoration: none">Cancel</Link>
                <button @click="saveChange">Save</button>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import dayjs from "dayjs";
import {Link, useForm} from "@inertiajs/vue3";

export default {
    components: {Link, MainLayout},
    props:{
        user:Object,
    },
    mounted(){
        this.form_data = useForm({
            id:this.user.id,
            firstname: this.user.firstname,
            lastname: this.user.lastname,
            email: this.user.email,
            phone_number: this.user.phone_number,
            bio: this.user.bio,
        })
    },
    data() {
        return {
            form_data:useForm({
                id:"",
                firstname: "",
                lastname: "",
                email: "",
                phone_number: "",
                bio:null,
            }),
            editMode: false,
            success:{
                show: false,
                message: "",
            }
        };
    },
    methods: {
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
        formatDate(value){
            return dayjs(value).format("YYYY-MM-DD");
        },
        saveChange(){
            $.LoadingOverlay("show")
            this.form_data.post(route('saveInformation'),{
                onFinish: (res) => {
                    $.LoadingOverlay("hide")
                    this.success.show = true
                    this.success.message = "Information Update successfully"
                    setTimeout(() => {
                        this.success.show = false
                        this.success.message = ""
                    },3000)
                },
                onError: (error) => {
                    let key = Object.keys(error)[0]
                    $.LoadingOverlay("hide")
                }
            })
        },
    }

};
</script>

<style scoped>
.clear {
    clear: both;
}

#action-button{
    display: flex;
    justify-content: flex-end;
    gap: 25px;
    align-items: center;

    & a{
        background: #7e7e7e;
        padding: 8px 25px;
        color: #fff;
        width: fit-content;
        border-radius: 5px;
        cursor: pointer;
        &:hover{
            background: #5b5b5b;
        }

    }
}

    & button{
        background: #0092E1;
        padding: 8px 25px;
        color: #fff;
        width: fit-content;
        border-radius: 5px;
        cursor: pointer;
        &:hover{
            background: #0385cb;
        }
    }
.Account-wrapper {
    font-family: "Poppins", sans-serif;
    gap: 2rem;
    padding: 2rem;
}

.form-container{
    margin-top: 2rem;
    width: 100%;
    max-width: 1298px;
    background-color:rgb(0, 146, 225,0.1);
    padding: 2rem 3.2rem;
    border-radius: 12px;
    color: #0092E1;
}

.txt-container{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.txt-row {
    display: flex;
    flex-direction: column;
}

.txt-row input{
    height:45px;
    padding:1.3rem;
    outline:none;
    border: solid 1px rgb(0, 146, 225);
    border-radius: 6px;
    font-size: 16px;
    width: 100%;
    max-width: 573px;
}

.txt-row textarea{
    width: 100%;
    max-width: 1186px;
    height: 179px;
    border-radius: 12px;
    padding:1.3rem;
    font-size: 16px;
    outline: none;
    border: solid 1px rgb(0, 146, 225);
}

.txt-verify{
    display: inline-block;
    position: relative;
}

.txt-verify .txtInput{
    padding-right:7rem;
}

.verify{
    position: absolute;
    right: 35px;
    top: 50%;
    transform: translateY(-50%);
}
@media (max-width: 1600px) {
    .txt-row input{
        height:45px;
        width: 100%;
        max-width: 473px;
    }

    .verify{
        position: absolute;
        right: 50px;
        top: 50%;
        transform: translateY(-50%);
    }
}

@media (max-width: 1400px){
    .verify{
        position: absolute;
        right: 30px;
        top: 50%;
    }
}

@media (max-width: 1200px){
    .verify{
        font-size: 14px;
    }
}

@media (max-width: 750px){
    .txt-row input{
        font-size: 15px;
    }
}
</style>
