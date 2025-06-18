<template>
    <MainLayout>
        <!-- QRCode Preview -->
        <div class="image-popup-modal" :class="{ active: isPopupActive }" @click.self="closePopup">
            <button class="close-popup-btn" @click="closePopup" aria-label="Close image popup">&times;</button>

            <div class="loading-indicator" v-if="isLoading">Loading...</div>

            <div class="popup-image-container" v-show="!isLoading">
                <img :src="actualImageUrl" alt="Popup Image" class="popup-image"
                     :style="{ transform: 'scale(' + currentScale + ')' }"
                     @wheel.prevent="handleWheelZoom">
            </div>

            <div class="popup-controls" v-show="!isLoading">
                <button @click="zoomIn" aria-label="Zoom In">+</button>
                <button @click="zoomOut" aria-label="Zoom Out">-</button>
            </div>
        </div>
        <!-- End QRCode Preview -->

        <!-- Add and Edit QRCode Modal-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">{{form_data.id ? 'Edit QRCode' : 'New QRCode'}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding-top: 55px;padding-bottom: 55px;">
                        <form class="d-flex gap-5">
                            <div style="width: 50%;">
                                <div class="mb-3">
                                    <label for="name" class="form-label">QRCode Name</label>
                                    <input type="text" v-model="form_data.name" class="form-control" :class="{'is-invalid':errors.name !== ''}" id="name" placeholder="Enter QRCode Name*">
                                    <div class="invalid-feedback">
                                        {{errors.name}}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="checkin_time" class="form-label">Checkin Time</label>
                                    <TimerPicker ref="checkin_ref" @change-time="onChangeCheckInTime"/>
                                    <div class="invalid-feedback">
                                        {{errors.checkin_time}}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="checkout_time" class="form-label">Checkout Time</label>
                                    <TimerPicker ref="checkout_ref" @change-time="onChangeCheckOutTime"/>
                                    <div class="invalid-feedback">
                                        {{errors.checkout_time}}
                                    </div>
                                </div>
                                <div v-if="googleMapsEmbedUrl" class="mb-3 text-center mt-5" >
                                    <button type="button" @click="openMap" class="btn btn-outline-primary">Change Location</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center flex-column gap-4" style="width: 50%;position: relative">
                                <button v-if="!googleMapsEmbedUrl" type="button" @click="openMap" class="btn btn-outline-primary">{{form_data.latitude && form_data.longitude ? 'Change Location' : 'Choose Location'}}</button>
                                <div v-if="googleMapsEmbedUrl">
                                    <iframe v-if="googleMapsEmbedUrl"
                                            :src="googleMapsEmbedUrl"
                                            style="border:0;width: 500px;height: 310px"
                                            allowfullscreen=""
                                            loading="lazy"
                                    >

                                    </iframe>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button v-if="!form_data.id" @click="save" type="button" class="btn btn-primary">Save</button>
                        <button v-else @click="saveEdit" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Add and Edit QRCode Modal-->

        <!-- Delete User Modal-->
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete QRCode?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Are you sure delete this qrcode?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button @click="onCfDelete" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delete End Delete Modal-->
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
                            :disabled="!this.filters.search && qrcode.data.length == 0"
                            autocomplete="off"
                        >
                    </div>
                </div>
                <button class="primary-button" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;New QRCode</button>
            </div>
            <!-- Map modal -->
            <div v-show="showMap" class="map-modal">
                <div id="map" style="width: 100%; height: 510px;"></div>
                <div class="d-flex gap-3 align-items-center justify-content-end py-3">
                    <button @click="closeMap" class="btn btn-secondary">Close</button>
                    <button @click="getCurrentLocation" class="btn btn-primary">Use My Location</button>
                </div>
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

            <div class="table-container" v-if="qrcode.data.length > 0">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,index) in qrcode.data" :key="'qrcode_list'+index">
                        <td>{{index+1}}</td>
                        <td>
                            <div class="qrcode-thumbnail-wrapper">
                                <img :src="'/images/qrcode/'+item.image" width="100%" height="100%">
                            </div>
                        </td>
                        <td>{{item.name}}</td>
                        <td>{{item.company.name}}</td>
                        <td>
                            <button @click="onDelete(item)" class="btn btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <button @click="onEdit(item)" class="btn btn-outline-primary mx-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button  @click="onView(item)" class="btn btn-outline-success">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="qrcode.data.length > 0" class="pagination">
                <button
                    v-for="link in qrcode.links"
                    :key="link.label"
                    :disabled="!link.url"
                    @click="goToPage(link.url)"
                    v-html="link.label"
                    class="px-2 pagination-item"
                    :class="{'pagination-item-active':link.active}"
                />
            </div>

            <span class="not-found-msg" v-if="this.filters.search && qrcode.data.length == 0">No record founded.</span>
            <span class="not-found-msg" v-if="!this.filters.search && qrcode.data.length == 0">No record.</span>
        </div>
    </MainLayout>
</template>

<script>
import TimerPicker from "@/Components/TimerPicker.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import QRCode from "qrcode";

export default{
    components: {MainLayout, TimerPicker},
    props: {
        qrcode:Object,
        filters: Object,
        company_id:Number,
    },
    mounted() {
        let vm = this;
        $("#staticBackdrop").on("hide.bs.modal", function(){
            vm.clearForms()
            vm.clearErrors()
            vm.$refs.checkin_ref.clearTime()
            vm.$refs.checkout_ref.clearTime()
        })

        $("#deleteModal").on("hide.bs.modal", function(){
            vm.form_data.id = null
        })
    },
    data(){
        return{
            success:{
                show: false,
                message: "",
            },
            form_data:useForm({
                id:null,
                name:"",
                checkin:"",
                checkout:"",
                checkin_time:{
                    hour:"",
                    minute:"",
                    am_pm:"",
                },
                checkout_time:{
                    hour:"",
                    minute:"",
                    am_pm:"",
                },
                latitude: null,
                longitude: null,
                embed_url:null,
            }),
            errors:{
                name:"",
            },
            search: this.filters.search || '',
            debounceTimer: null,
            map: null,
            marker: null,
            showMap: false,
            googleMapsEmbedUrl:null,
            isPopupActive: false,
            isLoading: false,
            imageUrl: '',
            actualImageUrl: '',
            currentScale: 1,
            zoomStep: 0.2,
            minScale: 0.4,
            maxScale: 3.0,
        }
    },
    watch: {
        // Watch for changes in isPopupActive to manage body overflow
        isPopupActive(newValue) {
            if (newValue) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    },
    methods:{
        openMap() {
            this.showMap = true;

            this.$nextTick(() => {
                if (!this.map) {
                    this.initMap();
                } else {
                    google.maps.event.clearListeners(this.map, 'click');
                    this.addMapClickListener();
                }
            });
        },
        onChangeCheckInTime(time){
            let hour = time.hour;
            let minute = time.minute;
            let am_pm = time.am_pm;
            this.form_data.checkin_time.hour = hour;
            this.form_data.checkin_time.minute = minute;
            this.form_data.checkin_time.am_pm = am_pm;
        },
        onChangeCheckOutTime(time){
            let hour = time.hour;
            let minute = time.minute;
            let am_pm = time.am_pm;
            this.form_data.checkout_time.hour = hour;
            this.form_data.checkout_time.minute = minute;
            this.form_data.checkout_time.am_pm = am_pm;
        },
        initMap() {
            const defaultLocation = { lat: -6.2, lng: 106.8 };

            this.map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 8,
            });

            this.addMapClickListener();
        },
        handleSelectedLocation(lat, lng) {
            const clickedLocation = { lat, lng };

            this.form_data.latitude = lat;
            this.form_data.longitude = lng;

            if (this.marker) {
                this.marker.setPosition(clickedLocation);
            } else {
                this.marker = new google.maps.Marker({
                    position: clickedLocation,
                    map: this.map,
                });
            }

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: clickedLocation }, (results, status) => {
                if (status === "OK" && results[0]) {
                    this.address = results[0].formatted_address;
                    this.googleMapsEmbedUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;
                    this.form_data.embed_url = this.googleMapsEmbedUrl;
                } else {
                    this.address = "Address not found";
                    this.googleMapsEmbedUrl = null;
                }
            });

            // Optional: Center map if needed
            this.map.setCenter(clickedLocation);
            this.map.setZoom(15);
        },
        addMapClickListener() {
            this.mapClickListener = this.map.addListener("click", (event) => {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();
                this.handleSelectedLocation(lat, lng);
            });
        },
        closeMap() {
            this.showMap = false;
            if (this.mapClickListener) {
                google.maps.event.removeListener(this.mapClickListener);
                this.mapClickListener = null;
            }
        },
        getCurrentLocation() {
            if (navigator.geolocation) {
                const watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        const accuracy = position.coords.accuracy;
                        console.log("Accuracy:", accuracy + " meters");
                        if (accuracy <= 150) {
                            const userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            this.latitude = parseFloat(position.coords.latitude.toFixed(6));
                            this.longitude = parseFloat(position.coords.longitude.toFixed(6));
                            this.handleSelectedLocation(this.latitude, this.longitude);

                            this.map.setCenter(userLocation);
                            this.map.setZoom(15);

                            if (this.marker) {
                                this.marker.setPosition(userLocation);
                            } else {
                                this.marker = new google.maps.Marker({
                                    position: userLocation,
                                    map: this.map,
                                });
                            }

                            // Stop watching once we get a good reading
                            navigator.geolocation.clearWatch(watchId);
                        }
                    },
                    (error) => {
                        alert("Could not get your location. Please allow location access.");
                        console.error("Geolocation error:", error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        },
        searchItems() {
            clearTimeout(this.debounceTimer)

            this.debounceTimer = setTimeout(() => {
                router.get(route('user'), { search: this.search }, {
                    preserveState: true,
                    replace: true
                })
            }, 300)
        },
        save(){
            this.form_data.checkout = this.form_data.checkout_time.hour + ":" + this.form_data.checkout_time.minute + " " + this.form_data.checkout_time.am_pm;
            this.form_data.checkin = this.form_data.checkin_time.hour + ":" +this.form_data.checkin_time.minute+" "+this.form_data.checkin_time.am_pm;
            this.form_data.embed_url = this.googleMapsEmbedUrl;
            let vm = this;
            this.clearErrors()
            this.form_data.post(route('createQRCode'),{
                onSuccess:async function(){
                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    console.log(vm.qrcode.data);
                    let token = vm.qrcode.data[0].token
                    let id = vm.qrcode.data[0].id;
                    await QRCode.toDataURL(token, {
                        width: 200,
                        margin: 2,
                    }).then( async response => {
                        const res = await fetch(response);
                        const blob = await res.blob();
                        let file = new File([blob], "qrcode.png", {type: blob.type});
                        let formData = useForm({
                            id: id,
                            image:file
                        });
                        formData.post(route('saveQRCodeImage'),{
                            onSuccess:()=>{
                                $("#staticBackdrop").modal('hide');
                                vm.success.show = true;
                                vm.success.message = "QRCode create successfully.";
                                setTimeout(()=>{
                                    vm.success.show = false;
                                    vm.success.message = "";
                                },3000)
                            }
                        })
                    })
                },
                onError:(err) =>{
                    const keys = Object.keys(err);
                    keys.forEach(key => {
                        this.errors[key] = err[key];
                    })
                    console.log(this.errors);
                }
            })
        },
        saveEdit(){
            this.form_data.checkout = this.form_data.checkout_time.hour + ":" + this.form_data.checkout_time.minute + " " + this.form_data.checkout_time.am_pm;
            this.form_data.checkin = this.form_data.checkin_time.hour + ":" +this.form_data.checkin_time.minute+" "+this.form_data.checkin_time.am_pm;
            this.form_data.embed_url = this.googleMapsEmbedUrl;
            let vm = this;
            this.clearErrors()
            this.form_data.post(route('updateQRCode'),{
                onSuccess:async function (data) {
                    let item = data.props.qrcode.data[data.props.qrcode.data.length - 1]
                    let id = item.id;
                    let token = item.token;
                    let image = item.image;

                    $("#staticBackdrop").modal('hide');
                    vm.clearForms();
                    await QRCode.toDataURL(token, {
                        width: 200,
                        margin: 2,
                    }).then(async response => {
                        const res = await fetch(response);
                        const blob = await res.blob();
                        let file = new File([blob], "qrcode.png", {type: blob.type});
                        let formData = useForm({
                            old_image:image,
                            id: id,
                            image: file
                        });
                        formData.post(route('saveQRCodeImage'), {
                            onSuccess: () => {
                                $("#staticBackdrop").modal('hide');
                                vm.clearForms()
                                vm.success.show = true;
                                vm.success.message = "QRCode updated successfully.";
                                setTimeout(()=>{
                                    vm.success.show = false;
                                    vm.success.message = "";
                                },3000)
                            }
                        })
                    })
                },
                onError:(err) =>{
                    const keys = Object.keys(err);
                    keys.forEach(key => {
                        this.errors[key] = err[key];
                    })
                    console.log(this.errors);
                }
            })
        },
        onEdit(item){
            this.form_data.id = item.id;
            this.form_data.name = item.name;

            // split checkin time
            var {hour,minute,am_pm} = this.splitTime(item.checkin_time);
            this.form_data.checkin_time.hour = hour;
            this.form_data.checkin_time.minute = minute;
            this.form_data.checkin_time.am_pm = am_pm;
            // set time to timepicker
            this.$refs.checkin_ref.setTime(hour,minute,am_pm);

            // split checkout time
            var {hour,minute,am_pm}=this.splitTime(item.checkout_time);
            this.form_data.checkout_time.hour = hour;
            this.form_data.checkout_time.minute = minute;
            this.form_data.checkout_time.am_pm = am_pm;
            // set time to timepicker
            this.$refs.checkout_ref.setTime(hour,minute,am_pm);
            this.form_data.embed_url = item.embed_url;
            this.googleMapsEmbedUrl = item.embed_url;
            this.form_data.latitude = item.latitude;
            this.form_data.longitude = item.longitude;
            $("#staticBackdrop").modal('show');
        },
        splitTime(time){
            let tmp = time.split(' ')
            let am_pm = tmp[1];
            let temp_time = tmp[0].split(':');
            let hour = temp_time[0];
            let minute = temp_time[1];
            return {hour:hour,minute:minute,am_pm:am_pm};
        },
        onDelete(item){
            this.form_data.id = item.id;
            $("#deleteModal").modal('show');
        },
        onCfDelete(){
            let vm = this
            let form = useForm({id:this.form_data.id})
            form.post(route('deleteQRCode'),{
                onSuccess:()=> {
                    console.log("delete")
                    $("#deleteModal").modal('hide')
                    vm.success.show = true;
                    vm.success.message = "QRCode delete successfully.";
                    setTimeout(()=>{
                        vm.success.show = false;
                        vm.success.message = "";
                    },3000)
                },
                onError:(err) =>{
                    console.log(err)
                }
            })
        },
        onView(item){
            this.imageUrl = `/images/qrcode/${item.image}`
            this.openPopup()
        },
        clearErrors(){
            this.errors.name = ""
        },
        clearForms(){
            this.form_data.id = null;
            this.form_data.name = "";
            this.form_data.old_embed_url = null;
            this.form_data.embed_url = null;
            this.form_data.latitude = null;
            this.form_data.longitude = null;
            this.googleMapsEmbedUrl = null
        },
        goToPage(url) {
            router.visit(url, {
                preserveScroll: true,
                preserveState: true,
            })
        },
        openPopup() {
            this.loadImage();
        },
        closePopup() {
            this.isPopupActive = false;
            this.resetZoom();
        },
        loadImage() {
            this.isLoading = true;
            this.actualImageUrl = '';

            const img = new Image();
            img.onload = () => {
                this.actualImageUrl = this.imageUrl;
                this.isLoading = false;
                this.isPopupActive = true;
                this.resetZoom();
            };
            img.onerror = () => {
                console.error('Error loading image.');
                this.isLoading = false;
                this.actualImageUrl = 'https://placehold.co/600x400/f00/fff?text=Error+Loading+Image';
                this.isPopupActive = true;
            };
            img.src = this.imageUrl;
        },
        zoomIn() {
            if (this.currentScale < this.maxScale) {
                this.currentScale += this.zoomStep;
                this.applyZoom();
            }
        },
        zoomOut() {
            if (this.currentScale > this.minScale) {
                this.currentScale -= this.zoomStep;
                this.applyZoom();
            }
        },
        handleWheelZoom(event) {
            if (!this.isPopupActive || this.isLoading) return;

            if (event.deltaY < 0) {
                if (this.currentScale < this.maxScale) {
                    this.currentScale += this.zoomStep / 2;
                }
            } else { // Scroll down (zoom out)
                if (this.currentScale > this.minScale) {
                    this.currentScale -= this.zoomStep / 2;
                }
            }
            this.applyZoom();
        },
        applyZoom() {
            this.currentScale = Math.max(this.minScale, Math.min(this.maxScale, this.currentScale));
        },
        resetZoom() {
            this.currentScale = 1;
        }
    }
}
</script>

<style>
.view-image-btn {
    padding: 12px 25px;
    font-size: 16px;
    color: white;
    background-color: #5cb85c; /* Vue Green */
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.view-image-btn:hover {
    background-color: #4cae4c;
}

.image-popup-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.85);
    display: none; /* Controlled by :class */
    justify-content: center;
    align-items: center;
    z-index: 1000;
    overflow: hidden;
}

.image-popup-modal.active {
    display: flex;
}

.popup-image-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90vw;
    height: 90vh;
}

.popup-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transform-origin: center center;
    transition: transform 0.2s ease-in-out;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    /* 'transform' is now handled by direct style binding in template */
}

.popup-controls {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(40, 40, 40, 0.8);
    padding: 10px 15px;
    border-radius: 8px;
    display: flex;
    gap: 10px;
    z-index: 1010;
}

.popup-controls button {
    background-color: #fff;
    color: #333;
    border: none;
    padding: 8px 15px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.popup-controls button:hover {
    background-color: #e0e0e0;
}

.close-popup-btn {
    position: absolute;
    top: 20px;
    right: 30px;
    background: none;
    border: none;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
    padding: 5px;
    line-height: 1;
    z-index: 1010;
    transition: color 0.2s ease;
}
.close-popup-btn:hover {
    color: #ccc;
}

.loading-indicator {
    color: white;
    font-size: 20px;
    position: absolute;
}

div.qrcode-thumbnail-wrapper{
    height: 70px;
    aspect-ratio: 2 / 2;
    border-radius: 2px;
    margin: 0 auto;
}
#no-map-preview-msg{
    font-size: 30px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    user-select: none;
}
#choose-map-wrapper{
    border: 2px dashed black;
    aspect-ratio: 2/2;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: all ease 150ms;

    &:hover{
        background-color: #e8e5e5;
    }

    & i{
        font-size: 110px;
    }
}
.map-modal {
    position: fixed;
    top: 10%;
    left: 10%;
    width: 80%;
    height: 80%;
    background: white;
    z-index: 1058;
    padding: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
    border-radius: 8px;
}
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
