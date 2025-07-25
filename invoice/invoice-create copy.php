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
							<h2 class="">Select Type:</h2>
						</div>
						<div class="col-xs-4">
							<select name="invoice_type" id="invoice_type" class="form-control">
								<option value="with_gst" selected>with Gst</option>
								<option value="without_gst">without Gst</option>
							</select>
						</div>
						<div class="col-xs-4">
							<div class="input-group date" id="invoice_date" style="margin-top: 21px;">
				                <input type="text" class="form-control required" name="invoice_date" placeholder="Invoice Date" data-date-format="<?php echo DATE_FORMAT ?>" />
				                <span class="input-group-addon">
				                    <span class="glyphicon glyphicon-calendar"></span>
				                </span>
				            </div>
						</div>	
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="float-left">Customer Information</h4>
							<!-- <a href="#" class="float-right select-customer"><b>OR</b> Select Existing Customer</a> -->
							<div class="clear"></div>
						</div>
						<div class="panel-body form-group form-group-sm">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<input type="text" class="form-control margin-bottom copy-input required" name="customer_name" id="customer_name" placeholder="Enter Name" tabindex="1">
									</div>
									<div class="form-group">
										<input type="text" class="form-control margin-bottom copy-input required" name="customer_address_1" id="customer_address_1" placeholder="Address 1" tabindex="3">	
									</div>
								</div>
								<div class="col-xs-6">
									<div class="input-group float-right margin-bottom">
										<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
										<input type="email" class="form-control copy-input required" name="customer_email" id="customer_email" placeholder="E-mail Address" aria-describedby="sizing-addon1" tabindex="2">
									</div>
								    <div class="form-group no-margin-bottom">
								    	<input type="text" class="form-control required" name="customer_phone" id="customer_phone" placeholder="Phone Number" tabindex="8">
									</div>
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
							<h4><a href="#" class="btn btn-success btn-xs add-row"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a> Item/Description</h4>
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
								<a href="#" class="btn btn-danger btn-xs delete-row"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
								<input type="text" class="form-control form-group-sm item-input invoice_product required" name="invoice_product[]" placeholder="Enter Product Name OR Description">
								<p class="item-select"></p>
							</div>
						</td>
						<td class="text-right">
							<div class="form-group form-group-sm no-margin-bottom">
								<input type="number" class="form-control invoice_product_qty calculate required" name="invoice_product_qty[]" value="1">
							</div>
						</td>
						<td class="text-right">
							<div class="input-group input-group-sm  no-margin-bottom">
								<span class="input-group-addon"><?php echo CURRENCY ?></span>
								<input type="number" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
							</div>
						</td>
						<!-- <td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="text" class="form-control calculate" name="invoice_product_discount[]" placeholder="Enter % OR value (ex: 10% or 10.50)">
							</div>
						</td> -->
						<td class="text-right">
							<div class="form-group form-group-sm  no-margin-bottom">
								<input type="text" class="form-control required" name="hncCode[]" placeholder="Enter HSN Code">
							</div>
						</td>
						<td class="text-right">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><?php echo CURRENCY ?></span>
								<input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" disabled>
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
							<strong>Discount:</strong>
						</div>
						<div class="col-xs-5">
							<div class="input-group input-group-sm">
								<span class="input-group-addon"><?php echo CURRENCY ?></span>
								<input type="text" class="form-control " name="invoice_discount" >
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
								<input type="text" class="form-control " name="invoice_shipping" >
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-4 col-xs-offset-3">
							<strong >CGST:</strong>
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
							<strong >SGST:</strong>
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
							<strong >IGST:</strong>
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
								<input type="text" class="form-control required" name="invoice_total" >
							</div>
						</div>
					</div>
				</div>

		

					<div class="col-xs-6 margin-top btn-group">
						<input type="submit" id="action_create_invoice" class="btn btn-success" value="Create Invoice" data-loading-text="Creating...">
					</div>
			

			</div>
			<div class="row">
				
			</div>
		</form>

<?php
	include('footer.php');
?>