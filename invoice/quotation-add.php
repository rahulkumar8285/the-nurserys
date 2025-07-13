<?php
include('header.php');

?>

<h2>Add Quotation</h2>
<hr>

<div id="response" class="alert alert-success" style="display:none;">
	<a href="#" class="close" data-dismiss="alert">&times;</a>
	<div class="message"></div>
</div>
						
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Customer Information</h4>
			</div>
			<div class="panel-body form-group form-group-sm">
				<form method="post" id="add_quotation">
					<input type="hidden" name="action" value="add_quotation">

					<div class="row">
                        <div class="col-xs-12" style="margin-bottom:10px;">
                        <input type="text" class="form-control required" name="quotation_title" placeholder="Enter Quotation Title" required>
                        </div>
						<div class="col-xs-4">
							<input type="text" class="form-control required" name="customer_name" placeholder="Enter Customer Name" required>
						</div>
						<div class="col-xs-4">
							<input type="text" class="form-control required" name="customer_phone" placeholder="Enter Customer Phone" required>
						</div>
						<div class="col-xs-4">
							<input type="text" class="form-control required" name="customer_email" placeholder="Enter Customer Email" required>
						</div>
                        <div class="col-xs-12" style="margin-top:10px;">
                            <lable for="customer_address" ><b>Customer address</b></lable>
                            <textarea class="form-control" name="customer_address"></textarea>
						</div>
					</div>
                    <hr>

                    <div class="row">
                            <div class="col-xs-5">
                              Item/Description
                            </div>
                            <div class="col-xs-2">
                            Quantity
                            </div>
                            <div class="col-xs-2">
                            Unit Price
                            </div>
                            <div class="col-xs-2">
                            Amount
                            </div>
                            <div class="col-xs-1 text-right">
                            <a href="javascript:void(0)" class="btn btn-success add_button">+</a>
                            </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-5">
                           <input type="text" class="form-control required" name="item_description[]" placeholder="Enter Item/Description" required>
                        </div>
                        <div class="col-xs-2">
                            <input type="text" class="form-control required" name="quantity[]" placeholder="Enter Quantity" required>
                        </div>

                        <div class="col-xs-2">
                            <input type="text" class="form-control required" name="unit_price[]" placeholder="Enter Unit Price" required>
                        </div>

                        <div class="col-xs-2">
                            <input type="text" class="form-control required" name="amount[]" placeholder="Enter Unit Amount" required>
                        </div>
                    </div>

                    <div class=" field_wrapper">

                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-xs-12" style="margin-top:10px;">
                            <lable for="gstAmount" ><b>if Have GST Than Enter GST Amount</b></lable>
                            <textarea class="form-control" name="gstAmount"></textarea>
                        </div>
                    </div>
                        <hr>

                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 10px;font-size: 21px;">
                           <lable for="customer_address" ><b>Including</b></lable>
                        </div>
                        <div class="col-xs-11">
                            <input type="text" class="form-control required" name="inclues[]" placeholder="Enter Inclues" required> 
                        </div>
                        <div class="col-xs-1">
                            <a href="javascript:void(0);" class="btn btn-success inclue_add_button"> + </a>
                        </div>

                    </div>

                    <div class="include_wapper">

                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12" style="margin-bottom: 10px;font-size: 21px;">
                           <lable for="customer_address" ><b>Terms and Conditions</b></lable>
                        </div>
                        <div class="col-xs-11">
                            <input type="text" class="form-control required" name="terms_and_conditions[]" placeholder="Enter Terms and Conditions" required> 
                        </div>
                        <div class="col-xs-1">
                            <a href="javascript:void(0);" class="btn btn-success terms_and_conditions_add_button"> + </a>
                        </div>

                    </div>

                    <div class="terms_and_conditions_wapper">

                    </div>

                    <hr>

                    <hr>


                    <div class="">
                        
                        <div class="row">
                            <div class="col-xs-12" style="margin-bottom: 10px;font-size: 21px;">
                                <lable for="customer_address" ><b>Add shipping labour cost flight charges and other things cost</b></lable>
                            </div>


                            
                            
                        </div>
                                    
                        <div class="">
                               <div class="row">
                                   <div class="col-md-6"  style="margin-top: 20px;">
                                        <input type="text"  placeholder="Add Something Elese" name="add_something_elese"  class="form-control" />
                                   </div>

                                   <div class="col-md-6" style="margin-top: 20px;">
                                        <input type="text"  placeholder="Add Something Elese Amount" name="add_something_elese_amount"   class="form-control" />
                                   </div>

                                   <div class="col-md-6" style="margin-top: 20px;">
                                        <input type="text"  placeholder="Labour Cost" name="labour_cost"   class="form-control" />
                                   </div>

                                   <div class="col-md-6" style="margin-top: 20px;">
                                        <input type="text"  placeholder="Flight Charges" name="flight_charges"  class="form-control" />
                                   </div>

                                   <div class="col-md-6" style="margin-top: 20px;">
                                        <input type="text"  placeholder="Final Total" name="total_amount"    class="form-control required" />
                                   </div>
                               </div>
                        </div>


                    </div>



                
					<div class="row">
						<div class="col-xs-12 margin-top btn-group">
							<input type="submit" id="action_add_quotation" class="btn btn-success " value="Submit" data-loading-text="Adding...">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<div>


<script>
$(document).ready(function(){
    var maxField = 50; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div class="row" style="margin-top:10px;"><hr><div class="col-xs-5"><input type="text" class="form-control required" name="item_description[]" placeholder="Enter Item/Description" required></div><div class="col-xs-2"><input type="text" class="form-control required" name="quantity[]" placeholder="Enter Quantity" required></div><div class="col-xs-2"><input type="text" class="form-control required" name="unit_price[]" placeholder="Enter Unit Price" required></div><div class="col-xs-2"><input type="text" class="form-control required" name="amount[]" placeholder="Enter Unit Amount" required></div><div class="col-xs-1"><a href="javascript:void(0);" class="btn btn-danger remove_button">-</a></div></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }else{
            alert('A maximum of '+maxField+' fields are allowed to be added. ');
        }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest('div.row').remove(); //Remove field html
        x--; //Decrease field counter
    });
});
</script>

<script>
$(document).ready(function(){
    var maxField = 50; //Input fields increment limitation
    var addButton = $('.inclue_add_button'); //Add button selector
    var wrapper = $('.include_wapper'); //Input field wrapper
    var fieldHTML = '<div class="row"><hr><div class="col-xs-11"><input type="text" class="form-control required" name="inclues[]" placeholder="Enter Inclues" required></div><div class="col-xs-1"><a href="javascript:void(0)" class="btn btn-danger remove_button_inclue"> - </a></div></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }else{
            alert('A maximum of '+maxField+' fields are allowed to be added. ');
        }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button_inclue', function(e){
        e.preventDefault();
        $(this).closest('div.row').remove(); //Remove field html
        x--; //Decrease field counter
    });
});
</script>

<script>
$(document).ready(function(){
    var maxField = 50; //Input fields increment limitation
    var addButton = $('.terms_and_conditions_add_button'); //Add button selector
    var wrapper = $('.terms_and_conditions_wapper'); //Input field wrapper
    var fieldHTML = '<div class="row"><hr><div class="col-xs-11"><input type="text" class="form-control required" name="terms_and_conditions[]" placeholder="Enter Terms and Conditions" required></div><div class="col-xs-1"><a href="javascript:void(0)" class="btn btn-danger remove_button_terms_and_conditions_wapper"> - </a></div></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }else{
            alert('A maximum of '+maxField+' fields are allowed to be added. ');
        }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button_terms_and_conditions_wapper', function(e){
        e.preventDefault();
        $(this).closest('div.row').remove(); //Remove field html
        x--; //Decrease field counter
    });
});
</script>



<?php
	include('footer.php');
?>