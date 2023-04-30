<!doctype html>
<html lang="en">
<?php include "template-header.php";?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="section_pending_booking_rooms">
    <div>
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">ORDERING</h1>
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
                                @click="get_all_pending_orders">Refresh</button>
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
                            <td> Order Date </td>
                            <td> Order Time </td>
                            <td> Orders </td>
                            <td> Downpayment </td>
                            <td> Payment Reference # </td>
                            <td> Actions </td>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr v-for="row in pending_orders">
                            <td>{{row.order_guest_fname}} {{row.order_guest_lname}}</td>
                            <td>{{row.order_guest_contact_number}}</td>
                            <td>{{row.order_pickup_or_deliver_date}}</td>
                            <td>{{row.order_pickup_or_deliver_time}}</td>
                            <td>
                                <button class="btn btn-sm w-100 btn-outline-success rounded-pill"
                                    @click="get_placed_orders_by_guest_id(row.order_guest_id)"> VIEW </button>
                            </td>
                            <td>{{row.order_total_amount_to_pay /2}}</td>
                            <td>{{row.order_payment_reference_number}}</td>
                            <td>
                                <button class="btn btn-sm w-100 btn-outline-success rounded-pill " @click=""> <i
                                        class="fa fa-check-circle"></i>
                                </button>
                                <button class="btn btn-sm w-100 btn-outline-danger rounded-pill" value="Decline"
                                    @click=""> <i class="fa fa-solid fa-ban"></i>
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
                <h1 class="h2">Reserved</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button class="btn btn-md btn-outline-secondary"
                            @click="get_all_pending_orders">Refresh</button>
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
                <!-- <tbody class="text-center">
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
                </tbody> -->
            </table>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_view_orders">
        Launch static backdrop modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="modal_view_orders" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modal_view_ordersLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_view_ordersLabel">ORDERS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" id="cntr_placed_orders_by_guest_id">
                        <table class="table table-bordered table-hover ">
                            <thead class="fw-bolder text-center">
                                <tr>
                                    <td> Item Name </td>
                                    <td> Quantity </td>
                                    <td> Price </td>
                                    <td> Subtotal </td>
                                    <td> Actions </td>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-for="row in placed_orders_by_guest_id">
                                    <td>{{row.menu_item_name}}</td>
                                    <td>{{row.cart_quantity}}</td>
                                    <td>{{row.menu_item_price}}</td>
                                    <td>{{row.subtotal}}</td>
                                    <!-- <td>
                                        <button class="btn btn-sm w-100 btn-outline-success rounded-pill " @click=""> <i
                                                class="fa fa-check-circle"></i>
                                        </button>
                                        <button class="btn btn-sm w-100 btn-outline-danger rounded-pill" value="Decline"
                                            @click=""> <i class="fa fa-solid fa-ban"></i>
                                        </button>
                                    </td> -->
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include "template-footer.php"?>

<!-- VUE JS -->
<script>
var app = new Vue({
    el: '#section_pending_booking_rooms',
    data: {
        pending_orders: '',
        reserved_orders: '',
        filter_date_from: '',
        filter_date_to: '',
        server_message: '',
        placed_orders_by_guest_id:'',
    },
    methods: {
        get_all_pending_orders: function() {
            axios
                .post("controller/admin.php", {
                    request: "get_all_pending_orders",
                    filter_date_from: this.filter_date_from,
                    filter_date_to: this.filter_date_to
                })
                .then(function(response) {
                    if (response.data.length > 0) {
                        app.pending_orders = response.data;
                    } else {
                        app.pending_orders = '';
                    }
                    // console.log(response.data);
                })
        },
        get_placed_orders_by_guest_id: (order_guest_id) => {
            axios
                .post("controller/admin.php", {
                    request: "get_placed_orders_by_guest_id",
                    order_guest_id: order_guest_id
                })
                .then((response) => {
                    $("#modal_view_orders").modal("show");
                    app.placed_orders_by_guest_id = response.data;
                    console.log(response);
                })
        },


    },

    created: function() {
        this.get_all_pending_orders();
        // this.get_all_booked_rooms();
    }
});
</script>

</body>

</html>