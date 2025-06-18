<template>
    <MainLayout>
        <div class="container">
            <div class="row">
                <!-- delete position modal -->
                <div class="modal fade" id="deletePositionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    aria-labelledby="deletePositionModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deletePositionModalLabel">Delete Position</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this position?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button @click="deletePositionConfirm" type="button"
                                    class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end delete position modal -->

                <!-- add and edit position modal -->
                <div class="modal fade" id="addPositionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPositionModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPositionModalLabel">{{ form_data.id ? 'Edit Position' :
                                    'Add Position' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label mb-2">Name</label>
                                        <input v-model="form_data.name" type="text"
                                            :class="{ 'is-invalid': errors.name }" class="form-control" id="name"
                                            name="name" placeholder="Enter position name*">
                                        <div v-if="errors.name" class="invalid-feedback">
                                            {{ errors.name }}
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description" class="form-label mb-2">Description</label>
                                        <textarea v-model="form_data.description" class="form-control" rows="8"
                                            id="description" name="description" placeholder="Leave blank if you don't want to add description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button @click="submitForm" type="button" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- end add and edit position modal -->


                <div class="Report-wrapper">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="search-container">
                            <div class="txt-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text"
                                    v-model="search"
                                    @input="searchItems"
                                    placeholder="Search by name"
                          >
                            </div>
                        </div>
                        <button class="primary-button" data-bs-toggle="modal" data-bs-target="#addPositionModal"><i
                                class="fa-solid fa-plus"></i>&nbsp;&nbsp;New Position</button>
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

                    <!-- Position Listing -->
                    <div class="table-container" v-if="positions.data.length > 0">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in positions.data" :key="'position_list' + index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td :class="{'text-muted':!item.description}">{{ item.description ? item.description : '(null)' }}</td>
                                    <td>
                                        <button @click="deletePosition(item)" class="btn btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <button @click="editPosition(item)" class="btn btn-outline-primary mx-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Position Listing -->
                    <div v-if="positions.data.length > 0" class="pagination">
                        <button
                            v-for="link in positions.links"
                            :key="link.label"
                            :disabled="!link.url"
                            @click="goToPage(link.url)"
                            v-html="link.label"
                            class="px-2 pagination-item"
                            :class="{'pagination-item-active':link.active}"
                        />
                    </div>

                    <span class="not-found-msg" v-if="this.filters.search && positions.data.length == 0">No record founded.</span>
                    <span class="not-found-msg" v-if="!this.filters.search && positions.data.length == 0">No record.</span>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import { useForm } from '@inertiajs/vue3';

export default {
    components: { MainLayout },
    props: {
        errors: Object,
        positions: Array,
        filters: Object
    },
    mounted() {
        $("#addPositionModal").on("hidden.bs.modal", () => {
            this.clearForms();
        });
        $("#deletePositionModal").on("hidden.bs.modal", () => {
            this.clearForms();
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
            form_data: useForm({
                id: null,
                name: null,
                description: null,
            }),
        }
    },
    methods: {
        searchItems(){
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(()=>{
                this.$inertia.get(route('position.index'), {search: this.search}, {preserveState: true});
            }, 500);
        },
        clearForms() {
            this.form_data = useForm({
                id: null,
                name: null,
                description: null,
            })
        },
        submitForm() {
            $.LoadingOverlay("show");
            let url = this.form_data.id ? route('position.update', this.form_data.id) : route('position.store');
            this.form_data.post(url, {
                onFinish: (res) => {
                    if (!this.errors.name) {
                        $("#addPositionModal").modal("hide");
                        this.clearForms();
                        $.LoadingOverlay("hide");
                        this.success.show = true;
                        this.success.message = "Position added successfully";
                        setTimeout(()=>{
                            this.success.show = false;
                            this.success.message = '';
                        },3000);
                    }
                },
                onError: (error) => {
                    alert("Something went wrong");
                    $.LoadingOverlay("hide");

                }
            })
        },
        editPosition(position) {
            this.form_data.id = position.id;
            this.form_data.name = position.name;
            this.form_data.description = position.description;
            $("#addPositionModal").modal("show");
        },
        deletePosition(position) {
            this.form_data.id = position.id;
            $("#deletePositionModal").modal("show");
        },
        deletePositionConfirm() {
            $.LoadingOverlay("show");
            this.form_data.post(route('position.delete'), {
                onFinish: (res) => {
                    $("#deletePositionModal").modal("hide");
                    this.clearForms();
                    $.LoadingOverlay("hide");
                    this.success.show = true;
                    this.success.message = "Position deleted successfully";
                    setTimeout(()=>{
                        this.success.show = false;
                        this.success.message = '';
                    },3000);
                },
                onError: (error) => {
                    alert("Something went wrong");
                    $.LoadingOverlay("hide");
                }
            })
        }
    }
}
</script>

<style>
.company-logo {
    & img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 5px
    }
}

#preview-wrapper {
    text-align: center;

    & img {
        width: 137px;
        object-fit: cover;
        aspect-ratio: 2 / 2.5;
        box-shadow: 0 0 1px dimgray;
    }
}

.pagination {
    display: inline-block;
    margin: 0 auto;
}

.pagination-item {
    color: black;
    float: left;
    padding: 8px 16px !important;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
}

.pagination-item-active {
    background-color: #0092E1;
    color: white;
    border: 1px solid #0092E1;
}

.pagination-item:hover:not(.pagination-item-active) {
    background-color: #ddd;
}


.Report-wrapper {
    padding: 0 20px;
    font-family: "Poppins", sans-serif;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.table-col {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.8rem;
    width: 100%;
}

.table-col .txtBox {
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

.txtBox {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1px;
}


.txtBox-header {
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

.txtBox-body {
    width: 250px;
    border: none;
    height: 35px;
    border-radius: 3px;
}

.table-container {
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

    & thead {
        background: #0092E1;
        color: white;
    }
}


th,
td {
    border-bottom: 2px solid black;
    /* Horizontal line */
    border-right: 2px solid black;
    padding: 8px;
    text-align: center;
    height: 35px;
}

th:last-child,
td:last-child {
    border-right: none;
}

tr:last-child td {
    border-bottom: none;
}

.receipt-container {
    margin-top: 16px;
    width: 100%;
    max-width: 308px;
    height: 290px;
    display: flex;
    flex-direction: column;
    padding: 10px;
    box-shadow: 0px 4px 4px 0 rgb(0, 0, 0, 0.25);
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

.total-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 22px;
}

@media (max-width: 950px) {
    .table-col {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;

    }

}
</style>