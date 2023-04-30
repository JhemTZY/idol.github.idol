<!doctype html>
<html lang="en">
<?php include "template-header.php";?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="section_pending_booking_rooms">
    <div>
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Booking Rooms</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="input-group mb-3">
                    <span class="input-group-text h-100 bg-sucess">FROM</span>
                    <input type="date" class="btn btn-md btn-success" v-model="filter_date_from">
                    <span class="input-group-text h-100 bg-sucess">TO</span>
                    <input type="date" class="btn btn-md btn-success" v-model="filter_date_to" value="">
                </div>

            </div>
        </div>
        <div>
            <div class="h1 text-dark text-center">{{server_message}}</div>
            <div class="h3 text-center">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="h2">Pending</div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-md btn-outline-secondary"
                                @click="get_all_pending_bookings_for_rooms">Refresh</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="cntr_pending_bookings">
                <table class="table table-bordered table-hover ">
                    <thead class="fw-bolder text-center">
                        <tr>
                            <td> Guest Name </td>
                            <td> Guest Contact Number </td>
                            <td> Check In </td>
                            <td> Check Out </td>
                            <td> Room Name </td>
                            <td> Nights </td>
                            <td> Payment Reference # </td>
                            <td> Actions </td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr v-for="row in pending_room_bookings">
                            <td>{{row.br_guest_fname}} {{row.br_guest_lname}}</td>
                            <td>{{row.br_guest_contact_num}}</td>
                            <td>{{row.br_checkin_date}}</td>
                            <td>{{row.br_checkout_date}}</td>
                            <td>{{row.room_name}} </td>
                            <td>{{row.br_number_of_days_to_stay}}</td>
                            <td>{{row.br_payment_reference_number}}</td>
                            <td>
                                <button class="btn btn-sm w-100 btn-outline-success "
                                    @click="accept_pending_booking(row.booking_id)"> <i class="fa fa-check-circle"></i>
                                </button>
                                <button class="btn btn-sm w-100 btn-outline-danger" value="Decline"
                                    @click="decline_pending_booking(row.booking_id)"> <i class="fa fa-solid fa-ban"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div>
        <div class="h3 text-center">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Booked</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button class="btn btn-md btn-outline-secondary" @click="get_all_booked_rooms">Refresh</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered  ">
                <thead class="fw-bolder text-center">
                    <tr>
                        <td> Guest Name </td>
                        <td> Guest Contact Number </td>
                        <td> Check In </td>
                        <td> Check Out </td>
                        <td> Nights </td>
                        <td> Actions </td>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr v-for="booked in booked_room">
                        <td>{{booked.br_guest_fname}} {{booked.br_guest_lname}}</td>
                        <td>{{booked.br_guest_contact_num}}</td>
                        <td>{{booked.br_checkin_date}}</td>
                        <td>{{booked.br_checkout_date}}</td>
                        <td>{{booked.br_number_of_days_to_stay}}</td>
                        <td>
                            <button class="btn btn-sm w-100 btn-outline-info disabled" @click=""> <i
                                    class="fa fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-sm w-100 btn-outline-success"
                                @click="archive_booked_rooms(booked.booking_id)"> <i class="fa fa-solid fa-box"></i>
                            </button>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</main>


<?php include "template-footer.php"?>

<!-- VUE JS -->
<script>
var app = new Vue({
    el: '#section_pending_booking_rooms',
    data: {
        pending_room_bookings: '',
        booked_room: '',
        filter_date_from: '',
        filter_date_to: '',
        booking_id: '',
        server_message: ''
    },
    methods: {
        get_all_pending_bookings_for_rooms: function() {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "get_all_pending_room_bookings",
                    filter_date_from: this.filter_date_from,
                    filter_date_to: this.filter_date_to
                })
                .then(function(response) {
                    if (response.data.length > 0) {
                        app.pending_room_bookings = response.data;
                    } else {
                        app.pending_room_bookings = '';
                    }
                    // console.log(response.data);
                })
        },
        get_all_booked_rooms: function($event) {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "get_all_booked_rooms",
                    filter_date_from: this.filter_date_from,
                    filter_date_to: this.filter_date_to
                }).then(function(response) {
                    if (response.data.length > 0) {
                        app.booked_room = response.data;
                    } else {
                        app.booked_room = '';
                    }
                })
        },
        accept_pending_booking: function(b_id) {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "accept_pending_booking",
                    booking_id: b_id,
                }).then(function(response) {
                    if (response.data.length > 0) {
                        app.server_message = response.data;
                        app.pending_room_bookings = '';
                        setInterval(() => {
                            app.server_message = '';
                            app.get_all_pending_bookings_for_rooms();
                        }, 3000)

                    } else {
                        // app.server_message = '';
                    }
                })
        },
        decline_pending_booking: function(b_id) {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "decline_pending_booking",
                    booking_id: b_id,
                }).then(function(response) {
                    if (response.data.length > 0) {
                        app.server_message = response.data;
                        app.pending_room_bookings = '';
                        setInterval(() => {
                            app.server_message = '';
                            app.get_all_pending_bookings_for_rooms();
                        }, 3000)

                    } else {
                        // app.server_message = '';
                    }
                })
        },
        archive_booked_rooms: (b_id) => {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "archive_booked_rooms",
                    booking_id: b_id,
                }).then(function(response) {
                    if (response.data.length > 0) {
                        app.server_message = response.data;
                        app.booked_room = '';
                        setInterval(() => {
                            app.server_message = '';
                            app.get_all_booked_rooms();
                        }, 3000)

                    } else {
                        // app.server_message = '';
                    }
                })
        }

    },

    created: function() {
        this.get_all_pending_bookings_for_rooms();
        this.get_all_booked_rooms();
    }
});
</script>




</body>

</html>