<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Datawarehouse</b> | Version 1.5 | 
        {elapsed_time} ms
    </div>
    <strong>Copyright &copy; 2022 <a href="<?php echo base_url(); ?>"> MIS-System Development Team </a>.</strong> All rights reserved.
</footer>


<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js" type="text/javascript"></script> -->
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/choice.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/js2/jquery-3.4.1.min.js'); ?>"></script> -->
<script type="text/javascript" src="<?php echo base_url('assets/js2/bootstrap.bundle.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js2/select2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/computation/computation.js'); ?>"></script>

<!-- VUEJS AND AXIOS -->
<script type="text/javascript" src="<?php echo base_url('assets/vuejs/vue.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/axios/axios.min.js'); ?>"></script>


<!-- VUE_SELECT -->
<script type="text/javascript" src="<?php echo base_url('assets/vue_select/vue_select.js'); ?>"></script>
<!-- VUE_SELECT -->

<!-- CONTEXT MENU  -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets2/css/bootstrap-jquery-context-menu/js/initialize.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets2/css/bootstrap-jquery-context-menu/js/context.js" charset="utf-8"></script>


<!-- <script type="text/javascript" src="<?php echo base_url('assets/excel_JS.js'); ?>"></script> -->
<!-- Data Tables -->
<script type="text/javascript" src="<?php echo base_url('assets/datatables/DataTables.js'); ?>"></script>

<script src="<?php echo base_url(); ?>assets2/plugins/flot/jquery.flot.js"></script>


<!-- TABLE COLUMN AND HEADER FREEZE FOR BIG DATA -->
<script src="<?php echo base_url(); ?>assets2/js2/jquery-freeze-table-master/dist/js/freeze-table.min.js"></script>


<!-- /*----------------------------------------------------------------------------------------
        *** SWEET ALERT !
    ---------------------------------------------------------------------------------------- */ -->

<script type="text/javascript" src="<?php echo base_url('assets/sweetalert/sweetalert.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/sweetalert/saving.js'); ?>"></script>

<!-- /*----------------------------------------------------------------------------------------
      SWEET ALERT ! ***
  ---------------------------------------------------------------------------------------- */ -->



<!-- SELECT 2 -->
<script>
    $(document).ready(function() {
        $('.selecting').select2();
    });
</script>

<script type="text/javascript">
    var windowURL = window.location.href;
    pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
    var x = $('a[href="' + pageURL + '"]');
    x.addClass('active');
    x.parent().addClass('active');
    var y = $('a[href="' + windowURL + '"]');
    y.addClass('active');
    y.parent().addClass('active');
</script>


<script>
    function sweetalert_success(message) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 800 
        })
    }

    function sweetalert_failed(message) {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 800 
        })
    }
</script>


</body>

</html>
