


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    <?php echo $pageTitle; ?>
  </title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.4 -->
  <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- FontAwesome 4.3.0 -->
  <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons 2.0.0 -->
  <link href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Context Menu BOOTSRAP -->
  <link href="<?php echo base_url(); ?>assets2/css/bootstrap-jquery-context-menu/css/context.bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets2/css/bootstrap-jquery-context-menu/css/context.standalone.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/tags/tags.css" rel="stylesheet" type="text/css" />

  <link href="<?php echo base_url(); ?>assets/button/button.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/tooltips.css'); ?>">

  <!-- JQUERY -->
  <script type="text/javascript" src="<?php echo base_url('assets/js2/jquery-3.4.1.min.js'); ?>"></script>

  <!-- BOOTSTRAP CONTEXT MENU -->
  <script src="<?php echo base_url(); ?>assets/BS_ContextMenu/dist/BootstrapMenu.min.js"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-select.css'); ?>">

  <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/dist/img/dwhicon/dwhicon_final.png">

  <!-- Select2 -->
  <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet" type="text/css" />

  <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
  <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

  <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/choices.min.css" rel="stylesheet" type="text/css" />

  <!-- CHARTJS -->
  <script src="<?= base_url() ?>assets2/plugins/chart.js/Chart.min.js"></script>

  <!--DATA TABLEs -->
  <link rel="stylesheet" href="<?php echo base_url('assets/datatables/DataTables.css'); ?>">


  <!-- *** VUEJS AND AXIOS -->
  <script type="text/javascript" src="<?php echo base_url('assets/vuejs/vue.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/axios/axios.min.js'); ?>"></script>
  <!-- VUEJS AND AXIOS *** -->


    <!-- VUE-SELECT -->
      <link rel="stylesheet" href="<?php echo base_url('assets/vue_select/vue_select.css'); ?>">
  <!-- VUE-SELECT-->

  <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    .error {
      color: gold;
      font-weight: normal;
    }

    /* Hide Scrollbar */
  /*  ::-webkit-scrollbar {
      width: 0.1px;
    }*/
  </style>
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
  </script>

  <!-- TABLE COLUMN AND HEADER FREEZE FOR BIG DATA -->
  <script>
    function freezetable(columnNum) {
      $(function() {
        $('.freeze-table').freezeTable({
          'columnNum': columnNum,
          'scroll' : scroll,
        });
      });
    }

    function ObjectLength(object) {
      var length = 0;
      for (var key in object) {
        if (object.hasOwnProperty(key)) {
          ++length;
        }
      }
      return length;
    };
  </script>

</head>

<body class="hold-transition skin-blue sidebar-mini ">
  <!-- oncontextmenu="return false;" -->
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="<?php echo base_url(); ?>" class="logo">
        <img src="<?php echo base_url(); ?>assets/dist/img/dwhicon/dwhicon11.png" alt="Datawarehouse" class="img-fluid" width="150">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>DATA WAREHOUSE</b>A T</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>DATA</b>WAREHOUSE</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <i class="fa fa-globe fa-spin"></i>
              </a>
              <ul class="dropdown-menu" style="border-radius: 60px;">
                <li class="header"> <i class="fa fa-clock-o" style="color:black"></i> Last Login :
                  <?= empty($last_login) ? "First Time Login" : $last_login; ?>
                </li>
              </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="<?php echo base_url(); ?>assets/dist/img/avatar2.png" class="user-image" alt="User Image" /> -->
                <div class="user-image text-center" style="background-color:#f39222; color:white; width:30px; height:30px; padding:5px;">
                  <b style="font-size:25px; ">
                    <?php echo substr($name, 0, 1); ?>
                  </b>
                </div>
                
                <span class="hidden-xs">
                  <?php echo $name; ?>
                </span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <!-- <div class="img-circle" style="background-color:#f39c12; color:white; width:30%; height:50%; margin:auto;">
                    <h1 class="text-center" style="font-size:70px;">
                      <?php echo substr($name, 0, 1); ?>
                    </h1>
                  </div> -->

                    
                  </p>

                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="<?php echo base_url(); ?>index.php/profile" class="btn btn-info btn-flat" style="color: white; border-radius:10px;"><i class="fa fa-user-circle"></i> Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="<?php echo base_url(); ?>index.php/logout" class="btn btn-dark btn-flat" style="color: white; border-radius:10px;"><i class="fa fa-sign-out"></i> Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header text-center info-box-text"><?= $role_text ?></li>
          <li>
            <a href="<?php echo base_url(); ?>index.php/dashboard">
              <i class="fa fa-cube"></i> <span>Dashboard</span></i>
            </a>
          </li>

          <li class="treeview">
            <?php
            if ($role_text == "BU HEAD" || $role_text == "NCFL PLAN (PSI)" || $role_text == "Production Amount" || $role_text == "Sales Amount" || $role_text == "Ending Inventory Comparison" || $role_text == "System Administrator") {
            ?>
              <a href="#">
                <i class="fa ion-ios-list"></i> <span>NCFL PLAN</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right "></i>
                </span>
              </a>
            <?php } ?>

            <ul class="treeview-menu">

              <?php
              if ($role_text == "NCFL PLAN (PSI)" || $role_text == "System Administrator" ) {
              ?>
                <li class=""><a href="<?php echo base_url(); ?>index.php/PSI/psi_edit"> <i class="fa fa-file"></i>PSI</a></li>
                <li class=""><a href="<?php echo base_url(); ?>index.php/Prod_Sales_Amount/Sales">
                    <i class="fa fa-file"></i>Sales Amount</a></li>
              <?php } ?>

               <?php
              if ($role_text == "BU HEAD") {
              ?>
                <li class=""><a href="<?php echo base_url(); ?>index.php/Psi_BU/psi_edit"> <i class="fa fa-file"></i>PSI</a></li>
              <?php } ?>

              <?php
              if ($role_text == "Production Amount" || $role_text == "System Administrator" || $role_text == "Ending Inventory Comparison") {
              ?>
                <li class=""><a href="<?php echo base_url(); ?>index.php/Prod_Sales_Amount/Production_amount">
                    <i class="fa fa-file"></i>Production Amount</a></li>
              <?php } ?>


              <?php
              if ($role_text == "NCFL PLAN (PSI)" || $role_text == "System Administrator") {
              ?>
                <li class="treeview">
                  <a href="#">
                    <i class="ion-ios-settings-strong"></i> <span> PSI Maintenance</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right "></i>
                    </span>
                  </a>

                  <ul class="treeview-menu">
                    <li>
                      <a href="<?php echo base_url(); ?>index.php/User_Maintenance/home">
                        <i class="fa fa-thin fa-gear"></i>
                        <span> Tools</span>
                      </a>
                    </li>
                  </ul>
                </li>
              <?php
              }
              ?>
              <?php
              if ($role_text == "Ending Inventory Comparison" || $role_text == "System Administrator") {
              ?>
                <li><a href="<?php echo base_url(); ?>index.php/Prod_Sales_Amount/Prod_Sales_report">
                    <i class="fa fa-file-excel-o"></i>
                    NCFL Plan Report</a></li>
              <?php } ?>

              <!-- <li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i> Level One
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                  <li class="treeview">
                    <a href="#"><i class="fa fa-circle-o"></i> Level Two
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                      <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    </ul>
                  </li>
                </ul>
              </li> -->
              <!-- <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li> -->
            </ul>
          </li>


          <?php
          if ($role_text == "CFO" || $role_text == "System Administrator" || $role_text == "Ending Inventory Comparison") {
          ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i></i> <span>CFO</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right "></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class=""><a href="<?php echo base_url(); ?>index.php/CFO/cfo_prod_comparison">
                    <i class="fa fa-product-hunt"></i>
                    Production Comparison</a></li>
                <li class=""><a href="<?php echo base_url(); ?>index.php/CFO/cfo_sales_comparison">
                    <i class="fa fa-dollar"></i>
                    Sales Comparison</a></li>
                <li class=""><a href="<?php echo base_url(); ?>index.php/task/End_invty_comparison">
                    <i class="fa fa-file-excel-o"></i>
                    End Invty Comparison</a></li>
              </ul>
            </li>
          <?php } ?>


          <!-- MATERIAL COST -->
<!--           <?php
          if ($role_text == "System Administrator") {
          ?>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-thumb-tack"></i></i> <span>Material Cost</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right "></i>
                </span>
              </a>
              <ul class="treeview-menu">

                <?php
                if ($role_text == "System Administrator") {
                ?>
                  <li class=""><a href="<?php echo base_url(); ?>index.php/Material_cost/materialCost_Report">

                      Sample 1</a></li>
                <?php } ?>


                <?php
                if ($role_text == "System Administrator") {
                ?>
                  <li class=""><a href="<?php echo base_url(); ?>index.php/Material_cost/mc_block_view">

                      Sample 2</a></li>
                <?php } ?>


              </ul>
            </li>
          <?php } ?> -->






          <?php
          if ($role_text == "System Administrator") {
          ?>
            <li class="treeview">
              <a href="#">
                <i class="ion-locked"></i> <span> Admin Maintenance</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right "></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li>
                  <a href="<?php echo base_url(); ?>index.php/userListing">
                    <i class="ion-ios-people"></i>
                    <span>Users</span>
                  </a>
                </li>
                <li>
                  <a href="<?php echo base_url(); ?>index.php/roles/roleListing">
                    <i class="fa fa-briefcase " aria-hidden="true"></i>
                    <span>Roles</span>
                  </a>
                </li>
              </ul>
            </li>
          <?php
          }
          ?>







        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>