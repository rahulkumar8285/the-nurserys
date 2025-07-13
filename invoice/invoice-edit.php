<?php


include('header.php');
include('functions.php');

$getID = $_GET['id'];

// Connect to the database
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

// output any connection error
if ($mysqli->connect_error) {
	die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
}

// the query
$query = "SELECT *
			FROM invoices
			WHERE id = '" . $mysqli->real_escape_string($getID) . "'";

$result = mysqli_query($mysqli, $query);
$data= mysqli_fetch_assoc($result);


// echo '<pre>';
// print_r($data);
// die;

/* close connection */
$mysqli->close();


?>

<h1>Edit Invoice</h1>
<hr>

<div id="response" class="alert alert-success" style="display:none;">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <div class="message"></div>
</div>

<form method="post" id="update_invoice">
    <input type="hidden" name="action" value="update_invoice">
    <input type="hidden" name="update_id" value="<?php echo $getID; ?>">

    <div class="row">

        <div class="col-xs-12">
            <div class="row">

                



                <div class="col-xs-4">
                    <div  style="margin-top: 21px;">
					<input type="date" class="form-control required" name="invoice_date" 

					<?php if(isset($data['invoice_date'])){ ?>
						value="<?php echo date('Y-m-d',strtotime($data['invoice_date']));?>" 
					<?php }?>

					
					/>	
						
                    </div>
                </div>


                <div class="col-xs-4">
                    <div  id="state_code_name" style="margin-top: 21px;">
                        <input type="text" class="form-control required" name="state_code_name"
                            placeholder="State Code Name"  value="<?php echo (isset($data['state_code_name']))? $data['state_code_name']:''; ?>" />
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
                                value="<?php echo (isset($data['customer_name']))? $data['customer_name']:''; ?>"
                                placeholder="Enter your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control required" id="customer_email" name="customer_email"
                                value="<?php echo (isset($data['customer_email']))? $data['customer_email']:''; ?>"
                                placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control required" id="customer_phone" name="customer_phone"
                                value="<?php echo (isset($data['customer_phone']))? $data['customer_phone']:''; ?>"
                                placeholder="Enter your phone number" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control required" name="customer_address_1"
                                value="<?php echo (isset($data['customer_address_1']))? $data['customer_address_1']:''; ?>"
                                id="customer_address_1" placeholder="Enter your address" required>
                        </div>
                        <div class="form-group">
                            <label for="gst">GST Number:</label>
                            <input type="text" class="form-control " id="custmore_gst" name="custmore_gst"
                                value="<?php echo (isset($data['custmore_gst']))? $data['custmore_gst']:''; ?>"
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
            <?php
					$resultArr = json_decode($data['itesData']);

					// print_r($resultArr);

					foreach($resultArr as $value){  ?>
            <tr>
                <td>
                    <div class="form-group form-group-sm  no-margin-bottom">
                        <a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove"
                                aria-hidden="true"></span></a>
                        <input type="text" class="form-control form-group-sm item-input invoice_product required"
                            name="invoice_product[]" value="<?php echo $value->invoice_product; ?>"
                            placeholder="Enter Product Name OR Description">
                        <p class="item-select"></p>
                    </div>
                </td>
                <td class="text-right">
                    <div class="form-group form-group-sm no-margin-bottom">
                        <input type="text" class="form-control invoice_product_qty calculate required"
                            name="invoice_product_qty[]" value="<?php echo $value->invoice_product_qty; ?>">
                    </div>
                </td>
                <td class="text-right">
                    <div class="input-group input-group-sm  no-margin-bottom">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="number" class="form-control calculate invoice_product_price required"
                            name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00"
                            value="<?php echo $value->invoice_product_price; ?>">
                    </div>
                </td>
                <!-- <td class="text-right">
								<div class="form-group form-group-sm  no-margin-bottom">
									<input type="text" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % OR value (ex: 10% or 10.50)">
								</div>
							</td> -->
                <td class="text-right">
                    <div class="form-group form-group-sm  no-margin-bottom">
                        <input type="text" class="form-control required" name="hncCode[]" placeholder="Enter HSN Code"
                            value="<?php echo $value->hncCode; ?>">
                    </div>
                </td>
                <td class="text-right">
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><?php echo CURRENCY ?></span>
                        <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]"
                            id="invoice_product_sub" value="<?php echo $value->invoice_product_sub; ?>"
                            aria-describedby="sizing-addon1" disabled>
                    </div>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
    <div id="invoice_totals" class="padding-right row text-right">
        <div class="col-xs-6">
            <div class="input-group form-group-sm textarea no-margin-bottom">
                <textarea class-"form-control" name="invoice_notes"
                    placeholder="Additional Notes..."><?php echo (isset($data['invoice_notes']))? $data['invoice_notes']:''; ?></textarea>
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
                        <input type="text" class="form-control " name="someting_text"  value="<?php echo (isset($data['someting_text']))? $data['someting_text']:''; ?>" placeholder="Text about">
                        <input type="text" class="form-control " name="someting_amount"  value="<?php echo (isset($data['someting_amount']))? $data['someting_amount']:''; ?>" placeholder="amount">
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
                        <input type="text" class="form-control"   name="labour_cost"  value="<?php echo (isset($data['labour_cost']))? $data['labour_cost']:''; ?>">
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
                        <input type="text" class="form-control " name="invoice_shipping"
                            value="<?php echo (isset($data['invoice_shipping']))? $data['invoice_shipping']:''; ?>">
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
                        <input type="text" class="form-control " name="invoice_discount"
                            value="<?php echo (isset($data['invoice_discount']))? $data['invoice_discount']:''; ?>">
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

                        <input type="text" class="form-control " name="cgst_persant" placeholder="%"
                            value="<?php echo (isset($data['cgst_persant']))? $data['cgst_persant']:''; ?>">

                        <input type="text" class="form-control " name="cgst_value" placeholder="amount"
                            value="<?php echo (isset($data['cgst_value']))? $data['cgst_value']:''; ?>">

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

                        <input type="text" class="form-control " name="sgst_persant" placeholder="%"
                            value="<?php echo (isset($data['sgst_persant']))? $data['sgst_persant']:''; ?>">

                        <input type="text" class="form-control " name="sgst_value" placeholder="amount"
                            value="<?php echo (isset($data['sgst_value']))? $data['sgst_value']:''; ?>">

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
                        <input type="text" class="form-control " name="igst_persant" placeholder="%"
                            value="<?php echo (isset($data['igst_persant']))? $data['igst_persant']:''; ?>">
                        <input type="text" class="form-control " name="igst_value" placeholder="amount"
                            value="<?php echo (isset($data['igst_value']))? $data['igst_value']:''; ?>">
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
                        <input type="text" class="form-control required" name="invoice_total"
                            value="<?php echo (isset($data['invoice_total']))? $data['invoice_total']:''; ?>">

                    </div>
                </div>
            </div>
        </div>



        <div class="col-xs-6 margin-top btn-group">
            <input type="submit" id="action_edit_invoice" class="btn btn-success" value="Update Invoice"
                data-loading-text="Updating...">
        </div>


    </div>
    <div class="row">

    </div>
</form>

<?php
	include('footer.php');
?>