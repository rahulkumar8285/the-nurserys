<?php

include('header.php');
include('functions.php');

?>

<h2>Create New <span class="invoice_type">Invoice</span> </h2>
<!-- <hr> -->

<div id="response" class="alert alert-success" style="display:none;">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <div class="message"></div>
</div>

<form method="post" id="create_invoice">
    <input type="hidden" name="action" value="create_invoice">

    <div class="row">

        <div class="col-xs-12">
            <div class="row">
			<div class="col-xs-4">
                    <div  style="margin-top: 21px;">
                        <input type="date" class="form-control required" name="invoice_date"/>	
                      
                    </div>
                </div>


                <div class="col-xs-4">
                    <div  id="state_code_name" style="margin-top: 21px;">
                        <input type="text" class="form-control required" name="state_code_name"
                            placeholder="State Code Name" />
                    </div>
                </div>


            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="container-filud">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Customer Details</h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control required" name="customer_name" id="customer_name"
                                placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control required" id="customer_email" name="customer_email"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control required" id="customer_phone" name="customer_phone"
                                placeholder="Enter your phone number" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control required" name="customer_address_1"
                                id="customer_address_1" placeholder="Enter your address" required>
                        </div>
                        <div class="form-group">
                            <label for="gst">GST Number:</label>
                            <input type="text" class="form-control " id="custmore_gst" name="custmore_gst"
                                placeholder="Enter your GST number">
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- / end client details section -->
    <table class="table table-bordered table-hover table-striped" id="invoice_table">
        <thead>
            <tr>
                <th width="500">
                    <h4><a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus"
                                aria-hidden="true"></span></a> Item/Description</h4>
                </th>
                <th>
                    <h4>Qty</h4>
                </th>
                <th>
                    <h4>Price</h4>
                </th>
                <th width="300">
                    <h4>HSN Code</h4>
                </th>
                <th>
                    <h4>Sub Total</h4>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="form-group form-group-sm  no-margin-bottom">
                        <a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove"
                                aria-hidden="true"></span></a>
                        <input type="text"  style="width: 350px;" class="form-control form-group-sm item-input invoice_product required"
                            name="invoice_product[]" placeholder="Enter Product Name OR Description">
                        <p class="item-select"></p>
                    </div>
                </td>
                <td class="text-right">
                    <div class="form-group form-group-sm no-margin-bottom">
                        <input type="text" style="width: 100px;"  class="form-control invoice_product_qty calculate required"
                            name="invoice_product_qty[]" value="1">
                    </div>
                </td>
                <td class="text-right">
                    <div class="input-group input-group-sm  no-margin-bottom">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="number" style="width: 100px;" class="form-control calculate invoice_product_price required"
                            name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
                    </div>
                </td>
                <!-- <td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="text" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % OR value (ex: 10% or 10.50)">
							</div>
						</td> -->
                <td class="text-right">
                    <div class="form-group form-group-sm  no-margin-bottom">
                        <input type="text" style="width: 150px;"  class="form-control required" name="hncCode[]" placeholder="Enter HSN Code">
                    </div>
                </td>
                <td class="text-right">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]"
                            id="invoice_product_sub" style="width: 150px;"  value="0.00" >
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="invoice_totals" class="padding-right row text-right">
        <div class="col-xs-6">
            <div class="input-group form-group-sm textarea no-margin-bottom">
                <textarea class-"form-control" name="invoice_notes" placeholder="Additional Notes..."></textarea>
            </div>


        </div>


        <div class="col-xs-6 no-padding-right">
            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>Sub Total:</strong>
                </div>
                <div class="col-xs-5">
                    <?php echo CURRENCY ?><span class="invoice-sub-total">0.00</span>
                    <input type="hidden" name="invoice_subtotal" id="invoice_subtotal">
                </div>
            </div>


            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>Add Something Elese:</strong>
                </div>

                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control " name="someting_text" placeholder="Text about">
                        <input type="text" class="form-control " name="someting_amount" placeholder="amount">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>Labour Cost:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="labour_cost">
                    </div>
                </div>
            </div>




            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong class="shipping">Shipping:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="invoice_shipping">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>Discount:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="invoice_discount">
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>CGST:</strong>
                </div>

                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="cgst_persant" placeholder="%">
                        <input type="text" class="form-control " name="cgst_value" placeholder="amount">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>SGST:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="sgst_persant" placeholder="%">
                        <input type="text" class="form-control " name="sgst_value" placeholder="amount">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>IGST:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control " name="igst_persant" placeholder="%">
                        <input type="text" class="form-control " name="igst_value" placeholder="amount">
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-xs-4 col-xs-offset-3">
                    <strong>Total:</strong>
                </div>
                <div class="col-xs-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control required" name="invoice_total">
                    </div>
                </div>
            </div>
        </div>



        <div class="col-xs-6 margin-top btn-group">
            <input type="submit" id="action_create_invoice" class="btn btn-success" value="Create Invoice"
                data-loading-text="Creating...">
        </div>


    </div>
    <div class="row">

    </div>
</form>

<?php
	include('footer.php');
?>