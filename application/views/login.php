<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>DW: LOGIN</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/dist/img/dwhicon/dwhicon_final.png">


  <style>
    #digital-clock {
      width: 40%;
      margin: auto;
      font-family: 'Open Sans Condensed', sans-serif;
      font-size: 64px;
      text-align: center;
      position: absolute;
      top: .40in;
      left: 700px;
      color: #fff;
      background: rgba(0%, 0%, 0%, 0.6);
      border-radius: 25px;
    }
  </style>


</head>


<body class="login-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="">
          <div id="digital-clock"> </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="login-box">
          <div class="login-box-body" style="border-radius:25px;">
            <h1 class="login-box-msg"> 
              <!-- <hr> DATAWAREHOUSE -->
              <img src="<?php echo base_url(); ?>assets/dist/img/dwhicon/dwhlogo.png" alt="Datawarehouse" class="img-fluid" width="80">

            </h1>
            <?php $this->load->helper('form'); ?>
            <div class="row">
              <div class="col-md-12">
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
              </div>
            </div>
            <?php
            $this->load->helper('form');
            $error = $this->session->flashdata('error');
            if ($error) {
            ?>
              <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error; ?>
              </div>
            <?php }
            $success = $this->session->flashdata('success');
            if ($success) {
            ?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $success; ?>
              </div>
            <?php } ?>
            <form action="<?php echo base_url(); ?>index.php/loginMe" method="post">
              <div class="form-group has-feedback">
                <input type="text" class="form-control1" placeholder="ID NUMBER" name="email" required style="text-align: center;" />
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control1" placeholder="PASSWORD" name="password" required style="text-align: center;" />
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <input type="submit" class="btn btn-primary btn-block btn-flat btn-lg" style="border-radius:2rem;" value="LOGIN" />
                </div>
             
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script>
    function Time() {
      // Creating object of the Date class
      var date = new Date();
      // Get current hour
      var hour = date.getHours();
      // Get current minute
      var minute = date.getMinutes();
      // Get current second
      var second = date.getSeconds();
      // Variable to store AM / PM
      var period = "";
      // Assigning AM / PM according to the current hour
      if (hour >= 12) {
        period = "PM";
      } else {
        period = "AM";
      }
      // Converting the hour in 12-hour format
      if (hour == 0) {
        hour = 12;
      } else {
        if (hour > 12) {
          hour = hour - 12;
        }
      }
      // Updating hour, minute, and second
      // if they are less than 10
      hour = update(hour);
      minute = update(minute);
      second = update(second);
      // Adding time elements to the div
      document.getElementById("digital-clock").innerText = hour + " : " + minute + " : " + second + " " + period;
      // Set Timer to 1 sec (1000 ms)
      setTimeout(Time, 1000);
    }
    // Function to update time elements if they are less than 10
    // Append 0 before time elements if they are less than 10
    function update(t) {
      if (t < 10) {
        return "0" + t;
      } else {
        return t;
      }
    }
    Time();
  </script>
</body>

</html>