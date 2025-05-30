<template>

    <div class="main-container">
      <div class="off-screen-menu-bg" :class="{ active: offScreenMenuActive }" @click="toggleOffScreenMenu"></div>
      <div class="off-screen-menu" :class="{ active: offScreenMenuActive }">
         <div class="logo-container">
                <img src="@/assets/logo2.png" class="logo" style="width:30px;">
            <label style="font-size:28px;">Checkplify</label>
            </div>
            <div class="Left-menu-container">
                <div class="db-container">
                    <li><Link :href="route('dashboard')">DashBoard</Link></li>
                </div>
                <div class="rp-container">
<!--                    <router-link to="/report">Report</router-link>-->
                    <li><Link :href="route('accountInformation')">Change Password</Link></li>
                </div>
                <div class="att-container">
<!--                    <router-link to="/attendance">Attendance</router-link>-->
                    <li><Link :href="route('accountInformation')">Change Password</Link></li>
                </div>
                <div class="st-container" @mouseenter="SetdropdownVisible = true" @mouseleave="SetdropdownVisible = false" >
                    <label >Setting</label>
                    <transition name="fade">
                        <div class="set-dropdown" v-show="SetdropdownVisible">
                            <ul>
                                <li><Link :href="route('accountInformation')">Account Information</Link></li>
<!--                                <li><router-link to="/ChangePassword">Change Password</router-link></li>-->
                                <li><Link :href="route('accountInformation')">Change Password</Link></li>
                            </ul>
                        </div>
                    </transition>
                </div>
            </div>
      </div>
      <nav>
         <div class="ham-menu" :class="{ active: offScreenMenuActive }" @click="toggleOffScreenMenu">
          <span></span>
          <span></span>
          <span></span>
         </div>
      </nav>

        <div class="left-container">
            <div class="logo-container">
                <img src="@/assets/logo2.png" class="logo" style="width:30px;">
            <label style="font-size:28px;">Checkplify</label>
            </div>
            <div class="Left-menu-container">
                <div class="db-container">
                    <li><Link :href="route('dashboard')" :class="{active:$page.url.startsWith('/dashboard')}">DashBoard</Link></li>
                </div>
                <div class="rp-container">
                    <Link :href="route('qrcode')">QR Code</Link>
                </div>
                <div v-if="role === 'developer'" class="company-container">
                    <Link :href="route('company')" :class="{active:$page.url.startsWith('/company')}">Company</Link>
                </div>

                <div v-if="role === 'developer'" class="company-container">
                    <Link :href="route('user')" :class="{active:$page.url.startsWith('/user')}">User</Link>
                </div>
                <div class="rp-container">
                    <router-link to="/report">Report</router-link>
                </div>
                <div class="att-container">
                    <router-link to="/attendance">Attendance</router-link>
                </div>
                <div class="st-container" @mouseenter="SetdropdownVisible = true" @mouseleave="SetdropdownVisible = false" >
                    <label >Setting</label>
                    <transition name="fade">
                        <div class="set-dropdown" v-show="SetdropdownVisible">
                            <ul>
                                <li><Link :href="route('accountInformation')">Account Information</Link></li>
                                <li><router-link to="/ChangePassword">Change Password</router-link></li>

                            </ul>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <div id="main-content">
            <div id="nav-bar">
                <div id="menu-bar-wrapper">
                    <label for="chk-menu">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                    <input type="checkbox" id="chk-menu">
                </div>
                <div class="profile-container" @click="toggleDropdown">
                    <i class="fa-solid fa-user"></i>
                    <div class="profile-dropdown" >
                        <button class="btn-profile">My Profile
                            <i class="fa-solid fa-angle-down" :class="{'rotate': ProfiledropdownVisible, 'rotate-back': !ProfiledropdownVisible}" style="background-color:white;font-size:20px;"></i>
                        </button>
                        <transition name="fade">
                            <div class="dropdown" v-show="ProfiledropdownVisible">
                                <ul>
                                    <li>
                                        <!-- <router-link to="/edit-profile">Edit Profile</router-link> -->
                                        <Link :href="route('accountInformation')">
                                            Edit Profile
                                        </Link>
                                    </li>
                                    <li>
                                        <Link :href="route('processLogout')">
                                            Log out
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
            <div id="right-container">
<!--                <div class="right-container">-->
<!--                </div>-->
                <div class="link-container">
                    <!--                <router-view></router-view>-->
                    <slot/>
                </div>
            </div>
        </div>


    </div>
</template>

<style>
.main-container:has(#menu-bar-wrapper input[type=checkbox]:checked){
    & .left-container{
        width: 0;
        min-width: 0;
        padding-left: 0;
        padding-right: 0;
    }
}
#menu-bar-wrapper{
    & label{
        & i{
            font-size: 20px;
            cursor: pointer;
        }
    }
    & input[type=checkbox]{
        visibility: hidden;
        appearance: none;
        display: none;
    }
}
#main-content{
    width: 100%;
    display: flex;
    flex-direction: column;
}
#nav-bar{
    background-color: #FFFFFF;
    box-shadow: 1px 1px 4px 0 rgb(0, 0, 0, 0.25);
    padding: 20px;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1;
}
li{
    list-style-type: none;
}
.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 36px;
    gap: 1rem;
}


.main-container {
    display: flex;
}

.off-screen-menu {
    display: none;
}

.off-screen-menu-bg {
    display: none;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.25);
    z-index: 900;
}

.left-container{
    width: 100%;
    min-width: 250px;
    max-width: 250px;
    overflow: hidden;
    transition: all 0.5s;
}

.main-container .left-container {
    background-color: #0092E1;
    color: white;
    /* height: 100%; */
    min-height: 100vh;
    padding: 20px;
}

.logo-container > label {
    font-family: 'Poppins', sans-serif;
}

.main-container .right-container {
    background-color: white;
    width: 100%;
    display: flex;
    /* height: 100%; */
}

.Left-menu-container {
    /* padding: 20px 40px; */
    margin-top: 50px;
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    justify-content: center;
    gap: 2.2rem;

}

.router-link-active {
    background-color: #1db1ff !important;
    color: white;
}

.Left-menu-container > div {
    width: 100%;
}

.icon {
    width: 30px;
}

.st-container{
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.st-container:hover .set-dropdown {
    display: block;
    transition: all ease 0.3s;
}


.st-container .set-dropdown ul{
    margin-top: 10px;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    justify-content: center;
    align-items: center;
    white-space: nowrap;
}

.st-container .set-dropdown ul li {
    width: 100%;
}

.st-container .set-dropdown ul li a{
    background-image:none;
    background-color:white;
    color:#0092E1 ;
    font-size: 16px;
    width: 100%;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;

}

.st-container .set-dropdown ul li a:hover{
    background-image:none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.st-container label{
    color: white;
    font-size: 20px;
    font-family: "Poppins", sans-serif;
    transition: background-color 0.7s ease, transform 0.6s ease-in-out;
    background-size: 20px;
    display: flex;
    align-items: center;
    padding: 2px 50px;
    background-position: 10px;
}

.st-container label:hover{
    background-color: white;
    color: #0092E1;
    transform: scale(1.05);
    width: 250px;
    border-radius: 4px;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 4px 50px;
    background-position: 10px;
}

.st-container .set-dropdown ul li a:hover{
    background-color: #f0f0f0;
}


.Left-menu-container a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    font-family: "Poppins", sans-serif;
    transition: background-color 0.7s ease, transform 0.6s ease-in-out;
    background-size: 20px;
    display: flex;
    align-items: center;
    gap: 1rem;
    /* padding: 2px 50px; */
    background-position: 10px;
    padding: 4px 50px;
}

.Left-menu-container a:hover {
    background-color: white;
    color: #0092E1;
    /* transform: scale(1.05); */
    border-radius: 4px;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    background-position: 10px;

}


.Left-menu-container .db-container{
    & a{
        background-image: url('@/assets/dashboard.png');
        background-repeat: no-repeat;
        display: inline-block;
        width: 100%;
        display: block;
    }

    & a.active, a:hover{
        background-color: white;
        color: #0092E1;
        border-radius: 4px;
        background-image: url('@/assets/dbBlue.png');
        background-repeat: no-repeat;
        display: inline-block;
    }
}



.Left-menu-container .company-container{
    & a{
        background-image: url('@/assets/rp.png');
        background-repeat: no-repeat;
        width: 100%;
        display: block;
    }

    & a.active, a:hover{
        background-color: white;
        color: #0092E1;
        border-radius: 4px;
        background-image: url('@/assets/rpBlue.png');
        background-repeat: no-repeat;
        display: inline-block;
    }
}

.Left-menu-container .rp-container a {
    background-image: url('@/assets/rp.png');
    background-repeat: no-repeat;
    width: 100%;
    display: block;
}

.Left-menu-container .rp-container a:hover {
    background-image: url('@/assets/rpBlue.png');
    background-repeat: no-repeat;
    display: inline-block;
}

.Left-menu-container .att-container a {
    background-image: url('@/assets/att.png');
    background-repeat: no-repeat;
    width: 100%;
    display: block;
}

.Left-menu-container .att-container a:hover {
    background-image: url('@/assets/attBlue.png');
    background-repeat: no-repeat;
    display: inline-block;
}

.Left-menu-container .st-container label {
    background-image: url('@/assets/set.png');
    background-repeat: no-repeat;
    width: 100%;
    display: block;
}

.Left-menu-container .st-container label:hover {
    background-image: url('@/assets/setBlue.png');
    background-repeat: no-repeat;
    display: inline-block;
}

#right-container {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    flex-direction: column;
    width: 100%;
    gap: 1rem;
    padding: 36px 26px;
    height: calc(100vh - 60px);
    overflow-y: scroll;
    position: relative;
}


.right-container, .link-container {
    width: 100%;
    /* height: 100vh; */
    /* padding: 36px 26px; */

}

.right-container .profile-container {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    cursor: pointer;
    margin-left: auto;

}

.profile-container{
    display: flex;
    gap: 1rem;

}

.right-container a {
    color: black;
    background-color: transparent;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 20px;
}

.profile-container i {
    border-radius: 50%;
    background-color: #CBCBCB;
    padding: 15px;
    font-size: 15px;
    width: 40px;
    height: 40px;
    text-align: center;
    margin-left: auto;
    /* display: block; */
    display: flex;
    justify-content: center;
    align-items: center;
}

.profile-dropdown{
    display: flex;
    flex-direction: column;
    font-family: "Poppins", sans-serif;
}




.profile-dropdown ul{
    list-style: none;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    position: absolute;

}

.profile-dropdown ul li{
    position: relative;
    background-color: #F4F6FA;
    top: 15px;
    padding: 10px;
    width: 100%;
    display: flex;
    align-items: flex-start;

}

.profile-dropdown ul li a{
    font-size: 16px;
}

.profile-dropdown ul li a:hover{
    color: #0092E1;
}

.btn-profile{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    border: none;
    background: transparent;
}

.search-container .txt-search {
    display: flex;
    align-items: flex-start;
    position: relative;
}

.txt-search i {
    position: absolute;
    font-size: 20px;
    left: 20px;
    top: 15px;
}

.txt-search input {
    border: none;
    box-shadow: 1px 1px 6px rgba(0, 0, 0, 0.151);
    background-color: #4e4e4e0c;
    width: 450px;
    font-size: 16px;
    border-radius: 6px;
    padding: 12px 50px;
}

.txt-search input:focus {
    outline: none;
}

.rotate {
    transform: rotate(180deg);
    transition: transform 0.2s ease-in-out;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}


.rotate-back {
    transform: rotate(0deg);
    transition: transform 0.2s ease-in-out;
}

.router-link-active{
    background-color: white;
}

.router-link-exact-active{
    background-color: white;
    color: white;
}

@media (max-width: 950px){

    .main-container{
        position: relative;
    }

    .left-container{
        width: 0;
        min-width: 0;
        overflow: hidden;
        /* position: absolute; */
        margin-left: -100px;
        margin-right: 60px;
    }

    .logo-container{
        margin-top: 7rem;
        color: white;
    }

    .off-screen-menu-bg.active {
        display: block;
    }
    .off-screen-menu {
        background-color:#0092E1;
        height: 100vh;
        width: 100%;
        max-width: 250px;
        position: fixed;
        top: 0;
        left: -350px;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 24px;
        transition: 0.3s ease;
        z-index: 1000;
    }
    .off-screen-menu.active {
        left:0;
    }

    nav{
        display: flex;
        position: absolute;
        top: 45px;
        left: 45px;
        z-index: 1001;
        cursor: pointer;
    }

    .ham-menu{
        height: 25px;
        width: 25px;
        margin-left:auto;
        position: relative;
    }

    .ham-menu span{
        height: 4px;
        width: 100%;
        background-color: black;
        border-radius: 25px;
        position: absolute;
        top:50%;
        left: 50%;
        transform: translate(-50%,-50%);
        transition: 0.3s ease;
    }

    .ham-menu span:nth-child(1){
        top: 25%;
    }

    .ham-menu span:nth-child(3){
        top: 75%;
    }

    .ham-menu.active span:nth-child(1){
        top: 50%;
        transform: translate(-50%,-50%) rotate(45deg);
        background-color: white;
    }

    .ham-menu.active span:nth-child(2){
        opacity: 0;
    }

    .ham-menu.active span:nth-child(3){
        top: 50%;
        transform: translate(-50%,-50%) rotate(-45deg);
        background-color: white;
    }

    .search-container{
        margin-left: 7rem;
    }
}




@media (max-width: 750px){

    .txt-search input{
        width: 300px;
    }

    .profile-container i {
        font-size: 16px;
    }
    .profile-dropdown a{
        white-space: nowrap;
        font-size: 16px;
    }
}
/* .link-container {
    width: 100%;
    height: 100%;
} */
</style>

<script>
import {Link,usePage} from "@inertiajs/vue3";

export default {
    components: {Link},
    mounted() {
      const {auth} = usePage().props;
      this.role = auth.role;
      this.user = auth.user;
    },
    data() {
        return {
            ProfiledropdownVisible: false,
            SetdropdownVisible: false,
            offScreenMenuActive: false,
            user:null,
            role:null,
        };
    },
    methods: {
        toggleDropdown() {
            this.ProfiledropdownVisible = !this.ProfiledropdownVisible;
        },
        toggleSetdrop() {
            this.SetdropdownVisible = !this.SetdropdownVisible;
        },
        toggleOffScreenMenu() {
            this.offScreenMenuActive = !this.offScreenMenuActive; // <-- Toggle menu visibility
        },
    }
};
</script>
