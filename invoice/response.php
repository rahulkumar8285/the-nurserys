<?php



include_once('includes/config.php');

// show PHP errors
ini_set('display_errors', 1);

// output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$action = isset($_POST['action']) ? $_POST['action'] : "";

if ($action == 'email_invoice'){

	$fileId = $_POST['id'];
	$emailId = $_POST['email'];
	$invoice_type = $_POST['invoice_type'];
	$custom_email = $_POST['custom_email'];

	require_once('class.phpmailer.php');

	$mail = new PHPMailer(); // defaults to using php "mail()"

	$mail->AddReplyTo(EMAIL_FROM, EMAIL_NAME);
	$mail->SetFrom(EMAIL_FROM, EMAIL_NAME);
	$mail->AddAddress($emailId, "");

	$mail->Subject = EMAIL_SUBJECT;
	//$mail->AltBody = EMAIL_BODY; // optional, comment out and test
	if (empty($custom_email)){
		if($invoice_type == 'invoice'){
			$mail->MsgHTML(EMAIL_BODY_INVOICE);
		} else if($invoice_type == 'quote'){
			$mail->MsgHTML(EMAIL_BODY_QUOTE);
		} else if($invoice_type == 'receipt'){
			$mail->MsgHTML(EMAIL_BODY_RECEIPT);
		}
	} else {
		$mail->MsgHTML($custom_email);
	}

	$mail->AddAttachment("./invoices/".$fileId.".pdf"); // attachment

	if(!$mail->Send()) {
		 //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mail->ErrorInfo.'</pre>'
	    ));
	} else {
	   echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Invoice has been successfully send to the customer'
		));
	}

}
// download invoice csv sheet
if ($action == 'download_csv'){

	header("Content-type: text/csv"); 

	// output any connection error
	if ($mysqli->connect_error) {
		die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	}
 
    $file_name = 'invoice-export-'.date('d-m-Y').'.csv';   // file name
    $file_path = 'downloads/'.$file_name; // file path

	$file = fopen($file_path, "w"); // open a file in write mode
    chmod($file_path, 0777);    // set the file permission

    $query_table_columns_data = "SELECT * 
									FROM invoices i
									JOIN customers c
									ON c.invoice = i.invoice
									WHERE i.invoice = c.invoice
									ORDER BY i.invoice";

    if ($result_column_data = mysqli_query($mysqli, $query_table_columns_data)) {

    	// fetch table fields data
        while ($column_data = $result_column_data->fetch_row()) {

            $table_column_data = array();
            foreach($column_data as $data) {
                $table_column_data[] = $data;
            }

            // Format array as CSV and write to file pointer
            fputcsv($file, $table_column_data, ",", '"');
        }

	}

    //if saving success
    if ($result_column_data = mysqli_query($mysqli, $query_table_columns_data)) {
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'CSV has been generated and is available in the /downloads folder for future reference, you can download by <a href="downloads/'.$file_name.'">clicking here</a>.'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

 
    // close file pointer
    fclose($file);

    $mysqli->close();

}

// Create customer
if ($action == 'create_customer'){

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_address_2 = $_POST['customer_address_2']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_postcode = $_POST['customer_postcode']; // customer postcode
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_address_2_ship = $_POST['customer_address_2_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	$customer_postcode_ship = $_POST['customer_postcode_ship']; // customer postcode (shipping)

	$query = "INSERT INTO store_customers (
					name,
					email,
					address_1,
					address_2,
					town,
					county,
					postcode,
					phone,
					name_ship,
					address_1_ship,
					address_2_ship,
					town_ship,
					county_ship,
					postcode_ship
				) VALUES (
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?
				);
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'ssssssssssssss',
		$customer_name,$customer_email,$customer_address_1,$customer_address_2,$customer_town,$customer_county,$customer_postcode,
		$customer_phone,$customer_name_ship,$customer_address_1_ship,$customer_address_2_ship,$customer_town_ship,$customer_county_ship,$customer_postcode_ship);

	if($stmt->execute()){
		//if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message' => 'Customer has been created successfully!'
		));
	} else {
		// if unable to create invoice
		echo json_encode(array(
			'status' => 'Error',
			'message' => 'There has been an error, please try again.'
			// debug
			//'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
		));
	}

	//close database connection
	$mysqli->close();
}

// Create invoice
if ($action == 'create_invoice'){

	// invoice customer information
	// billing


	
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_phone = $_POST['customer_phone']; // customer phone number
	$state_code_name = $_POST['state_code_name']; // customer state_code_name
	$custmore_gst = $_POST['custmore_gst']; // customer custmore_gst
	$someting_text = $_POST['someting_text']; // customer someting_text
	$someting_amount = $_POST['someting_amount']; // customer someting_text
	$labour_cost = $_POST['labour_cost']; // customer someting_text





	

	// invoice details
	// $custom_email = $_POST['custom_email']; // invoice custom email body
	$invoice_date = date('Y-m-d',strtotime($_POST['invoice_date'])); // invoice date
	
	// echo $invoice_date;	
	// die;	
	$invoice_subtotal = $_POST['invoice_subtotal']; // invoice sub-total
	$invoice_shipping = $_POST['invoice_shipping']; // invoice shipping amount
	$invoice_discount = $_POST['invoice_discount']; // invoice discount

	$cgst_persant = $_POST['cgst_persant']; // cgst_persant
	$cgst_value = $_POST['cgst_value']; // cgst_persant

	$sgst_persant = $_POST['sgst_persant']; // sgst_persant
	$sgst_value = $_POST['sgst_value']; // sgst_value

	$igst_persant = $_POST['igst_persant']; // sgst_persant
	$igst_value = $_POST['igst_value']; // sgst_value

	$invoice_total = $_POST['invoice_total']; // invoice total
	$invoice_notes = $_POST['invoice_notes']; // Invoice notes
	$invoice_type = 'GST'; // Invoice type

	$invoice_product =  $_POST['invoice_product'];
	$invoice_product_qty = $_POST['invoice_product_qty'];
	$invoice_product_price = $_POST['invoice_product_price'];
	$hncCode = $_POST['hncCode'];
	$invoice_product_sub = $_POST['invoice_product_sub'];
	$itesmArr =array();	

		foreach($invoice_product as $key=>$val){
			$insideArr = array(
				'invoice_product'=> $invoice_product[$key],
				'invoice_product_qty'=> $invoice_product_qty[$key],
				'invoice_product_price'=> $invoice_product_price[$key],
				'hncCode'=> $hncCode[$key],
				'invoice_product_sub'=> $invoice_product_sub[$key],

			);
			array_push($itesmArr,$insideArr);
		}



		$itesData = json_encode($itesmArr);

        
        // get last invoice number
        
        $sql = "SELECT * FROM invoices ORDER BY id DESC LIMIT 1";

        $result = $mysqli->query($sql);
        
        $lastInvoiceNum = 0;
        
        if ($result->num_rows > 0) {
        // Extracting invoice_num and assigning it to $lastId
        while($row = $result->fetch_assoc()) {
            $lastId = $row['invoice_num'];
        }
            $lastInvoiceNum = $lastId;
        } 
    
        $lastInvoiceNum++;
        


	$query = "INSERT INTO `invoices`( `invoice_num`,`invoice_date`,`state_code_name`, `invoice_discount`, `invoice_notes`, `invoice_type`, `customer_name`, `customer_address_1`, `customer_email`, `customer_phone`,`custmore_gst`, `itesData`, `invoice_subtotal`, `invoice_shipping`,`someting_text`,`someting_amount`,`labour_cost`, `cgst_persant`, `cgst_value`, `sgst_persant`, `sgst_value`, `igst_persant`, `igst_value`, `invoice_total`) VALUES ('".$lastInvoiceNum."','".$invoice_date."','".$state_code_name."','".$invoice_discount."','".$invoice_notes."','".$invoice_type."','".$customer_name."','".$customer_address_1."','".$customer_email."','".$customer_phone."','".$custmore_gst."','".$itesData."','".$invoice_subtotal."','".$invoice_shipping."','".$someting_text."','".$someting_amount."','".$labour_cost."','".$cgst_persant."','".$cgst_value."','".$sgst_persant."','".$sgst_value."','".$igst_persant."','".$igst_value."','".$invoice_total."')";
	
	header('Content-Type: application/json');

	// echo $query;
	// die;
	// execute the query
	$stmt = $mysqli->prepare($query);
	// die;	
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	// $stmt->bind_param('sss',$product_name,$product_desc,$product_price);

	if($stmt->execute()){
	    //if saving success
		// die;	
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Invoice has been added successfully!'
		));
		
	}else {
		// if unable to create invoice
		echo json_encode(array(
			'status' => 'Error',
			'message' => 'There has been an error, please try again.'
			// debug
			//'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
		));
	}

	//close database connection
	$mysqli->close();

}

// Adding new product
if($action == 'delete_invoice') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM invoices WHERE invoice = ".$id.";";
	$query .= "DELETE FROM customers WHERE invoice = ".$id.";";
	$query .= "DELETE FROM invoice_items WHERE invoice = ".$id.";";

	unlink('invoices/'.$id.'.pdf');

	if($mysqli -> multi_query($query)) {
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Adding new product
if($action == 'update_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$getID = $_POST['id']; // id

	// invoice customer information
	// billing
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_address_2 = $_POST['customer_address_2']; // customer address
	$customer_town = $_POST['customer_town']; // customer town
	$customer_county = $_POST['customer_county']; // customer county
	$customer_postcode = $_POST['customer_postcode']; // customer postcode
	$customer_phone = $_POST['customer_phone']; // customer phone number
	
	//shipping
	$customer_name_ship = $_POST['customer_name_ship']; // customer name (shipping)
	$customer_address_1_ship = $_POST['customer_address_1_ship']; // customer address (shipping)
	$customer_address_2_ship = $_POST['customer_address_2_ship']; // customer address (shipping)
	$customer_town_ship = $_POST['customer_town_ship']; // customer town (shipping)
	$customer_county_ship = $_POST['customer_county_ship']; // customer county (shipping)
	$customer_postcode_ship = $_POST['customer_postcode_ship']; // customer postcode (shipping)

	// the query
	$query = "UPDATE store_customers SET
				name = ?,
				email = ?,
				address_1 = ?,
				address_2 = ?,
				town = ?,
				county = ?,
				postcode = ?,
				phone = ?,

				name_ship = ?,
				address_1_ship = ?,
				address_2_ship = ?,
				town_ship = ?,
				county_ship = ?,
				postcode_ship = ?

				WHERE id = ?

			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'sssssssssssssss',
		$customer_name,$customer_email,$customer_address_1,$customer_address_2,$customer_town,$customer_county,$customer_postcode,
		$customer_phone,$customer_name_ship,$customer_address_1_ship,$customer_address_2_ship,$customer_town_ship,$customer_county_ship,$customer_postcode_ship,$getID);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Update product
if($action == 'update_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// invoice product information
	$getID = $_POST['id']; // id
	$product_name = $_POST['product_name']; // product name
	$product_desc = $_POST['product_desc']; // product desc
	$product_price = $_POST['product_price']; // product price

	// the query
	$query = "UPDATE products SET
				product_name = ?,
				product_desc = ?,
				product_price = ?
			 WHERE product_id = ?
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'ssss',
		$product_name,$product_desc,$product_price,$getID
	);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}


// Update quotation
if($action == 'update_quotation') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}




	// invoice product information
	$getID = $_POST['id']; // id
	$quotation_title = $_POST['quotation_title'];
	$customer_name = $_POST['customer_name'];
	$customer_phone = $_POST['customer_phone'];
	$customer_email = $_POST['customer_email'];
	$customer_address = $_POST['customer_address'];

	$item_description_arr = $_POST['item_description'];
	$quantity_arr = $_POST['quantity'];
	$unit_price_arr = $_POST['unit_price'];
	$amount_arr = $_POST['amount'];

	$gstAmount = $_POST['gstAmount'];


	$add_something_elese = $_POST['add_something_elese'];
	$add_something_elese_amount = $_POST['add_something_elese_amount'];
	$labour_cost = $_POST['labour_cost'];
	$flight_charges = $_POST['flight_charges'];
	$total_amount = $_POST['total_amount'];


	// echo '<pre>';
	// print_r($_POST);
	// die;

		$itesmArr =array();	
		$itemData = '';

		foreach($item_description_arr as $key=>$val){
			$insideArr = array(
				'item_description'=> $item_description_arr[$key],
				'quantity'=> $quantity_arr[$key],
				'unit_price'=> $unit_price_arr[$key],
				'amount'=> $amount_arr[$key],
			);
			array_push($itesmArr,$insideArr);
		}

	$itemData = json_encode($itesmArr);
	$inclues = json_encode($_POST['inclues']);
	$terms_and_conditions = json_encode($_POST['terms_and_conditions']);

	// the query
	$query = "UPDATE quotation SET
					quotation_title =?,
					customer_name =?,
					customer_phone =?,
					customer_email =?,
					customer_address =?,
					itemsData =?,
					inclues =?,
					terms_and_conditions =?,	
					gstAmount =?,
					add_something_elese =?,
					add_something_elese_amount =?,
					labour_cost =?,
					flight_charges =?,
					total_amount =?
			 WHERE quotation_id = ?
			";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param(
		'sssssssssssssss',
		$quotation_title,$customer_name,$customer_phone,$customer_email,$customer_address,$itemData,$inclues,$terms_and_conditions,$gstAmount,$add_something_elese,$add_something_elese_amount,$labour_cost,$flight_charges,$total_amount,$getID
	);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}


// Adding new product
if($action == 'update_invoice') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["update_id"];

	
	
	$customer_name = $_POST['customer_name']; // customer name
	$customer_email = $_POST['customer_email']; // customer email
	$customer_address_1 = $_POST['customer_address_1']; // customer address
	$customer_phone = $_POST['customer_phone']; // customer phone number
	$state_code_name = $_POST['state_code_name']; // customer state_code_name
	$custmore_gst = $_POST['custmore_gst']; // customer custmore_gst
	$someting_text = $_POST['someting_text']; // customer someting_text
	$someting_amount = $_POST['someting_amount']; // customer someting_text
	$labour_cost = $_POST['labour_cost']; // customer someting_text

	// invoice details
	// $custom_email = $_POST['custom_email']; // invoice custom email body
	
	$invoice_date = date('Y-m-d',strtotime($_POST['invoice_date'])); 
	$invoice_subtotal = $_POST['invoice_subtotal']; // invoice sub-total
	$invoice_shipping = $_POST['invoice_shipping']; // invoice shipping amount
	$invoice_discount = $_POST['invoice_discount']; // invoice discount

	$cgst_persant = $_POST['cgst_persant']; // cgst_persant
	$cgst_value = $_POST['cgst_value']; // cgst_persant

	$sgst_persant = $_POST['sgst_persant']; // sgst_persant
	$sgst_value = $_POST['sgst_value']; // sgst_value

	$igst_persant = $_POST['igst_persant']; // sgst_persant
	$igst_value = $_POST['igst_value']; // sgst_value

	$invoice_total = $_POST['invoice_total']; // invoice total
	$invoice_notes = $_POST['invoice_notes']; // Invoice notes
	$invoice_type = 'GST'; // Invoice type

	$invoice_product =  $_POST['invoice_product'];
	$invoice_product_qty = $_POST['invoice_product_qty'];
	$invoice_product_price = $_POST['invoice_product_price'];
	$hncCode = $_POST['hncCode'];
	$invoice_product_sub = $_POST['invoice_product_sub'];
	$itesmArr =array();	

		foreach($invoice_product as $key=>$val){
			$insideArr = array(
				'invoice_product'=> $invoice_product[$key],
				'invoice_product_qty'=> $invoice_product_qty[$key],
				'invoice_product_price'=> $invoice_product_price[$key],
				'hncCode'=> $hncCode[$key],
				'invoice_product_sub'=> $invoice_product_sub[$key],

			);
			array_push($itesmArr,$insideArr);
		}

		$invoice_num = 10;
		$invoice_num = 10;

		$itesData = json_encode($itesmArr);



	

	$query = "UPDATE `invoices` SET `invoice_date`='".$invoice_date."',`invoice_discount`='".$invoice_discount."',`invoice_notes`='".$invoice_notes."',`invoice_type`='".$invoice_type."',`customer_name`='".$customer_name."',`customer_address_1`='".$customer_address_1."',`customer_email`='".$customer_email."',`customer_phone`='".$customer_phone."',`itesData`='".$itesData."',`invoice_subtotal`='".$invoice_subtotal."',`invoice_shipping`='".$invoice_shipping."',`cgst_persant`='".$cgst_persant."',`cgst_value`='".$cgst_value."',`sgst_persant`='".$sgst_persant."',`sgst_value`='".$sgst_value."',`igst_persant`='".$igst_persant."',`igst_value`='".$igst_value."',`invoice_total`='".$invoice_total."',`state_code_name`='".$state_code_name."',`custmore_gst`='".$custmore_gst."',`someting_text`='".$someting_text."',`someting_amount`='".$someting_amount."',`labour_cost`='".$labour_cost."'  WHERE id=".$id;


	header('Content-Type: application/json');

	if($mysqli -> multi_query($query)) {
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Invoice has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Adding new product
if($action == 'delete_product') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM products WHERE product_id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Login to system
if($action == 'login') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	session_start();

    extract($_POST);

    $username = mysqli_real_escape_string($mysqli,$_POST['username']);
    $pass_encrypt = md5(mysqli_real_escape_string($mysqli,$_POST['password']));

    $query = "SELECT * FROM `users` WHERE username='$username' AND `password` = '$pass_encrypt'";

    $results = mysqli_query($mysqli,$query) or die (mysqli_error());
    $count = mysqli_num_rows($results);

    if($count!="") {
		$row = $results->fetch_assoc();

		$_SESSION['login_username'] = $row['username'];

		// processing remember me option and setting cookie with long expiry date
		if (isset($_POST['remember'])) {	
			session_set_cookie_params('604800'); //one week (value in seconds)
			session_regenerate_id(true);
		}  
		
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Login was a success! Transfering you to the system now, hold tight!'
		));
    } else {
    	echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'Login incorrect, does not exist or simply a problem! Try again!'
	    ));
    }
}

// Adding new product
if($action == 'add_product') {

	$product_name = $_POST['product_name'];
	$product_desc = $_POST['product_desc'];
	$product_price = $_POST['product_price'];

	//our insert query query
	$query  = "INSERT INTO products
				(
					product_name,
					product_desc,
					product_price
				)
				VALUES (
					?, 
                	?,
                	?
                );
              ";

    header('Content-Type: application/json');

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('sss',$product_name,$product_desc,$product_price);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been added successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
}

// Adding new user
if($action == 'add_user') {

	$user_name = $_POST['name'];
	$user_username = $_POST['username'];
	$user_email = $_POST['email'];
	$user_phone = $_POST['phone'];
	$user_password = $_POST['password'];

	//our insert query query
	$query  = "INSERT INTO users
				(
					name,
					username,
					email,
					phone,
					password
				)
				VALUES (
					?,
					?, 
                	?,
                	?,
                	?
                );
              ";

    header('Content-Type: application/json');

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	$user_password = md5($user_password);
	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('sssss',$user_name,$user_username,$user_email,$user_phone,$user_password);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been added successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
}

// Update product
if($action == 'update_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// user information
	$getID = $_POST['id']; // id
	$name = $_POST['name']; // name
	$username = $_POST['username']; // username
	$email = $_POST['email']; // email
	$phone = $_POST['phone']; // phone
	$password = $_POST['password']; // password

	if($password == ''){
		// the query
		$query = "UPDATE users SET
					name = ?,
					username = ?,
					email = ?,
					phone = ?
				 WHERE id = ?
				";
	} else {
		// the query
		$query = "UPDATE users SET
					name = ?,
					username = ?,
					email = ?,
					phone = ?,
					password =?
				 WHERE id = ?
				";
	}

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	if($password == ''){
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'sssss',
			$name,$username,$email,$phone,$getID
		);
	} else {
		$password = md5($password);
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param(
			'ssssss',
			$name,$username,$email,$phone,$password,$getID
		);
	}

	//execute the query
	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been updated successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
	
}

// Delete User
if($action == 'delete_user') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM users WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'User has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}

// Delete User
if($action == 'delete_customer') {

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	$id = $_POST["delete"];

	// the query
	$query = "DELETE FROM store_customers WHERE id = ?";

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('s',$id);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Customer has been deleted successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	// close connection 
	$mysqli->close();

}


// Adding Quotation
if($action == 'add_quotation') {

	$quotation_title = $_POST['quotation_title'];
	$customer_name = $_POST['customer_name'];
	$customer_phone = $_POST['customer_phone'];
	$customer_email = $_POST['customer_email'];
	$customer_address = $_POST['customer_address'];
	$gstAmount = $_POST['gstAmount'];


	$item_description_arr = $_POST['item_description'];
	$quantity_arr = $_POST['quantity'];
	$unit_price_arr = $_POST['unit_price'];
	$amount_arr = $_POST['amount'];


	$add_something_elese = $_POST['add_something_elese'];
	$add_something_elese_amount = $_POST['add_something_elese_amount'];
	$labour_cost = $_POST['labour_cost'];
	$flight_charges = $_POST['flight_charges'];
	$total_amount = $_POST['total_amount'];


		$itesmArr =array();	
		$itemData = '';

		foreach($item_description_arr as $key=>$val){
			$insideArr = array(
				'item_description'=> $item_description_arr[$key],
				'quantity'=> $quantity_arr[$key],
				'unit_price'=> $unit_price_arr[$key],
				'amount'=> $amount_arr[$key],
			);
			array_push($itesmArr,$insideArr);
		}

	$itemData = json_encode($itesmArr);
	$inclues = json_encode($_POST['inclues']);
	$terms_and_conditions = json_encode($_POST['terms_and_conditions']);


	//our insert query query
	$query  = "INSERT INTO quotation
				(
					quotation_title,
					customer_name,
					customer_phone,
					customer_email,
					customer_address,
					itemsData,
					inclues,
					terms_and_conditions,
					gstAmount,
					add_something_elese,
					add_something_elese_amount,
					labour_cost,
					flight_charges,
					total_amount
					)
				VALUES (
					?, 
                	?,
                	?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
					?
                );
              ";

    header('Content-Type: application/json');

	/* Prepare statement */
	$stmt = $mysqli->prepare($query);
	if($stmt === false) {
	  trigger_error('Wrong SQL: ' . $query . ' Error: ' . $mysqli->error, E_USER_ERROR);
	}

	/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
	$stmt->bind_param('ssssssssssssss',$quotation_title,$customer_name,$customer_phone,$customer_email,$customer_address,$itemData,$inclues,$terms_and_conditions,$gstAmount,$add_something_elese,$add_something_elese_amount,$labour_cost,$flight_charges,$total_amount);

	if($stmt->execute()){
	    //if saving success
		echo json_encode(array(
			'status' => 'Success',
			'message'=> 'Product has been added successfully!'
		));

	} else {
	    //if unable to create new record
	    echo json_encode(array(
	    	'status' => 'Error',
	    	//'message'=> 'There has been an error, please try again.'
	    	'message' => 'There has been an error, please try again.<pre>'.$mysqli->error.'</pre><pre>'.$query.'</pre>'
	    ));
	}

	//close database connection
	$mysqli->close();
}





?>