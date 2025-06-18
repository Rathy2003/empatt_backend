<template>
    <MainLayout>
        <div class="container">
            <div class="row">
                <div class="Report-wrapper">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="search-container">
                            <div class="txt-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input
                                    type="text"
                                    v-model="search"
                                    @input="searchItems"
                                    placeholder="Search by employee name"
                                    :disabled="!this.filters.search && requestLeaves.data.length == 0"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
<!--                        <button class="primary-button" data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;New Employee</button>-->
                    </div>
                    <!-- success message alert-->
<!--                    <div class="alert alert-success alert-dismissible fade show mb-0" role="alert" v-if="success.show">-->
<!--                        <div class="d-flex align-items-center gap-2">-->
<!--                            <i class="fa-solid fa-circle-check"></i>-->
<!--                            <span>{{success.message}}</span>-->
<!--                        </div>-->
<!--                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
<!--                    </div>-->
                    <!-- success message alert-->
                    <div class="table-container" v-if="requestLeaves.data.length > 0">
                        <table>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Name</th>
                                <th>Title</th>
                                <td>Reason</td>
                                <td>Duration</td>
                                <th>Created At</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(item,index) in requestLeaves.data" :key="'employee_list'+index">
                                <td>{{index+1}}</td>
                                <td>{{item.employee.firstname}} {{item.employee.lastname}}</td>
                                <td>{{item.title}}</td>
                                <td>{{item.reason}}</td>
                                <td>{{item.duration}} Days</td>
                                <td>{{formatDate(item.created_at)}}</td>
                                <td>
                                    <button @click="toggleStatus(item,'rejected')" v-if="item.status === 'pending' || item.status === 'accepted'" class="btn btn-danger">Reject</button>
                                    <button @click="toggleStatus(item,'accepted')" v-if="item.status === 'pending' || item.status === 'rejected'" class="btn btn-success mx-3">Accept</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="requestLeaves.data.length > 0" class="pagination">
                        <button
                            v-for="link in requestLeaves.links"
                            :key="link.label"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            v-html="link.label"
                            class="px-2 pagination-item"
                            :class="{'pagination-item-active':link.active}"
                        />
                    </div>

<!--                    <span class="not-found-msg" v-if="this.filters.search && requestLeaves.data.length == 0">No record founded.</span>-->
<!--                    <span class="not-found-msg" v-if="!this.filters.search && requestLeaves.data.length == 0">No record.</span>-->
                </div>
            </div>
        </div>
    </MainLayout>
</template>
<script>
    import MainLayout from "@/Layouts/MainLayout.vue";
    import {router, useForm} from "@inertiajs/vue3";
    import dayjs from "dayjs";
    export default {
        components: {
            MainLayout,
        },
        props: {
            requestLeaves:Object,
            filters: Object,
        },
        data() {
            return {
                search: this.filters.search || '',
                debounceTimer: null,
            }
        },
        methods:{
            searchItems(){
                clearTimeout(this.debounceTimer)

                this.debounceTimer = setTimeout(() => {
                    router.get(route('request-leave.index'), { search: this.search }, {
                        preserveState: true,
                        replace: true
                    })
                }, 300)
            },
            toggleStatus(item,status){
              let form_data = useForm({
                  id: item.id,
                  status: status,
              })
              form_data.post(route('request-leave.update-status'),{
                  onSuccess: ()=>{
                      console.log("d")
                  },
                  onError: ()=>{
                      console.log("d")
                  }
              })
            },
            goToPage(url) {
                router.visit(url, {
                    preserveScroll: true,
                    preserveState: true,
                })
            },
            formatDate(date){
                return dayjs(date).format('DD-MMM-YYYY hh:mm:ss A');
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
