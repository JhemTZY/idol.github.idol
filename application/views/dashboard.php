<div class="content-wrapper" id="dashboard">
  <section class="content-header">
    <h1 style="color: white;">
      <i class="fa fa-cube" aria-hidden="true"></i> Dashboard
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div @mouseenter="hoverSound" class="small-box bg-aqua" style="border-radius:1rem; padding:5px;">
          <div class="inner">
            <h3>
              <?php echo count($this->db->query("SELECT * FROM tbl_new_plan ")->result()); ?>
            </h3>
            <p>PSI Data</p>
          </div>
          <div class="icon">
            <i class="fa fa-database"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div @mouseenter="hoverSound" class="small-box bg-green" style="border-radius:1rem; padding:5px;">
          <div class="inner">
            <h3>
              24<sup style="font-size: 20px">%</sup>
            </h3>
            <p>Project</p>
          </div>
          <div class="icon">
            <i class="fa fa-bar-chart"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div @mouseenter="hoverSound" class="small-box bg-yellow" style="border-radius:1rem; padding:5px;">
          <div class="inner">
            <h3>
              <?= substr($selectedDPRNO, 4); ?>
            </h3>
            <p>Current Revision</p>
          </div>
          <div class="icon">
            <i class="fa fa-calendar"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div @mouseenter="hoverSound" class="small-box bg-primary" style="border-radius:1rem; padding:5px;">
          <div class="inner">
            <h3>
              <?php echo count($this->db->query("SELECT * FROM tbl_revision")->result()); ?>
            </h3>
            <p>Revisions Made</p>
          </div>
          <div class="icon">
            <i class="fa fa-book"></i>
          </div>
        </div>
      </div>
    </div>

        <div class="col-md-3">
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">REV-1-1-23</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- <a href="<?= base_url() ?>assets/dwh_report.pdf" class="btn btn-outline-primary btn-lg" target="_blank">DWH: STATUS REPORT</a> -->
              <a href="<?= base_url() ?>assets/Revision.xlsx" class="btn btn-outline-primary btn-lg" target="_blank">DWH: REV-1-1-23</a>
<hr>
              <a href=" http://10.216.2.202/Datawarehouse_approval/approved_by.php" class="btn btn-outline-primary btn-lg" target="_blank">Approve by Mr. Yamada San</a>
              <hr>
              <a href=" http://10.216.2.202/Datawarehouse_approval/prepared_by.php" class="btn btn-outline-primary btn-lg" target="_blank">Approve by Ms. Helen Mailom</a>
              <hr>
              <a href=" http://10.216.2.202/Datawarehouse_approval/noted_by.php" class="btn btn-outline-primary btn-lg" target="_blank">Approve by Mr. Veth Onde</a>

              <a href=" http://10.216.128.101/DWH_M/login" class="btn btn-outline-primary btn-lg" target="_blank">DWH_M</a>




            

              <hr>

                
           <?php
            if ($role_text == "System Administrator") {
            ?>
             
            <?php } ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

  
  </section>
</div> 


<script>
  // let chart = new Chart('sales_chart');
  var app = new Vue({
    el: '#dashboard',
    data: {

    },
    methods: {
      tts_welcome: function() {
        var audio = new Audio('<?= base_url() ?>assets/dist/audio/welcometodw.mp3');
        audio.play();
      },
      hoverSound: function() {
        var audio = new Audio('<?= base_url() ?>assets/dist/audio/hoversound1.mp3');
        audio.play();
      },
      create_chart: function() {
        sweetalert_success("Chart Created");
      },
    },
    created: function() {
      this.tts_welcome();
    }
  });
</script>

</body>

</html>