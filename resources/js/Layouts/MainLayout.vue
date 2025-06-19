<template>
    <Head title="Checkplify Admin Panel"/>

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

                    <!-- CEO Role -->
                    <div v-if="role === 'ceo'">
                        <li :class="{ active: $page.url.startsWith('/position') }">
                            <Link :href="route('position.index')"
                                  class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                    <i class="fa-solid fa-user-tie"></i>
                                </span>
                                <span>Position</span>
                            </Link>
                        </li>
                    </div>
                    <!-- End CEO Role -->

                    <!-- Admin And CEO Role -->
                    <div v-if="role === 'admin' || role === 'ceo'">
                        <li :class="{ active: $page.url.startsWith('/request-leave') }">
                            <Link :href="route('request-leave.index')"
                                  class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                   <i class="fa-solid fa-person-walking-arrow-right"></i>
                                </span>
                                <span>Request Leave</span>
                            </Link>
                        </li>

                        <li :class="{ active: $page.url.startsWith('/overtime') }">
                            <Link :href="route('overtime.index')"
                                  class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                   <i class="fa-solid fa-clock"></i>
                                </span>
                                <span>Overtime</span>
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

                        <li :class="{ active: $page.url.startsWith('/qrcode') }">
                            <Link :href="route('qrcode')"
                                  class="text-white text-decoration-none d-flex align-items-center gap-2 w-100 ">
                                <span class="icon">
                                    <i class="fa-solid fa-qrcode"></i>
                                </span>
                                <span>QR Code</span>
                            </Link>
                        </li>
                    </div>
                    <!-- End Admin And CEO Role -->

                    <!-- Admin Role -->
                    <div v-if="role === 'admin'">

                    </div>
                    <!-- End Admin Role -->

                    <!-- Settings -->
                    <li>
                        <button @click="toggleSetdrop"
                                class="text-white text-decoration-none d-flex align-items-center gap-2 w-100">
                            <span class="icon">
                                <i class="fa-solid fa-gear"></i>
                            </span>
                            <span style="font-size: 17px;font-weight: 500;">Settings</span>
                            <i class="fa-solid fa-angle-down"
                               :class="{ 'rotate': SetdropdownVisible, 'rotate-back': !SetdropdownVisible }"></i>
                        </button>

                    </li>
                    <div class="settings-dropdown" v-show="SetdropdownVisible">
                        <ul>
                            <li v-if="role === 'admin' || role === 'ceo'"
                                :class="{ active: $page.url.startsWith('/account-information') }">
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

                <div class="d-flex align-items-center gap-3">
                    <!-- Notification -->
                    <div v-if="role === 'admin' || role === 'ceo'" class="position-relative" style="margin-top: 5px">
                        <button type="button" class="btn icon-btn" style="padding: 5px" @click="toggleNotifications">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-bell" viewBox="0 0 16 16">
                                <path
                                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                        </button>

                        <!-- Badge -->
                        <span v-if="unreadCount > 0"
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    {{ unreadCount }}
  </span>

                        <!-- Notification Dropdown -->
                        <div v-if="showDropdown" class="position-absolute bg-white shadow p-2 mt-2"
                             style="right: 0; width: 300px; z-index: 999;">
                            <div v-if="notifications.length === 0">No new notifications</div>
                            <ul class="list-unstyled mb-0">
                                <li v-for="(notification, index) in notifications" :key="index"
                                    class="border-bottom py-1">
                                    <strong>{{ notification.data.title }}</strong>
                                    <br>
                                    <small>{{ new Date(notification.created_at).toLocaleString() }}</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--  End Notification  -->
                    <div class="profile-container" @click="toggleDropdown">
                        <i class="fa-solid fa-user"></i>
                        <div class="profile-dropdown">
                            <button class="btn-profile">{{ user ? user.firstname : '' }}
                                <i class="fa-solid fa-angle-down"
                                   :class="{ 'rotate': ProfiledropdownVisible, 'rotate-back': !ProfiledropdownVisible }"
                                   style="background-color:white;font-size:20px;"></i>
                            </button>
                            <transition name="fade">
                                <div class="dropdown" v-show="ProfiledropdownVisible">
                                    <ul>
                                        <li v-if="role === 'admin' || role === 'ceo'">
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
            </div>
            <div id="right-container">
                <div class="link-container">
                    <slot/>
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

.logo-container > label {
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

.Left-menu-container > ul {
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


li button {
    background-color: transparent;
    border: none;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    padding: 10px;

    &:hover {
        background-color: white;
        color: #0092E1;
        border-radius: 4px;

        & span {
            color: #0092E1;
        }

        & i {
            color: #0092E1;
        }
    }
}

.settings-dropdown > ul {
    list-style: none;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    padding: 0 !important;
    padding-left: 25px !important;
}

.settings-dropdown > ul li {
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
    padding: 0 25px;
    padding-bottom: 15px;
    background-color: #F4F6FA;
    border-radius: 10px;
    box-shadow: 0 4px 4px rgba(0, 0, 0, .25);
    right: -3px;
}

.profile-dropdown ul li {
    position: relative;

    top: 10px;
    padding: 10px;
    width: 100%;
    display: flex;
    align-items: flex-start;

}

.profile-dropdown ul li a {
    font-size: 16px;
    color: black;
    text-decoration: none;
    width: max-content;
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
import {Link, usePage, Head} from "@inertiajs/vue3";
import axios from "axios";

export default {
    components: {Link, Head},
    mounted() {
        const {user, role} = usePage().props;
        this.role = role;
        this.user = user;
        if (this.$page.url.startsWith('/account-information')) {
            this.SetdropdownVisible = true;
        }
        if (role === 'admin' || role === 'ceo') {
            this.fetchNotificationCount()
            this.intervalId = setInterval(() => {
                this.fetchNotificationCount();
            }, 15000);
        }
    },
    beforeDestroy() {
        clearInterval(this.intervalId);
    },
    data() {
        return {
            ProfiledropdownVisible: false,
            SetdropdownVisible: false,
            offScreenMenuActive: false,
            user: null,
            role: null,
            unreadCount: 0,
            notifications: [],
            showDropdown: false,
            intervalId: null,
        };
    },
    methods: {
        fetchNotificationCount() {
            axios.get(route('getNotificationCount'))
                .then(response => {
                    let unread = response.data.count;
                    this.unreadCount = unread;
                });
        },
        toggleDropdown() {
            this.ProfiledropdownVisible = !this.ProfiledropdownVisible;
        },
        toggleSetdrop() {
            this.SetdropdownVisible = !this.SetdropdownVisible;
        },
        async fetchNotifications() {
            try {
                const response = await axios.get(route('getAllNotifications'))
                this.notifications = response.data
                this.unreadCount = 0
            } catch (e) {
                console.error('Failed to fetch notifications', e)
            }
        },
        toggleNotifications() {
            this.showDropdown = !this.showDropdown
            if (this.showDropdown) {
                this.fetchNotifications()
            }
        }

    }
};
</script>
