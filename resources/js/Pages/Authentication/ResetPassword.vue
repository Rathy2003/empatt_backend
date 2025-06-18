<template>
    <Head title="Reset Password" />
    <div class="form-container">
        <div class="form-panel">
            <div>
                <div class="form-header">
                    <h1>Forgot Password?</h1>
                </div>
                <p v-if="step == 1" class="form-description">
                    No problem. Enter the email address you used to register and we'll send you a link to reset your
                    password.
                </p>
                <!-- Success alert -->
                <div class="alert alert-success d-flex justify-content-between" v-if="success.show">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <p class="mb-0">{{ success.message }}</p>
                    </div>
                    <button class="btn-close" data-bs-dismiss="alert">
                    </button>
                </div>
                <!-- Error alert -->
                <div class="alert alert-danger d-flex justify-content-between" v-if="error.show">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                        <p class="mb-0">{{ error.message }}</p>
                    </div>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <form v-if="step == 1">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input @keyup="clearErrors" v-model="form.email" type="email" id="email" placeholder="Enter your email">

                    </div>

                    <button @click="sendResetCode" type="button" class="btn">
                        Send Reset Code
                    </button>

                    <div class="back-link-container">
                        <Link :href="route('login')" class="back-link">Back to Login</Link>
                    </div>
                </form>


                <form v-if="step == 2">
                    <div class="form-group">
                        <label for="code">Reset Code</label>
                        <input @keyup="clearErrors" v-model="form.code" type="text" id="code" placeholder="Enter the code">
                    </div>
                    <div class="form-group d-flex justify-content-between">
                        <span>Remaining time: <span class="text-primary">0:{{remainingTime.toString().padStart(2, '0')}}s</span></span>
                        <span>Didn't receive the code? <a href="javascript:void(0)" @click="sendResetCode" :class="{'disabled': remainingTime > 0}">Resend Code</a></span>
                    </div>
                    <button @click="verifyResetCode" type="button" class="btn">
                        Confirm
                    </button>
                </form>

                <form v-if="step == 3">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input v-model="form.password" type="password" id="password" placeholder="Enter your new password">
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input v-model="form.confirm_password" type="password" id="confirm-password" placeholder="Confirm your new password" :class="{'is-invalid': errors.confirm_password}">
                        <div class="invalid-feedback" v-if="errors.confirm_password">{{ errors.confirm_password }}</div>
                    </div>
                    <button @click="resetPassword" type="button" class="btn">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>

        <div class="illustration-panel">
            <img src="/reset-password.png" alt="Forgot password illustration">
        </div>
    </div>

</template>

<script>
import { Head, Link, useForm, router } from "@inertiajs/vue3";

export default {
    components: {
        Head,
        Link
    },
    data(){
        return{
            remainingTime: 60,
            step:1,
            success: {
                message: '',
                show: false
            },
            error: {
                message: '',
                show: false
            },
            form: useForm({
                email: '',
                code:'',
                password: '',
                confirm_password: ''
            })
        }
    },
    methods:{
        clearErrors(){
            this.errors = {
                email: '',
                code: '',
                password: '',
                confirm_password: ''
            }
        },
        sendResetCode(){
            $.LoadingOverlay("show");
            this.form.post(route('sendResetCode'),{
                onSuccess: () => {
                    this.step = 2;
                    this.remainingTime = 60;
                    this.startTimer();
                    $.LoadingOverlay("hide");
                    this.success.message = 'Reset code sent to your email.';
                    this.success.show = true;
                    setTimeout(() => {
                        this.success.show = false;
                    }, 3000);
                },
                onError: (e) => {
                    if(e.email){
                        this.error.message = e.email;
                        this.error.show = true;

                        setTimeout(() => {
                            this.error.show = false;
                        }, 3000);
                    }
                    $.LoadingOverlay("hide");
                }
            })

        },
        verifyResetCode(){
            $.LoadingOverlay("show");
            this.form.post(route('verifyResetCode'),{
                onSuccess: () => {
                    this.step = 3;
                    $.LoadingOverlay("hide");
                    this.success.message = 'Reset code verified.';
                    this.success.show = true;
                    setTimeout(() => {
                        this.success.show = false;
                    }, 3000);
                },
                onError: (e) => {
                    if(e.message){
                        this.error.message = e.message;
                        this.error.show = true;

                        setTimeout(() => {
                            this.error.show = false;
                            this.error.message = '';
                        }, 3000);
                    }
                    $.LoadingOverlay("hide");
                }
            })
        },
        resetPassword(){
            $.LoadingOverlay("show");
            this.form.post(route('processResetPassword'),{
                onSuccess: () => {
                    this.success.message = 'Password reset successfully. You will be redirected to login page in 5 seconds.';
                    this.success.show = true;
                    $.LoadingOverlay("hide");
                    let count = 5;
                    let timer = setInterval(() => {
                        count--;
                        this.success.message = 'Password reset successfully. You will be redirected to login page in ' + count + ' seconds.';
                        if(count <= 0){
                            clearInterval(timer);
                            this.success.show = false;
                            router.visit(route('login'));
                        }
                    }, 1000);
                },
                onError: (e) => {
                    console.log(e);

                    if(e.password && e.confirm_password){
                        if(this.form.password == "" && this.form.confirm_password == ""){
                            this.error.message = "Password and confirm password are required.";
                            this.error.show = true;
                        }
                        else if(this.form.password != this.form.confirm_password){
                            this.error.message = "Password and confirm password do not match.";
                            this.error.show = true;
                        }
                    }else if(e.password){
                        this.error.message = e.password;
                        this.error.show = true;
                    }else if(e.confirm_password){
                        this.error.message = e.confirm_password;
                        this.error.show = true;
                    }

                    setTimeout(() => {
                        this.error.show = false;
                        this.error.message = '';
                    }, 3000);
                    $.LoadingOverlay("hide");
                }
            })
        },
        startTimer(){
            const timer = setInterval(() => {
                this.remainingTime--;
                if(this.remainingTime <= 0){
                    clearInterval(timer);
                }
            }, 1000);
        }
    }
}
</script>

<style scoped>
a.disabled {
  pointer-events: none;
  cursor: default;
}
.form-container {
    display: flex;
    width: 100%;
    background-color: white;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-radius: 0.75rem;
    overflow: hidden;
    height: 100dvh;
}


.form-panel {
    width: 50%;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.illustration-panel {
    width: 50%;
    background-color: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
}

.illustration-panel img {
    max-width: 24rem;
    width: 100%;
    height: auto;
    border-radius: 0.5rem;
}

.form-header {
    margin-bottom: 2rem;
}

.form-header h1 {
    font-size: 2.25rem;
    font-weight: 700;
    color: #2563eb;
}

.form-description {
    margin-bottom: 1.5rem;
    color: #4b5563;
}

.form-group {
    margin-bottom: 1.25rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #2563eb;
}

input[type="email"], input[type="text"], input[type="password"] {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

input[type="email"].is-invalid, input[type="text"].is-invalid, input[type="password"].is-invalid{
    border-color: #ef4444;
}

input[type="email"]:focus, input[type="text"]:focus, input[type="password"]:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.4);
}

.btn {
    width: 100%;
    background-color: #2563eb;
    color: white;
    font-weight: 700;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-bottom: 1.5rem;
}

.btn:hover {
    background-color: #1d4ed8;
}

.back-link-container {
    text-align: center;
    font-size: 0.875rem;
}

.back-link {
    font-weight: 500;
    color: #2563eb;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .form-panel,
    .illustration-panel {
        width: 100%;
    }

    .illustration-panel {
        display: none;
    }

    .form-header h1 {
        font-size: 1.875rem;
    }
}
</style>
