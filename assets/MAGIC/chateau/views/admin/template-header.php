<?php

echo '

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Hugo 0.88.1">
<title>Chateau De Galera - Admin</title>

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
    rel="stylesheet">

<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap core CSS -->
<link href="views/admin/assets/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="views/admin/dashboard.css" rel="stylesheet">

<!-- VUE JS -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="views/assets/axios.min.js"></script>

</head>
<body>
  
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">CHATEAU DE GALERA</a>
<button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
<!--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> -->
<div class="navbar-nav">
  <div class="nav-item text-nowrap">
    <a class="nav-link px-3" href="#">Sign out</a>
  </div>
</div>
</header>

<div class="container-fluid">
<div class="row ">
  <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
      <ul class="nav flex-column h4 ">
        <li class="nav-item ">
          <a class="nav-link active" aria-current="page" href="Admin-Dashboard">
            <span data-feather="home"></span>
            Dashboard
          </a>
        </li>
        <li class="nav-item "  ">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#mdl_booking">
            <span data-feather="file"></span>
            Booking
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="Admin-Dashboard-Ordering">
            <span data-feather="shopping-cart"></span>
            Ordering
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="Admin-Dashboard-Customers">
            <span data-feather="users"></span>
            Customers
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="Admin-Tools">
            <span data-feather="users"></span>
            Tools
          </a>
        </li>
        <!-- <li class="nav-item ">
          <a class="nav-link" href="#">
            <span data-feather="bar-chart-2"></span>
            Reports
          </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link" href="#">
            <span data-feather="layers"></span>
            Integrations
          </a>
        </li> -->
      </ul>

    
    </div>
  </nav>

';