<?php

class Ordering_Model{
    
  public $konek;
  public function __construct($konek){
    $this->konek = $konek;
  }

  public function get_menu_based_on_category($menu_item_category){
    $cmd = "SELECT * FROM tbl_menu WHERE menu_item_category=?  AND menu_item_status='Available' ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($menu_item_category))){
    return $datas = $stmt->fetchAll();
    }else {
    echo "Error";
    }
    
  }


  public function add_to_cart($guest_id,$menu_item_id,$quantity,$date_added,$cart_status){
    $cmd = "INSERT INTO tbl_cart VALUES (NULL,?,?,?,?,?)";
    $stmt = $this->konek->prepare($cmd);
    $stmt->execute(array($guest_id,$menu_item_id,$quantity,$date_added,$cart_status));
    if($stmt->rowCount()>0){
      echo "Successfully Added ";
    }else {
      echo "Error! Something Happened";
    }
  }

  public function isItemInGuestCart_existing($cart_Guest_id, $menu_item_id){
    $cmd = "SELECT * FROM tbl_cart WHERE cart_guest_id=? AND cart_menu_item_id=? LIMIT 1";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($cart_Guest_id, $menu_item_id))){
      if(!$stmt->rowCount() == 0){
        return TRUE;
      }
      else{
        return FALSE;
      }
    }
  }

  public function update_cartQuantity_increment($cart_Guest_id, $menu_item_id){
    $cmd = "UPDATE tbl_cart SET cart_quantity=cart_quantity+1, cart_status='ACTIVE' WHERE cart_guest_id=? AND cart_menu_item_id=?";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($cart_Guest_id, $menu_item_id))){
      echo "Updated Cart - ACTIVE";
    }else {
      echo "Error";
    }
  }

  // public function cart_is_Abandoned($cart_Guest_id){
  //   $cmd = "UPDATE tbl_cart SET cart_status='Abandoned' WHERE cart_guest_id=?";
  //   $stmt = $this->konek->prepare($cmd);
  //   if($stmt->execute(array($cart_Guest_id))){
  //     echo "Cart Abandoned";
  //   }else {
  //     echo "Error";
  //   }
  // }

  
  public function get_active_cart_items($guest_id){
    $cmd = "SELECT cart_guest_id, cart_menu_item_id,cart_quantity,cart_status,menu_item_id,menu_item_name,menu_item_price, 
            (cart_quantity * menu_item_price) AS subtotal
            FROM `tbl_cart` 
            INNER JOIN tbl_menu 
            ON cart_menu_item_id = tbl_menu.menu_item_id 
            WHERE cart_guest_id=? 
            AND cart_status='ACTIVE' ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($guest_id))){
    return $datas = $stmt->fetchAll();
    }else {
    echo "Error";
    }
  }

  public function get_placed_cart_items($guest_id){
    $cmd = "SELECT cart_guest_id, cart_menu_item_id,cart_quantity,cart_status,menu_item_id,menu_item_name,menu_item_price, 
            (cart_quantity * menu_item_price) AS subtotal
            FROM `tbl_cart` 
            INNER JOIN tbl_menu 
            ON cart_menu_item_id = tbl_menu.menu_item_id 
            WHERE cart_guest_id=? 
            AND cart_status='PLACED' ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($guest_id))){
    return $datas = $stmt->fetchAll();
    }else {
    echo "Error";
    }
  }

  public function remove_from_cart($cart_Guest_id, $food_id){
    $cmd = "UPDATE tbl_cart SET cart_status='REMOVED', cart_quantity='0' WHERE cart_guest_id=? AND cart_menu_item_id=?";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($cart_Guest_id, $food_id))){
      echo "Cart Menu Item Removed";
    }else {
      echo "Error";
    }
  }

  function place_order($cart_guest_id){
    $cmd = "UPDATE tbl_cart SET cart_status='PLACED' WHERE cart_guest_id=? AND cart_status='ACTIVE' ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($cart_guest_id))){
      echo "ORDER PLACED";
    }else {
      echo "Error";
    }
  }

  function get_total_amount_of_active_orders($guest_id){
    $cmd = "SELECT SUM(cart_quantity * menu_item_price) AS total_amount_of_orders
          FROM `tbl_cart` 
          INNER JOIN tbl_menu 
          ON cart_menu_item_id = tbl_menu.menu_item_id 
          WHERE cart_guest_id=? 
          AND cart_status='ACTIVE' 
          ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($guest_id))){
    return $datas = $stmt->fetch();
    }else {
    echo "Error";
    }
  }

  function get_total_amount_of_placed_orders($guest_id){
    $cmd = "SELECT SUM(cart_quantity * menu_item_price) AS total_amount_of_orders
          FROM `tbl_cart` 
          INNER JOIN tbl_menu 
          ON cart_menu_item_id = tbl_menu.menu_item_id 
          WHERE cart_guest_id=? 
          AND cart_status='PLACED' 
          ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($guest_id))){
    return $datas = $stmt->fetch();
    }else {
    echo "Error";
    }
  }

  function confirm_order(
   $or_id,
   $or_g_id,
   $or_g_fname,
   $or_g_lname,
   $or_g_contactNum,
   $or_g_email,
   $or_MOO,
   $or_date,
   $or_time,
   $or_paymentMethod,
   $or_total_amountPay,
   $or_payment_stat,
   $or_payment_refNum,
   $or_transDateTime,
   $or_status){
    $cmd = "INSERT INTO tbl_orders VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $this->konek->prepare($cmd);
    $stmt->execute(array(
     $or_id,
     $or_g_id,
     $or_g_fname,
     $or_g_lname,
     $or_g_contactNum,
     $or_g_email,
     $or_MOO,
     $or_date,
     $or_time,
     $or_paymentMethod,
     $or_total_amountPay,
     $or_payment_stat,
     $or_payment_refNum,
     $or_transDateTime,
     $or_status));
    if($stmt->rowCount()>0){
      echo json_encode(array("Order is successful."));
    }else {
      echo "Error! Something Happened";
    }
  }
  


}