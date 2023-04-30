<?php

class Route {
  /*
  two parameters.
  route : route address
  file : location of the file to show if route address matched
  */
  public function add($route, $file){
      //replacing first and last forward slashes
      //$_REQUEST['uri'] will be empty if req uri is /
      $reqUri='';
      if(!empty($_SERVER['REQUEST_URI'])){
          $route = preg_replace("/(^\/)|(\/$)/","",$route);
          $reqUri =  preg_replace("/(^\/)|(\/$)/","",$_SERVER['REQUEST_URI']);

      }else{
          $reqUri = "/";
      }
      if($reqUri == $route ){
          //on match include the file. 
          require $file;
          //exit because route address matched.
          exit();
      }
  }
  public function notFound($file){
    include($file);
    exit();
  }
}

//Route instance

// USER ROUTES
$route = new Route();
$route->add("chateau/", "views/user/index.php");
$route->add("chateau/Home", "views/user/index.php");
$route->add("chateau/About", "views/user/about.php");
$route->add("chateau/Rooms", "views/user/room.php");
$route->add("chateau/Contact", "views/user/contact.php");
$route->add("chateau/Menu", "views/user/menu.php");
$route->add("chateau/Booking-Room", "views/user/booking_room.php");

// PORTAL ROUTES
$route->add("chateau/Portal", "views/login.php");

// ADMIN ROUTES Admin-Dashboard-Booking-Rooms
$route->add("chateau/Admin", "views/admin/admin_dashboard.php");
$route->add("chateau/Admin-Dashboard", "views/admin/admin_dashboard.php");
$route->add("chateau/Admin-Dashboard-Ordering", "views/admin/admin_dashboard_ordering.php");
$route->add("chateau/Admin-Dashboard-Booking-Rooms", "views/admin/admin_dashboard_booking_rooms.php");
$route->add("chateau/Admin-Dashboard-Booking-Transportation", "views/admin/admin_dashboard_booking_transportation.php");
$route->add("chateau/Admin-Dashboard-Customers", "views/admin/admin_dashboard_customers.php");
$route->add("chateau/Admin-Tools", "views/admin/tools.php");


$route->notFound("404.php");

?>
