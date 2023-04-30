<?php
include "model/general_model.php";
include "model/connection.php";
$connection = new Connection();
$konek = $connection->connect();
$general_model = new General_model($konek);
?>



        <?php include "temp_header.php" ?>


        <!-- Page Header Start -->
        <!-- <div class="container-fluid page-header mb-5 p-0" style="background-image: url(views/user/img/profile.jpg);">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Rooms</h1>
                    <nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center text-uppercase">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item"><a href="#">Pages</a></li>
							<li class="breadcrumb-item text-white active" aria-current="page">Rooms</li>
						</ol>
					</nav>
                </div>
            </div>
        </div> -->
        <!-- Page Header End -->


        <!-- Room Start -->
        <div class=" py-5 px-3">
            <div class="text-center wow fadeInUp">
                <marquee behavior="" direction="" class="text-dark h4"> Check In: 12:00 PM | Check Out: 1:00 PM | 50%
                    Downpayment | 20%
                    Cancellation Rate </marquee>
                <h6 class="section-title text-center text-info text-uppercase">Our Rooms</h6>
                <h1 class="mb-5">Explore Our <span class="text-info text-uppercase">Rooms</span></h1>
            </div>
            <div class="row g-4">
                <?php
					$rooms = $general_model->select_all_from_where("tbl_rooms", 'room_status', 'Available');
					foreach ($rooms as $room) :
					?>
                <div class="col-lg-3 col-md-6 ">
                    <div class="room-item shadow rounded overflow-hidden">
                        <div class="position-relative">
                            <img class="img-fluid" src="views/user/img/Rooms/<?= $room['room_photo_link']; ?>"
                                alt="Room 1">
                            <small class="h5 position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">PHP <?= $room['room_price'] ?>/Night</small>
                        </div>
                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0"><?= $room['room_name'] ?></h5>
                                <!-- <div class="ps-2">
              										<small class="fa fa-star text-primary"></small>
              										<small class="fa fa-star text-primary"></small>
              										<small class="fa fa-star text-primary"></small>
              										<small class="fa fa-star text-primary"></small>
              									</div> -->
                            </div>
                            <!-- <div class="d-flex mb-3">
                                <small class=" me-3 pe-3"><i class="text-primary"></i></small>
                                <small class="border-end me-3 pe-3"><i class="fa fa-bath text-primary me-2"></i>2 Bath</small>
									<small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small>
                            </div> -->
                            <p class="text-body mb-3"><?= $room['room_details'] ?></p>
                            <div class="d-flex justify-content-between">
                                <!-- <a class="btn btn-sm btn-primary rounded py-2 px-4" href="">View Detail</a> -->
                                <a class="btn btn-sm btn-dark rounded py-2 px-4" name="<?= md5($room['room_id']); ?>"
                                    id="<?=$room['room_photo_link']?>"
                                    onclick="select_room(this.name,this.id,<?=$room['room_price']/2?>,<?=$room['room_price']?>,'<?=$room['room_name']?>')"
                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="No registration required">
                                    Book Now </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Room End -->

        <!-- Booking Modal -->
        <?php
            // Booking ID to be used for Transactions, This will be auto generated when the window loads.
            $booking_id = "CBR-".mt_rand(1000,999999);
        ?>
        <div class="modal fade" id="booking_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">BOOKING</h5>
                        <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class='row px-3 py-3'>
                            <div class="col-lg-6">
                                <div class="row g-3">
                                    <div class="col-12 ">
                                      <div class="row">
                                        <div class="col-sm-6 h4 text-center" id="lbl_room_name"></div>
                                        <div class="col-sm-6 h4 text-center" id="lbl_room_price_original">Price: </div>
                                      </div>
                                        <img class="img-fluid rounded w-100 " id="modal_room_photo" src=""
                                            alt="Room Photo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <form method="POST" action="Booking-Room">
                                    <div class="row g-3">

                                        <!-- HIDDEN FORMS START-->
                                        <div class="col-md-12" hidden>
                                            <div class="form-floating">
                                                <input name="txt_room_id" type="text" class="form-control bg-white"
                                                    id="txt_room_id" />
                                                <label for="name">Room ID</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12" hidden>
                                            <div class="form-floating">
                                                <input name="txt_booking_id" type="text" class="form-control bg-white"
                                                    value="<?=$booking_id;?>" />
                                                <label for="name">Booking ID: </label>
                                            </div>
                                        </div>
                                        <!-- HIDDEN FORMS END -->
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input name="" type="text" class="form-control bg-white"
                                                    value="<?=$booking_id;?>" disabled />
                                                <label for="name">Booking ID: </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input name="txt_client_fname" type="text" class="form-control"
                                                    id="txt_client_fname" required />
                                                <label for="name">First Name:</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input name="txt_client_lname" type="text" class="form-control"
                                                    id="txt_client_lname" required />
                                                <label for="txt_client_lname">Last Name:</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input name="txt_client_phone_number" type="text" class="form-control"
                                                    id="txt_client_phone_number" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="To inform you on your booking status." required />
                                                <label for="txt_client_phone_number">Contact Number:</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating">
                                                <input name="txt_client_email" type="email" class="form-control"
                                                    id="txt_client_email" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="To inform you on your booking status." required />
                                                <label for="txt_client_email">Email:</label>
                                            </div>
                                        </div>
                                        <p class="text-info text-center lead" id="lbl_server_message_booking"></p>
                                        <div class="col-md-6">
                                            <label for="txt_checkin_date">Check In:</label>
                                            <input name="txt_checkin_date" type="date" class="form-control"
                                                id="txt_checkin_date" placeholder="Check In" data-target="#date3"
                                                data-toggle="datetimepicker" required />
                                        </div>
                                        <div class="col-md-6">
                                            <label for="txt_checkout_date">Check Out:</label>
                                            <input name="txt_checkout_date" type="date" class="form-control"
                                                id="txt_checkout_date" placeholder="Check Out" data-target="#date4"
                                                data-toggle="datetimepicker" required />
                                        </div>
                                        <div class="col-md-12" hidden>
                                            <div class="form-floating">
                                                <input name="txt_nights" type="text" class="form-control bg-white"
                                                    id="txt_nights" />
                                                <label for="txt_nights">Nights</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6" >
                                            <div class="form-floating">
                                                <input name="txt_days" type="text" class="form-control bg-white"
                                                    id="txt_days" disabled/>
                                                <label for="txt_days">Days</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6" >
                                            <div class="form-floating">
                                                <input name="txt_nights_copy" type="text" class="form-control bg-white"
                                                    id="txt_nights_copy" disabled/>
                                                <label for="txt_nights_copy">Nights</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="">
                                                <h5 for="checkout">PAYMENT METHOD:</h5>
                                                <a class="btn btn-outline-info" id="btn_gcash_p2p"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="GCASH Peer to Peer">GCASH
                                                    (P2P)</a>
                                                <a class="btn btn-outline-dark disabled" id="btn_paypal"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="This payment mode is not yet available" hidden>PAYPAL</a>
                                            </div>



                                            <div id="cntr_gcash_p2p">
                                                <blockquote class="text-dark">Dear Customer, when using Gcash Peer to
                                                    Peer as your mode of payment,
                                                    make sure you are sending the exact amount for Downpayment unless it
                                                    will be returned and your booking will be cancelled. </blockquote>
                                                <small>Send your Payment to 09171678331 (VILMA A.) or use your GCASH app
                                                    to scan and pay with a message <u><?=$booking_id;?></u> </small>
                                                <div class="form-floating">
                                                    <input name="txt_downpayment" type="text"
                                                        class="form-control bg-white" id="txt_downpayment"  hidden/>
                                                    <input type="text" class="form-control bg-white"
                                                        id="txt_downpayment_copy" disabled />
                                                    <label for="txt_downpayment_copy">Downpayment</label>
                                                </div>
                                                <!-- QR Code of Payment Recepient -->
                                                <!-- <div class="form-control bg-white">
                                                    <img src="views/user/img/icons/vilma_c_gcash_qr.png"
                                                        class="rounded mx-auto d-block img-fluid" alt="09171678331">
                                                </div> -->
                                                <div class="" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    title="Paste your Reference Number from the GCASH App after your payment">
                                                    <label for="txt_payment_reference_number">Reference Number (Proof of Payment)</label>
                                                    <input name="txt_payment_reference_number" type="text"
                                                        class="form-control" required id="txt_payment_reference_number" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-dark w-100 py-3" type="submit"
                                                id="btn_booking_submit">Book</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Booking Modal End -->


    <br> <br> <br> <br> <br> <br>

    <?php include "temp_footer.php"; ?>

    <script type="text/javascript">
    $(document).ready(() => {
        var server_date = "<?=$date_html?>";

        $("#txt_checkin_date").on('change', () => {
            var checkin_date = $("#txt_checkin_date").val();
            var checkout_date = $("#txt_checkout_date").val();
            var room_id = $("#txt_room_id").val();

            if (checkin_date < server_date) {
                $("#txt_checkin_date").val("");
            }
            // If user attempts to override check in date after selecting the checkout date, check out date will be reset.
            if (checkout_date < server_date || checkout_date <= checkin_date ) {
                $("#txt_checkout_date").val("");
            }
            compute_days_of_stay();

            // Check if there are existing booking for this date.
            $.ajax({
                type:"POST",
                url:"controller/Booking_Rooms.php", 
                data: {request:"check_existing_booking",room_id:room_id,checkin_date:checkin_date},
                success:(res)=>{
                    $("#lbl_server_message_booking").html(res);
                }

            });
        });
        $("#txt_checkout_date").on('change', () => {
            var checkin_date = $("#txt_checkin_date").val();
            var checkout_date = $("#txt_checkout_date").val();
            if (checkout_date < server_date || checkout_date <= checkin_date ) {
                $("#txt_checkout_date").val("");
            }
            compute_days_of_stay();
        });
        // BOOK Button is Clicked
        $("#btn_booking_submit").on('click', () => {
            var checkin_date = $("#txt_checkin_date").val();
            // console.log("Checkin Date: " + checkin_date);
            // console.log("Server Date: " + server_date);
        });
        // Hide Peer to Peer Payment Details
        $("#cntr_gcash_p2p").hide();
        // Display Reference Number Textbox after clicking button GCASH P2P
        $("#btn_gcash_p2p").on('click', () => {
            $("#cntr_gcash_p2p").toggle();
        });
    });
    // Function to Get the Details of the Selected Room when the Button Book Now is clicked.
    function select_room(room_id, room_photo_link, room_price_half,room_price_original, room_name) {
        $("#txt_room_id").val(room_id);
        $("#txt_downpayment").val(room_price_half);
        $("#txt_downpayment_copy").val("PHP " + room_price_half);
        // Store to local storage the price of the Room for later usage.
        window.localStorage.setItem("RoomPriceHalf",room_price_half);
        // Show Original Price at the bottom of the Room Picture
        $("#lbl_room_price_original").html("Price: "+room_price_original);
        $("#lbl_room_name").html(room_name);
        $("#booking_modal").modal('show');
        // Link Room Photo from page to Modal
        $("#modal_room_photo").prop("src", "views/user/img/Rooms/" + room_photo_link + "");
        compute_total_amount_to_pay();
    }
    // Function to Compute the Days of Stay based on Date of Check in and Check out.
    function compute_days_of_stay(){
      var checkin_date = new Date($("#txt_checkin_date").val());
      var checkout_date = new Date($("#txt_checkout_date").val());
      var days_of_stay = parseInt((checkout_date - checkin_date)/(24*3600*1000));
      $("#txt_nights").val(days_of_stay);
      $("#txt_nights_copy").val(days_of_stay);
      var days = days_of_stay-1;
      $("#txt_days").val(days);
      compute_total_amount_to_pay();
    }
    // Function to Compute total payment. Room Price / 2 * Number of Days to Stay.
    function compute_total_amount_to_pay(){
      var room_price = window.localStorage.getItem("RoomPriceHalf");
      var days_of_stay = $("#txt_nights").val();
      var total_amount_to_pay = room_price * days_of_stay;
      $("#txt_downpayment").val(total_amount_to_pay);
      $("#txt_downpayment_copy").val("PHP " + total_amount_to_pay);
    }
    </script>

</body>

</html>
