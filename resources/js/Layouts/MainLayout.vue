<template>

    <div class="main-container">



        <!-- Sidebar -->

        <div class="left-container">
            <div class="logo-container">
                <img src="@/assets/logo2.png" class="logo" style="width:30px;">
                <label style="font-size:28px;">Checkplify</label>
            </div>
            <div class="Left-menu-container mt-4">
                <ul class="p-0">

                    <li :class="{ active: $page.url.startsWith('/dashboard') }">
                        <Link :href="route('dashboard')"
                            class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                        <span class="icon">
                            <i class="fa-solid fa-house"></i>
                        </span>
                        <span>Dashboard</span>
                        </Link>
                    </li>

                    <!-- Developer Role -->
                    <div v-if="role === 'developer'">

                        <li :class="{ active: $page.url.startsWith('/company') }">
                            <Link :href="route('company')"
                                class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                    <i class="fa-solid fa-building"></i>
                                </span>
                                <span>Company</span>
                            </Link>
                        </li>

                        <li :class="{ active: $page.url.startsWith('/user') }">
                            <Link :href="route('user')"
                                class="text-white text-decoration-none d-flex align-items-center gap-2 w-100">
                            <span class="icon">
                                <i class="fa-solid fa-users"></i>
                            </span>
                            <span>User</span>
                            </Link>
                        </li>
                    </div>
                    <!-- End Developer Role -->

                    <!-- Admin Role -->
                     <div v-if="role === 'admin'">
                        <li :class="{ active: $page.url.startsWith('/position') }">
                            <Link :href="route('position.index')"
                                class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <span>Position</span>
                            </Link>
                        </li>

                        <li :class="{ active: $page.url.startsWith('/employee') }">
                            <Link :href="route('employee.index')"
                                class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <span>Employee</span>
                            </Link>
                        </li>
                     </div>
                    <!-- End Admin Role -->

                    
                   
                    
                    <!-- Settings -->
                    <li>
                        <button @click="toggleSetdrop"
                            class="text-white text-decoration-none d-flex align-items-center gap-2 w-100">
                            <span class="icon">
                                <i class="fa-solid fa-gear"></i>
                            </span>
                            <span>Settings</span>
                            <i class="fa-solid fa-angle-down"
                                :class="{ 'rotate': SetdropdownVisible, 'rotate-back': !SetdropdownVisible }"></i>
                        </button>

                    </li>
                    <div class="settings-dropdown" v-show="SetdropdownVisible">
                        <ul>
                            <li :class="{ active: $page.url.startsWith('/account-information') }">
                                <Link :href="route('accountInformation')">
                                Account Information
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('processLogout')">
                                Log out
                                </Link>
                            </li>
                        </ul>
                    </div>
                    <!-- Settings -->

                </ul>
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
                    <div class="profile-dropdown">
                        <button class="btn-profile">My Profile
                            <i class="fa-solid fa-angle-down"
                                :class="{ 'rotate': ProfiledropdownVisible, 'rotate-back': !ProfiledropdownVisible }"
                                style="background-color:white;font-size:20px;"></i>
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
                <div class="link-container">
                    <slot />
                </div>
            </div>
        </div>


    </div>
</template>


<style>
.main-container:has(#menu-bar-wrapper input[type=checkbox]:checked) {
    & .left-container {
        width: 0;
        min-width: 0;
        padding-left: 0;
        padding-right: 0;
    }
}

#menu-bar-wrapper {
    & label {
        & i {
            font-size: 20px;
            cursor: pointer;
        }
    }

    & input[type=checkbox] {
        visibility: hidden;
        appearance: none;
        display: none;
    }
}

#main-content {
    width: 100%;
    display: flex;
    flex-direction: column;
}

#nav-bar {
    background-color: #FFFFFF;
    box-shadow: 1px 1px 4px 0 rgb(0, 0, 0, 0.25);
    padding: 20px;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1;
}

li {
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

.left-container {
    width: 100%;
    min-width: 250px;
    max-width: 250px;
    overflow: hidden;
    transition: all 200ms ease-in-out;
}



.main-container .left-container {
    background-color: #0092E1;
    color: white;
    min-height: 100vh;
    padding: 20px;
}

.logo-container>label {
    font-family: 'Poppins', sans-serif;
}

.main-container .right-container {
    background-color: white;
    width: 100%;
    display: flex;
    /* height: 100%; */
}

.router-link-active {
    background-color: #1db1ff !important;
    color: white;
}

.Left-menu-container>ul {
    width: 100%;

    & li {
        margin-bottom: 10px;

        & a {
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 17px;
            font-weight: 500;
            padding-left: 18px;
        }

    }
}

.icon {
    width: 30px;
}

.Left-menu-container li:hover {
    background-color: white;
    color: #0092E1;
    /* transform: scale(1.05); */
    border-radius: 4px;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    background-position: 10px;

    & a {
        color: #0092E1 !important;
    }

}

.Left-menu-container li.active {
    background-color: white;
    color: #0092E1;
    border-radius: 4px;

    & a {
        color: #0092E1 !important;
    }
}


li button{
    background-color: transparent;
    border: none;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    padding: 10px;

    &:hover{
        background-color: white;
        color: #0092E1;
        border-radius: 4px;

        & span{
            color: #0092E1;
        }

        & i{
            color: #0092E1;
        }
    }
}

.settings-dropdown>ul {
    list-style: none;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    padding: 0 !important;
    padding-left: 25px !important;
}

.settings-dropdown>ul li {
    width: 100%;
    padding: 0 !important;

    & a {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100% !important;
        color: white;
        text-decoration: none;
        padding: 10px !important;
    }
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


.right-container,
.link-container {
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

.profile-container {
    display: flex;
    gap: 1rem;

}

.Left-menu-container .position-container {
    & a {
        background-image: url('@/assets/rp.png');
        background-repeat: no-repeat;
        width: 100%;
        display: block;
    }
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

.profile-dropdown {
    display: flex;
    flex-direction: column;
    font-family: "Poppins", sans-serif;
}




.profile-dropdown ul {
    list-style: none;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    position: absolute;

}

.profile-dropdown ul li {
    position: relative;
    background-color: #F4F6FA;
    top: 15px;
    padding: 10px;
    width: 100%;
    display: flex;
    align-items: flex-start;

}

.profile-dropdown ul li a {
    font-size: 16px;
}

.profile-dropdown ul li a:hover {
    color: #0092E1;
}

.btn-profile {
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}


.rotate-back {
    transform: rotate(0deg);
    transition: transform 0.2s ease-in-out;
}

.router-link-active {
    background-color: white;
}

.router-link-exact-active {
    background-color: white;
    color: white;
}

@media (max-width: 950px) {

    .main-container {
        position: relative;
    }

    .left-container {
        width: 0 !important;
        min-width: 0 !important;
        max-width: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        overflow: hidden;
    }

    .main-container:has(#menu-bar-wrapper input[type=checkbox]:checked) {
        & .left-container {
            width: 250px !important;
            min-width: 250px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
    }

    .search-container {
        margin-left: 7rem;
    }
}




@media (max-width: 750px) {

    .txt-search input {
        width: 300px;
    }

    .profile-container i {
        font-size: 16px;
    }

    .profile-dropdown a {
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
import { Link, usePage } from "@inertiajs/vue3";

export default {
    components: { Link },
    mounted() {
        const { auth } = usePage().props;
        this.role = auth.role;
        this.user = auth.user;

        if(this.$page.url.startsWith('/account-information')){
            this.SetdropdownVisible = true;
        }
    },
    data() {
        return {
            ProfiledropdownVisible: false,
            SetdropdownVisible: false,
            offScreenMenuActive: false,
            user: null,
            role: null,
        };
    },
    methods: {
        toggleDropdown() {
            this.ProfiledropdownVisible = !this.ProfiledropdownVisible;
        },
        toggleSetdrop() {
            this.SetdropdownVisible = !this.SetdropdownVisible;
        },
    }
};
</script>
