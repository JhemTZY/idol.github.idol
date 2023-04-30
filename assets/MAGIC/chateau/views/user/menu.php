<?php include "temp_header.php" ?>

<!-- Menu Start -->
<div class="container-xxl py-5 px-3" id="section_menu">
    <div class="wow fadeInUp">
        <h1 class="mb-5">MENU <span class="text-info text-uppercase"></span></h1>
        <!-- <button class="btn btn-lg w-100 btn-dark" onclick="javascript=$('#ordering_details_modal').modal('show')">OPEN
            ORDERING DETAILS MODAL</button> -->


        <!-- <h6 class="section-title text-center text-info text-uppercase">Sinandigan Puerto Galera</h6> -->
        <ul class="nav  nav-pills nav-justified ">
            <li class="nav-item btn-fill d-grid gap-2">
                <a class=" active btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('RECOMMENDED')">RECOMMENDED
                    <!-- <img src="http://www.pngall.com/wp-content/uploads/2016/05/Best-Quality-Download-PNG.png" class="img-responsive img-thumbnail" width="120"  alt="RECOMMENDED"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('Silog')">SILOG
                    <!-- <img src="https://3.bp.blogspot.com/-BzPAsRxXZ7o/VrHDH_nfwcI/AAAAAAAANqA/d8T8wlG73hs/s1600/Top-Silog-Distric%2B%252822%2529.jpg" class="img-responsive img-thumbnail" width="120"  alt="SILOG"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('BURGER and PIZZA')">BURGER/PIZZA
                    <!-- <img src="https://preview.redd.it/j5edwbln66u41.jpg?auto=webp&s=b2f93c7b977e7591c50f21e1633802b2211865ee" class="img-responsive img-thumbnail" width="120"  alt="BURGER/PIZZA"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('KAMBING')">KAMBING
                    <!-- <img src="https://i.pinimg.com/originals/3d/dd/f9/3dddf9108de2477e439cc54030a765f6.jpg" class="img-responsive img-thumbnail" width="120"  alt="KAMBING"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('SEAFOODS')">SEAFOODS
                    <!-- <img src="https://lh6.googleusercontent.com/proxy/lqd3KfPcocnlA3TLdel0014kU0-Z8h-0nhXChKGJxyWXrexfbTQ_y2WHPkTjZ49JD7PogsMQvOaZDIjMBNGL7fPNebwSP7qabKbdxUs_5erZ505b8clllunIT_oT8wYVnQfTDFg=w1200-h630-p-k-no-nu" class="img-responsive img-thumbnail" width="120"  alt="SEAFOODS"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('BEEF')">BEEF
                    <!-- <img src="https://www.biomerieux-industry.com/sites/default/files/2019-05/SSG-FAH-meat-rawBeef_0.jpg" class="img-responsive img-thumbnail" width="120"  alt="BEEF"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('PORK')">PORK
                    <!-- <img src="https://baysmeatmarket.com/newsite/wp-content/uploads/2018/05/pork-chops.jpg" class="img-responsive img-thumbnail" width="120"  alt="PORK"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('CHICKEN')">CHICKEN
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="CHICKEN"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('VEGETABLES')">VEGETABLES
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="VEGETABLES"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('KOREAN FOOD')">KOREAN FOOD
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="KOREAN FOOD"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('PASTA')">PASTA
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="PASTA"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('SAMGYUPSAL')">SAMGYUPSAL
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="SAMGYUPSAL"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('PANCIT BILAO')">PANCIT BILAO
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="PANCIT BILAO"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('PANCIT SOLO')">PANCIT SOLO
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="PANCIT SOLO"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('DESSERT')">DESSERT
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="DESSERT"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('SHAKES')">SHAKES
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="SHAKES"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('COLD DRINKS')">COLD DRINKS
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="COLD DRINKS"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('COFFEE')">COFFEE
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="COFFEE"> -->
                </a>
            </li>
            <li class="nav-item btn-fill d-grid gap-2 ">
                <a class=" btn btn-outline-dark btn-lg border mb-1 mx-1  "
                    @click="get_menu_based_on_category('BEVERAGES')">BEVERAGES
                    <!-- <img src="THEQUICKBROWNFOXJUMPSOVERTHEAXYDOG" class="img-responsive img-thumbnail" width="120"  alt="BEVERAGES"> -->
                </a>
            </li>

        </ul>
    </div>


    <!-- Ordering Menu Modal -->
    <?php
        // Guest ID.
        $guest_id = "G-".substr($server_year,2).$server_month.$server_datetoday.substr(mt_rand(1000,999999),1);
    ?>
    <div class="modal fade" id="ordering_menu_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h3" id="exampleModalLabel">ORDERING - {{menu_category}}</div>
                    <div class="mx-auto h6 bg-success text-white text-center px-1 py-1 rounded-pill">GUEST ID:
                        <?=$guest_id;?>
                    </div>
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class='row px-3 py-3'>
                        <div class="col-lg-6 shadow px-3 py-3">
                            <div class="row g-3" v-for="data in menu">
                                <div class="col-lg-6">
                                    <div class="mb-3" style="max-width: 540px;">
                                        <div class="row g-0">
                                            <div class="col-md-12">
                                                <img v-bind:src="data.menu_item_photo_link"
                                                    class="img-fluid rounded-start" alt="Food Photo">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mb-3" style="max-width: 540px;">
                                        <div class="row g-0">
                                            <div class="col-md-12">
                                                <div class="card-header ">
                                                    <h4 class="  text-uppercase">{{data.menu_item_name}} | PHP
                                                        {{data.menu_item_price}} </h4>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-text"> {{data.menu_item_description}}</p>
                                                    <!-- <p class="card-text"><small class="text-muted"> Status: {{data.menu_item_status}} </small></p> -->
                                                    <div class="btn btn-lg w-100 btn-success"
                                                        @click="add_to_cart(data.menu_item_id)"> <i
                                                            class="fa fa-solid fa-cart-plus"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-12 ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="alert alert-dark h4" role="alert">
                                                    {{ordering_server_message}}
                                                </div>
                                                <div class="card-body shadow">
                                                    <h1 class="text-center"> <i class="fa fa-solid bi-list-task"></i>
                                                        ORDERS</h1>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <td>Item name</td>
                                                                    <td>Quantity</td>
                                                                    <td>Price</td>
                                                                    <td>Subtotal</td>
                                                                    <td>Actions</td>
                                                                </tr>
                                                            </thead>

                                                            <tbody v-for="data in active_cart_items">
                                                                <tr>
                                                                    <td> {{data.menu_item_name}} </td>
                                                                    <td> {{data.cart_quantity}} </td>
                                                                    <td> {{data.menu_item_price}} </td>
                                                                    <td> {{data.subtotal}}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn btn-sm w-100 btn-outline-dark"
                                                                            @click="add_to_cart(data.menu_item_id)">
                                                                            <i class="fa fa-solid fa-plus"></i>
                                                                        </div>
                                                                        <div class="btn btn-sm w-100 btn-outline-dark"
                                                                            @click="remove_from_cart(data.menu_item_id)">
                                                                            <i class="fa fa-solid fa-minus"></i>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="h4 text-center">Total: PHP
                                                            {{total_amount_of_active_orders}} </div>
                                                        <button class="btn btn-danger btn-lg w-100"
                                                            @click="place_order">Place
                                                            Order</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Ordering Menu Modal End -->

    <!-- Ordering Details Modal -->
    <div class="modal fade" id="ordering_details_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="ordering_details_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h2" id="ordering_details_modal">ORDERING DETAILS</div>
                    <div class=" mx-auto h6 bg-success text-white text-center px-1 py-1 rounded-pill">GUEST ID:
                        <?=$guest_id;?> </div>
                    <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class='row px-3 py-3'>
                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-12 ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <!-- <div class="alert alert-dark h4" role="alert">
                                                        {{ordering_server_message}}
                                                    </div> -->
                                                <div class="card-body shadow">
                                                    <h1 class="text-center"> <i class="fa fa-solid bi-list-task"></i>
                                                        REVIEW ORDERS</h1>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <td>Item name</td>
                                                                    <td>Quantity</td>
                                                                    <td>Price</td>
                                                                    <td>Subtotal</td>
                                                                    <td>Actions</td>
                                                                </tr>
                                                            </thead>

                                                            <tbody v-for="data in placed_cart_items">
                                                                <tr>
                                                                    <td> {{data.menu_item_name}} </td>
                                                                    <td> {{data.cart_quantity}} </td>
                                                                    <td> {{data.menu_item_price}} </td>
                                                                    <td> {{data.cart_quantity * data.menu_item_price}}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn btn-sm w-100 btn-outline-dark"
                                                                            @click="remove_from_cart(data.menu_item_id)">
                                                                            <i class="fa fa-solid fa-minus"></i>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 shadow px-3 py-3">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="h3 text-center text-uppercase text-muted">Guest Details</div>
                                    <div class="row">
                                        <div class="mb-2 col-sm-6">
                                            <div class="form-floating">
                                                <input name="txt_ordering_guest_fname" type="text"
                                                    v-model="order_guest_fname" class="form-control bg-white"
                                                    id="txt_ordering_guest_fname" />
                                                <label for="txt_ordering_guest_fname">First Name</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-6">
                                            <div class="form-floating">
                                                <input name="txt_ordering_guest_lname" type="text"
                                                    v-model="order_guest_lname" class="form-control bg-white"
                                                    id="txt_ordering_guest_lname" />
                                                <label for="txt_ordering_guest_lname">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-12">
                                            <div class="form-floating">
                                                <input name="txt_ordering_guest_contact_number" type="text"
                                                    v-model="order_guest_contact" class="form-control bg-white"
                                                    id="txt_ordering_guest_contact_number" />
                                                <label for="txt_ordering_guest_contact_number">Contact Number</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-12">
                                            <div class="form-floating">
                                                <input name="txt_ordering_guest_email" type="email"
                                                    v-model="order_guest_email" class="form-control bg-white"
                                                    id="txt_ordering_guest_email" />
                                                <label for="txt_ordering_guest_email">Email</label>
                                            </div>
                                        </div>
                                        <!-- <div class="h3 text-center text-uppercase text-muted">Ordering Details</div> -->

                                        <div class="mb-2 col-sm-12 px-3 py-3 text-center">
                                            <div class="form-floating position-relative px-3  ">
                                                <div class="form-check form-check-inline px-3 ">
                                                    <input class="form-check-input h2" type="radio" value="Pickup"
                                                        v-model="order_mode_of_order" id="txt_ordering_om_pickup"
                                                        checked>
                                                    <label class="form-check-label h2 btn btn-outline-dark rounded-pill"
                                                        for="txt_ordering_om_pickup">
                                                        Pickup
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline px-3 ">
                                                    <input class="form-check-input h2" type="radio" value="Deliver"
                                                        v-model="order_mode_of_order" id="txt_ordering_om_deliver">
                                                    <label class="form-check-label h2 btn btn-outline-dark rounded-pill"
                                                        for="txt_ordering_om_deliver">
                                                        Deliver
                                                    </label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-6">
                                            <div class="form-floating">
                                                <input name="txt_ordering_order_date" type="date"
                                                    v-model="order_pickup_deliver_date" class="form-control bg-white"
                                                    id="txt_ordering_order_date" />
                                                <label for="txt_ordering_order_date">Date</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-6">
                                            <div class="form-floating">
                                                <input name="txt_ordering_order_time" type="time"
                                                    v-model="order_pickup_deliver_time" class="form-control bg-white"
                                                    id="txt_ordering_order_time" />
                                                <label for="txt_ordering_order_time">Time</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-12 text-center">
                                            <div class="form-floating position-relative px-3  ">
                                                <div class="form-check form-check-inline px-3 btn ">
                                                    <input class="form-check-input h2" type="radio" value="Pickup"
                                                        @click="get_total_amount_of_placed_orders"
                                                        v-model="order_payment_method" id="txt_ordering_payment_method"
                                                        checked>
                                                    <label class="form-check-label h3 text-primary "
                                                        for="txt_ordering_payment_method_gcash">
                                                        G-Cash (P2P)
                                                    </label>
                                                </div>
                                                <!-- <p>Mode of Order: {{order_mode_of_order}} </p> -->
                                                <div class="h3" v-if="order_mode_of_order == 'Pickup'">
                                                    Downpayment: {{total_amount_of_placed_orders/2}} <small class="h6">
                                                        <br> Send your downpayment to 09171678331 (Vilma A.)
                                                        with a message '<?=$guest_id;?>' </small>
                                                </div>
                                                <div class="h3" v-if="order_mode_of_order == 'Deliver'">
                                                    Amount to Pay: {{total_amount_of_placed_orders}} <small class="h6">
                                                        <br> Send your full payment to 09171678331 (Vilma A.)
                                                        with a message '<?=$guest_id;?>' </small>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-12">
                                            <div class="form-floating">
                                                <input name="txt_ordering_payment_reference_number" type="text"
                                                    v-model="order_reference_number" class="form-control bg-white"
                                                    id="txt_ordering_payment_reference_number" />
                                                <label for="txt_ordering_payment_reference_number">Payment Reference
                                                    Number</label>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-sm-12" v-for="dt in order_confirmation_server_message">
                                            <div class="alert alert-success" v-if="dt=='Order is successful.'"> {{dt}} </div>
                                            <div class="alert alert-primary" v-if="dt!='Order is successful.'"> {{dt}} </div>
                                        </div>
                                        <div class="mb-2 col-sm-12">
                                            <button class="btn btn-dark w-100 rounded-pill"
                                                @click="confirm_order">CONFIRM
                                                ORDER</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- Ordering Details Modal End -->

    </div>
    <!-- Menu End -->

</div>

<br> <br> <br> <br> <br> <br>

<?php include "temp_footer.php"; ?>
</body>

<script>
var app = new Vue({
    el: "#section_menu",
    data: {
        menu_category: '',
        menu: '',
        active_cart_items: '',
        placed_cart_items: '',
        ordering_server_message: '',
        total_amount_of_active_orders: '',
        total_amount_of_placed_orders: '',
        order_confirmation_server_message:'',
        // ORDERING DATAS
        order_guest_fname: '',
        order_guest_lname: '',
        order_guest_contact: '',
        order_guest_email: '',
        order_mode_of_order: '',
        order_pickup_deliver_date: '',
        order_pickup_deliver_time: '',
        order_payment_method: '',
        order_reference_number: '',
    },
    methods: {
        get_menu_based_on_category: (menu_category) => {
            $("#ordering_menu_modal").modal("show");
            app.menu_category = menu_category;
            axios
                .post("controller/Ordering.php", {
                    request: "get_menu_based_on_category",
                    menu_category: menu_category
                })
                .then((response) => {
                    app.menu = response.data;
                    // console.log(response.data);
                })
        },
        get_active_cart_items: () => {
            axios
                .post("controller/Ordering.php", {
                    request: "get_active_cart_items",
                    guest_id: "<?=$guest_id;?>",
                })
                .then((response) => {
                    app.active_cart_items = response.data;
                    app.get_total_amount_of_active_orders();
                    // console.log(response.data);
                })
        },
        get_placed_cart_items: () => {
            axios
                .post("controller/Ordering.php", {
                    request: "get_placed_cart_items",
                    guest_id: "<?=$guest_id;?>",
                })
                .then((response) => {
                    app.placed_cart_items = response.data;
                    // app.get_total_amount_of_active_orders();
                    // console.log(response.data);
                })
        },
        get_total_amount_of_active_orders: () => {
            axios
                .post("controller/Ordering.php", {
                    request: "get_total_amount_of_active_orders",
                    guest_id: "<?=$guest_id;?>",
                })
                .then((response) => {
                    app.total_amount_of_active_orders = response.data;
                    // console.log(response.data);
                })
        },
        get_total_amount_of_placed_orders: () => {
            axios
                .post("controller/Ordering.php", {
                    request: "get_total_amount_of_placed_orders",
                    guest_id: "<?=$guest_id;?>",
                })
                .then((response) => {
                    app.total_amount_of_placed_orders = response.data;
                })
        },
        add_to_cart: (food_id) => {
            axios
                .post("controller/Ordering.php", {
                    request: "add_to_cart",
                    food_id: food_id,
                    guest_id: "<?=$guest_id;?>",
                })
                .then((response) => {
                    app.get_active_cart_items();

                })
        },
        // cart_abandoned: () => {
        //     axios
        //         .post("controller/Ordering.php", {
        //             request: "cart_abandoned",
        //             guest_id: "<?=$guest_id;?>",
        //         })
        //         .then((response) => {
        //             // console.log(response.data);
        //             app.get_active_cart_items();
        //         })
        // },
        remove_from_cart: (food_id) => {
            axios
                .post("controller/Ordering.php", {
                    request: "remove_from_cart",
                    guest_id: "<?=$guest_id;?>",
                    food_id: food_id
                })
                .then((response) => {
                    // console.log(response);
                    app.get_active_cart_items();
                    app.get_placed_cart_items();
                    app.get_total_amount_of_placed_orders();
                })
        },
        place_order: () => {
            if (app.active_cart_items.length > 0) {
                axios
                    .post("controller/Ordering.php", {
                        request: "place_order",
                        guest_id: "<?=$guest_id;?>",
                    })
                    .then((response) => {
                        app.get_placed_cart_items();
                        app.ordering_server_message = response.data;
                        setTimeout(() => {
                            app.ordering_server_message = "";
                            $("#ordering_details_modal").modal("show");
                        }, 1000);
                        // window.location = "Menu";
                    })
            } else {
                app.ordering_server_message = "Add some order first.";
                setTimeout(() => {
                    app.ordering_server_message = "";
                }, 3000);
            }

        },
        confirm_order: () => {
            axios
                .post("controller/Ordering.php", {
                    request: "confirm_order",
                    order_id: "<?="ORDR-".gmdate('Y',$timestamp).gmdate('m',$timestamp).gmdate('d',$timestamp)."-".substr(mt_rand(999,99999999),3);?>",
                    order_guest_id: '<?=$guest_id?>',
                    order_guest_fname: app.order_guest_fname,
                    order_guest_lname: app.order_guest_lname,
                    order_guest_contact: app.order_guest_contact,
                    order_guest_email: app.order_guest_email,
                    order_mode_of_order: app.order_mode_of_order,
                    order_pickup_deliver_date: app.order_pickup_deliver_date,
                    order_pickup_deliver_time: app.order_pickup_deliver_time,
                    order_payment_method: app.order_payment_method,
                    order_total_amount_to_pay: app.total_amount_of_placed_orders,
                    order_reference_number: app.order_reference_number,
                })
                .then((response) => {
                    // console.log(response.data);
                    app.order_confirmation_server_message = response.data;
                    if(response.data[0]=="Order is successful."){
                        setTimeout(()=>{window.location="Menu"},3000)
                    }
                    setTimeout(()=>{app.order_confirmation_server_message='';},2000)
                })
        }
    },
    created: () => {

        // window.addEventListener('beforeunload', (event) => {
        //     // Cancel the event as stated by the standard.
        //     event.preventDefault();
        //     // Chrome requires returnValue to be set.
        //     event.returnValue =
        //         "Are you sure you want to leave? Items in your cart will be deleted!";
        //     app.cart_abandoned();
        // });

    }
})
</script>

</html>