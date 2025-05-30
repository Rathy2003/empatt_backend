<template>
    <MainLayout>

        <div class="Account-wrapper">
            <div class="title" style="color: #0092E1">
                <h1>Account Information</h1>
            </div>
            <div v-if="error_message!==''">
                <label>
                    <input type="checkbox" class="alertCheckbox" autocomplete="off" />
                    <div class="alert error">
                        <span class="alertClose">X</span>
                        <span class="alertText">{{error_message}}
                            <br class="clear"/></span>
                    </div>
                </label>
            </div>
            <div v-if="isSuccess">
                <label>
                    <input type="checkbox" class="alertCheckbox" autocomplete="off" />
                    <div class="alert success">
                        <span class="alertClose">X</span>
                        <span class="alertText">Success
                            <br class="clear"/></span>
                    </div>
                </label>
            </div>
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
                            <input type="email" id="" v-model="form_data.email" placeholder="user@example.com" class="txtInput">
                            <label for="" class="verify">Not verified</label>
                        </div>

                    </div>
                    <div class="txt-row">
                        <label for="">Phone Number</label>
                        <div class="txt-verify">
                            <input type="text" id="" v-model="form_data.phone_number" placeholder="Phone Number" class="txtInput">
                            <label for="" class="verify">Not verified</label>
                        </div>
                    </div>
                    <div class="txt-row">
                        <label for="">Date of birth</label>
                        <input @change="onChangeDob" type="date" id="" :value="formatDate(form_data.dob)">
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
                <Link :href="route('dashboard')">Cancel</Link>
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
            dob: this.user.dob,
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
                dob:null,
                bio:null,
            }),
            editMode: false,
            error_message:"",
            isSuccess:false,
        };
    },
    methods: {
        formatDate(value){
            return dayjs(value).format("YYYY-MM-DD");
        },
        saveChange(){
            this.error_message = ""
            this.form_data.post(route('saveInformation'),{
                onFinish: (res) => {
                    this.isSuccess = true
                },
                onError: (error) => {
                    let key = Object.keys(error)[0]
                    this.error_message = error[key]
                }
            })
        },
        onChangeDob(e){
            this.form_data.dob = e.target.value
        }
    }

};
</script>

<style scoped>

.alert {
    position: relative;
    top: 10;
    left: 0;
    width: auto;
    height: auto;
    padding: 10px;
    margin: 10px;
    line-height: 1.8;
    border-radius: 5px;
    cursor: hand;
    cursor: pointer;
    font-family: sans-serif;
    font-weight: 400;
}

.alertCheckbox {
    display: none;
}

:checked + .alert {
    display: none;
}

.alertText {
    display: table;
    margin: 0 auto;
    text-align: left;
    font-size: 16px;
}

.alertClose {
    float: right;
    font-size: 17px;
}

.clear {
    clear: both;
}

.error {
    background-color: #f13333;
    border: 1px solid #EDD;
    color: #ffffff;
}

.success {
    background-color: rgb(0, 146, 225, 0.1);
    border: 1px solid #DED;
    color: #0092E1;
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
