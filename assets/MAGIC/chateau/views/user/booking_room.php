<?php
include "model/connection.php";
include "model/BookingRooms.php";
include "library/form_validation.php";
$form_validation = new Form_Validation();
$connection = new Connection();
$konek = $connection->connect();
$booking_rooms_model = new BookingRoom_Model($konek);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chateau De Galera</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Hotel" name="keywords">
    <meta
        content="Accompanied with quality service from our friendly staff and cozy accommodations, Chateau de Galera offers you comfort while you get to experience, enjoy and take instagram-worthy snaps by the tourist frequented Lighthouse and aesthetic view of nature"
        name="description">

    <!-- Favicon -->
    <link href="views/user/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="views/user/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="views/user/css/style.css" rel="stylesheet">

    <!-- QR CODE GENERATOR -->
    <script type="text/javascript" src="views/assets/QR-JS/qrcode.js"></script>
</head>


<body>
    <div class="bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->

        <?php include "temp_header.php";?>

        <?php
            if($form_validation->submitted()){
                $br_auto_increment_id = NULL;
                $booking_id = $form_validation->xss_clean($_POST['txt_booking_id']);
                $br_room_id = $form_validation->xss_clean($_POST['txt_room_id']);
                $br_guest_fname = $form_validation->xss_clean($_POST['txt_client_fname']);
                $br_guest_lname = $form_validation->xss_clean($_POST['txt_client_lname']);
                $br_guest_contact_num = $form_validation->xss_clean($_POST['txt_client_phone_number']);
                $br_guest_email = $form_validation->xss_clean($_POST['txt_client_email']);
                $br_checkin_date = $form_validation->xss_clean($_POST['txt_checkin_date']);
                $br_checkout_date = $form_validation->xss_clean($_POST['txt_checkout_date']);
                $br_number_of_days_to_stay = $form_validation->xss_clean($_POST['txt_nights']);
                $br_downpayment = $form_validation->xss_clean($_POST['txt_downpayment']);
                $br_payment_method = "GCASH P2P";
                $br_payment_reference_number = $form_validation->xss_clean($_POST['txt_payment_reference_number']);
                $br_payment_status = "Pending";
                $br_booking_status = "Pending";
                $br_transaction_date = $date_html.' '.$server_time;
                $br_guest_id =  $br_guest_fname[0].$br_guest_lname[0].'-'.substr($br_guest_contact_num,7).'-'.$server_year;


                $form_validation->empty($br_guest_fname, "Enter your Firstname");
                $form_validation->empty($br_guest_lname, "Enter your Lastname");
                $form_validation->empty($br_guest_contact_num, "Enter your Contact Number");
                $form_validation->empty($br_guest_email, "Enter your Email");
                $form_validation->valid_email($br_guest_email, "Email is invalid");
                $form_validation->min_length($br_guest_contact_num,11, "Contact number should be an 11 digits number. <br><span class='lead'>Ex: 09171678331</span> ");
                $form_validation->max_length($br_guest_contact_num,11, "Contact number should be 11 digits only.");
                $form_validation->numeric($br_guest_contact_num, "Contact Number must be a number");
                $form_validation->does_exist($konek,"tbl_booking_rooms","booking_id",$booking_id,"Booked Already <a class=' w-100 btn btn-outline-secondary' href='Rooms'> Book Again </a>");
                

                if($form_validation->run()){
                    $booking_rooms_model->create_new_booking(   $br_auto_increment_id ,
                                                                $booking_id ,
                                                                $br_room_id ,
                                                                $br_guest_id ,
                                                                $br_guest_fname ,
                                                                $br_guest_lname ,
                                                                $br_guest_contact_num ,
                                                                $br_guest_email ,
                                                                $br_checkin_date ,
                                                                $br_checkout_date ,
                                                                $br_number_of_days_to_stay ,
                                                                $br_downpayment ,
                                                                $br_payment_method ,
                                                                $br_payment_reference_number ,
                                                                $br_payment_status ,
                                                                $br_booking_status ,
                                                                $br_transaction_date);
                    echo '
                    <div class="container-xxl py-3">
                    <div class="container">
                        <div class="row g-4">
                            <div class="col-12  shadow px-5 py-3">
                                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                                    <h2 class="mb-5"><span class="text-dark text-uppercase"></span> Booking Confirmation Voucher
                                    </h2>
                                </div>
                                <div class="table-bordered">
                                    <table class="table table-striped  ">
                                        <thead class="bg-white">
                                            <tr>
                                                <td>
                                                    <h1> Chateu De Galera </h1>
                                                    <span>Sinandigan, Puerto Galera, Oriental Mindoro, 5203</span>
                                                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+09171678331 <i
                                                            class="fa fa-envelope me-3"></i>arcinasv1975@gmail.com</p>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <!-- QR CODE to Verify Booking -->
                                                    <div class="img-fluid mx-auto" id="booking_qrcode" style="width:100px; height:100px; margin-top:15px;"></div>
                                                    <p class="text-center"> Scan Me </p>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr >
                                                <td colspan="1">
                                                    <div class=" px-3 py-3 ">
                                                        <div class="h5"> BOOKING ID </div>
                                                        <div class="h3"> '.$booking_id.' </div>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" px-3 py-3 text-center">
                                                        <div class="h5"> NIGHTS </div>
                                                        <div class="h3"> '.$br_number_of_days_to_stay.' </div>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" px-3 py-3 text-center">
                                                        <div class="h5"> Status </div>
                                                        <div class="h3"> '.$br_payment_status.' </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                                                    <div class=" px-3 py-3">
                                                        <div class="h5"> GUEST DETAILS </div>
                                                        <div class="h3">
                                                        <div class="h3"> '.$br_guest_fname. ' '.$br_guest_lname.'   </div>
                                                        </div>
                                                        <div class="h6"> '.$br_guest_contact_num.' </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" px-3 py-3 text-center">
                                                        <div class="h5"> CHECK IN </div>
                                                        <div class="h3"> '.$br_checkin_date.' </div>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" px-3 py-3 text-center">
                                                        <div class="h5"> CHECK OUT </div>
                                                        <div class="h3"> '.$br_checkout_date.' </div>
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
                <script type="text/javascript">
               
                var qrcode = new QRCode(document.getElementById("booking_qrcode"), {
                    width: 100,
                    height: 100
                });
                function make_booking_qr_code() {
                    qrcode.makeCode("'.$booking_id.'");
                }
                make_booking_qr_code();
                </script>
                    ';
                }else{
                    $form_errors = json_decode($form_validation->validation_errors());
                    foreach($form_errors as $frm_errs):
                        echo '
                            <div class="card w-50 shadow mx-auto px-3 py-3 mt-3" style="width: 18rem;">
                                <ul class="list-group list-group-flush h3">
                                <li class="list-group-item red-400 text-center">'.$frm_errs.'</li>
                                </ul>
                            </div>
                        ';
                    endforeach;
                    echo '
                    <div class=" w-50  mx-auto px-3 py-3 mt-3" style="width: 18rem;">
                        <a class="btn  w-100 btn-lg " href="javascript:history.back()"> GO BACK </a>
                    </div>
                    ';
                }
            }else{
                echo '
                 <h1 class="text-center"> NO BOOKING DATA</h1>
                ';
            }
        ?>

        <br> <br> <br><br> <br> <br>

        
        <?php include "temp_footer.php";?>
</body>

</html>