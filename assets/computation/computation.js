$(document).ready(function (){
  var myRoww = 0;
     
     $(".price").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","price" + myRoww);
     $(this).attr("data-id", myRoww);
  
 
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".prod_qty").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","prod_qty" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.prod_qty', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  prod_qty = $(this).val();
  trial = parseFloat(price)*parseFloat(prod_qty);
  $('#prod_amount'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".prod_amount").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","prod_amount" + myRoww);
    
 
});
});










$(document).ready(function (){
  var myRoww = 0;
     
     $(".sales_qty").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","sales_qty" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.sales_qty', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  sale_qty = $(this).val();
  trial = parseFloat(price)*parseFloat(sale_qty);
  $('#sales_amount'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".sales_amount").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","sales_amount" + myRoww);
    
 
});
});












$(document).ready(function (){
  var myRoww = 0;
     
     $(".invty_end").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","invty_end" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.sales_qty', function(){
  var sample =  $(this).attr("data-id");
  prod_qty = $('#prod_qty'+ sample).val();
  sales_qty = $(this).val();
  trial = parseFloat(prod_qty)-parseFloat(sales_qty);
  $('#invty_end'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});




$(document).ready(function (){
  var myRoww = 0;
     
     $(".invty_end_amount").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","invty_end_amount" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.invty_end', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  invty_end = $(this).val();
  trial = parseFloat(price)*parseFloat(invty_end);
  $('#invty_end_amount'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});





$(document).ready(function (){
  var myRoww = 0;
     
     $(".price").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","price" + myRoww);
     $(this).attr("data-id", myRoww);
  
 
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".prod_qty").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","prod_qty" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.prod_qty', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  prod_qty = $(this).val();
  trial = parseFloat(price)*parseFloat(prod_qty);
  $('#prod_amount'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".prod_amount").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","prod_amount" + myRoww);
    
 
});
});










$(document).ready(function (){
  var myRoww = 0;
     
     $(".sales_qty").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","sales_qty" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.sales_qty', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  sale_qty = $(this).val();
  trial = parseFloat(price)*parseFloat(sale_qty);
  $('#sales_amount'+ sample).val(trial);
console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});

$(document).ready(function (){
  var myRoww = 0;
     
     $(".sales_amount").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","sales_amount" + myRoww);
    
 
});
});







$(document).ready(function (){
  var myRoww = 0;
     
     $(".invty_end").each(function(){
     
         myRoww = myRoww +1;
         
     $(this).attr("id","invty_end" + myRoww);
     $(this).attr("data-id", myRoww);
    

$(document).on('input','.sales_qty', function(){
  var sample =  $(this).attr("data-id");
  price = $('#price'+ sample).val();
  prod_qty = $('#prod_qty'+ sample).val();
  invty_end = $('#invty_end'+ sample).val();
  sales_qty = $(this).val();
  trials = parseFloat(price)*parseFloat(invty_end);
  trial = parseFloat(prod_qty)-parseFloat(sales_qty);
  $('#invty_end_amount'+ sample).val(trials);
  $('#invty_end'+ sample).val(trial);

  invty_end = $('#invty_end1 ,#invty_end7, #invty_end13, #invty_end19').val();
  prod_qty = $('#prod_qty2 , #prod_qty8 , #prod_qty14 , #prod_qty20').val();
  sales = $('#sales_qty2, #sales_qty8 ,#sales_qty14 ,#sales_qty20').val();
  samples = parseFloat(invty_end)+parseFloat(prod_qty)-parseFloat(sales_qty);





  $('#invty_end2 , #invty_end8 ,#invty_end14 ,#invty_end20').val(samples);


  // invty_end3 = $('#invty_end3 ,#invty_end9, #invty_end15, #invty_end21').val();
  // prod_qty3 = $('#prod_qty4 , #prod_qty10, #prod_qty16 , #prod_qty22').val();
  // sales3 = $('#sales_qty4, #sales_qty10 ,#sales_qty16 ,#sales_qty22').val();
  // samples3 = parseFloat(invty_end3)+parseFloat(prod_qty3)-parseFloat(sales_qty3);
  // $('#invty_end4 , #invty_end10 ,#invty_end16 ,#invty_end22').val(samples3);


 
 


 

console.log(trial);
console.log(prod_qty);
console.log(price);
});
});
});
