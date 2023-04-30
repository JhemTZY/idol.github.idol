
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Task Management
        <small>Add / Edit Task</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <div class="box box-primary">
                   
                    <div class="box-header">
                    <h3 class="box-title">Datawarehouse Production Plan</h3>
                    <div class="box-tools">
                    <form action="<?php echo base_url(); ?>index.php/revision/updates" method="POST" >
                        
                        <div class="input-group">
                       
                        </div>
                        </form>
                </div>
            </div><!-- /.box-header -->
         
            <div class="box-body table-responsive no-padding">
         
              <table class="table table-bordered table-hover " id="mytable">
     

              <thead>
       <tr>
           <th rowspan = "2">Customer</th>
           <th rowspan = "2">Model</th>
           <th rowspan = "2">Price</th>
         
           
        
          
       <?php foreach ($monthList as $monRows) { ?>
               <th colspan = "2"><?php echo $monRows; ?>

           
           <?php } ?>
       </tr>
       <?php foreach ($monthList as $monRows) { ?>
        
               <th style="text-align: center;">PRODUCTION<p>QTY</p></th>
               <th style="text-align: center;">SALES<p>QTY</p></th>
         


               
               
               
           <?php } ?>
           
   </thead>
   <tbody>
           <?php if (!empty($records)) {
               foreach ($records as $record) { ?>



          <!-- <tr> 
        <td><input class ="cus"  name = "cus[]" value ="<?php echo $record->Customer; ?>"/></td> 
        <td><input class ="Model"   name = "Model[]" value ="<?php echo $record->Model; ?>"/></td> 
        <td><input class ="price Model"name = "Model[]" value ="<?php echo $record->price1; ?>"/></td> 
        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty1; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty1; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount1; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount1; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end1; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount1; ?>"/></td> 
       
        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty2; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty2; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount2; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount2; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end2; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount2; ?>"/></td> 

        
        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty3; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty3; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount3; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount3; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end3; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount3; ?>"/></td> 

        
        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty4; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty4; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount4; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount4; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end4; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount4; ?>"/></td> 

        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty5; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty5; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount5; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount5; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end5; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount5; ?>"/></td> 
                    
        
        <td><input class ="<?php echo $prd_qty; ?> Model"name ="prod_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_qty6; ?>"/></td> 
        <td><input class ="<?php echo $sales_qty; ?> Model"name ="sales_qty_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_qty6; ?>"/></td> 
        <td><input class ="prod_amount" name ="prod_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->prod_amount6; ?>"/></td> 
        <td><input class ="sales_amount" name ="sales_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->sales_amount6; ?>"/></td> 
        <td><input class ="invty_end" name="invty_end_<?php echo $monRows; ?>[]"   value ="<?php echo $record->invty_end6; ?>"/></td> 
        <td><input class ="invty_end_amount" name= "invty_end_amount_<?php echo $monRows; ?>[]" value ="<?php echo $record->invty_end_amount6; ?>"/></td> 

    </tr> -->
                   
                    <?php }
           } ?>








         </tr>
           </tbody>
    </table>

</div>




            <div class="col-md-4">
                <?php
                $this->load->helper('form');
                $error = $this->session->flashdata('error');
                if ($error) { ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php }
                ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php }
                ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>