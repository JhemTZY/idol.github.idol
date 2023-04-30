<?php 

$timestamp = time() + (1 * 24 * 30 * 40);
$date_mdy = gmdate("F j, Y",$timestamp);
$date_html = gmdate("Y-m-j",$timestamp);
$server_year = gmdate("Y",$timestamp);
$server_month = gmdate("m",$timestamp);
$server_datetoday = gmdate("j",$timestamp);
$server_time = gmdate("h:i:s A", $timestamp);


echo '

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chateau De Galera</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Hotel" name="keywords">
    <meta content="Accompanied with quality service from our friendly staff and cozy accommodations, Chateau de Galera offers you comfort while you get to experience, enjoy and take instagram-worthy snaps by the tourist frequented Lighthouse and aesthetic view of nature" name="description">

    <!-- Favicon -->
    <link href="views/user/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Tahoma:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="views/user/css/bootstrap.min.css" rel="stylesheet">

    <!-- VUE JS -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="views/assets/axios.min.js"></script>

    <!-- Template Stylesheet -->
    <link href="views/user/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-info" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->
        
        <!-- Header Start -->
        <div class="container-fluid bg-dark text-white px-0">
            <div class="row gx-0">
                <div class="col-lg-1 bg-dark d-none d-lg-block py-3 px-3">
                    <a class="navbar-brand" href="Home">
                        <img src="views/user/img/profile" class="img-fluid img-thumbnail rounded-pill " height="100" width="100" alt="logo">
                    </a>
                </div>
                <div class="col-lg-9 mx-auto">
                    <div class="row gx-0 bg-dark text-white d-none d-lg-flex ">
                        <div class="col-lg-7  text-start">
                            <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                                <i class="fa fa-envelope text-info me-2"></i>
                                <p class="mb-0">arcinasv1975@gmail.com</p>
                            </div>
                            <div class="h-100 d-inline-flex align-items-center py-2">
                                <i class="fa fa-phone-alt text-info me-2"></i>
                                <p class="mb-0">09171678331</p>
                            </div>
                            <div class="h-100 d-inline-flex align-items-center py-2 px-3">
                                <i class="fa fa-calendar text-info me-2"></i>
                                <p class="mb-0" id="lbl_date">'.$date_mdy.'</p>
                            </div>
                            <div class="h-100 d-inline-flex align-items-center py-2 px-3">
                                <i class="fa fa-hourglass text-info me-2"></i>
                                <p class="mb-0" id="lbl_time">'.$server_time.'</p>
                            </div>
                        </div>
                        <div class="col-lg-5 px-5 text-end">
                            <div class="d-inline-flex align-items-center py-2">
                                <a class="me-3" href="https://www.facebook.com/Chateau.de.Galera/"><i class="text-white fab fa-facebook-f"></i></a>
                                <a class="me-3" href="#SocMed"><i class="text-white fab fa-twitter"></i></a>
                                <a class="me-3" href="#SocMed"><i class="text-white fab fa-linkedin-in"></i></a>
                                <a class="me-3" href="#SocMed"><i class="text-white fab fa-instagram"></i></a>
                                <a class="" href="#SocMed"><i class="text-white fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <!-- <a href="views/user/index.html" class="navbar-brand d-block d-lg-none">
                            <h1 class="m-0 text-primary text-uppercase">Hotelier</h1>
                        </a> -->
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0">
                                <a href="Home" class="nav-item nav-link active">Home</a>
                                <a href="About" class="nav-item nav-link ">About</a>
                               
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="Rooms" class="dropdown-item">Rooms</a>
                                        <a href="Menu" class="dropdown-item">Menu</a>
                                        <a href="Portal" class="dropdown-item">Portal</a>
                                    </div>
                                </div>
                                <a href="Contact" class="nav-item nav-link">Contact</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Header End -->

       

';

?>