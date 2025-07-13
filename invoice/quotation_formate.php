<?php
include_once("includes/config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include 'dbconnect.php';
use Dompdf\Dompdf; 
use Dompdf\Options; 

$html ='';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
    $html = createQuotationPDF($_GET['quotation_id']);

}else if($_GET['invoise_id']&& !empty($_GET['invoise_id'])){
    $html = createInvoicePDF($_GET['invoise_id']);
}

    require_once 'dompdf/autoload.inc.php';
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    // print_r($html);
    // die;
    $dompdf->loadHtml($html); 
     $dompdf->render(); 
    $dompdf->stream("Quotation.pdf", array("Attachment" => false));


function createQuotationPDF($id){
    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
    $query = "SELECT * FROM quotation where quotation_id =".$id;
	$results = $mysqli->query($query);
    $data = $results->fetch_assoc();

    $itemsData =  json_decode($data['itemsData']);
    $inclues =  json_decode($data['inclues']);
    $terms_and_conditions =  json_decode($data['terms_and_conditions']);


    // echo '<pre>';
    // print_r($inclues);
    // die;

 

    $html ='<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>Quotation</title><style>table tr td{border-collapse:collapse}</style></head><body style="margin:0;padding:0 0"><table align="center" width="550" border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #000;font-family:Arial,Helvetica,sans-serif;line-height:18px;border-collapse:collapse;font-size:13px;color:#000;padding:5px"><tr style="background:#fff"><td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px"><strong style="color:#000;font-size:14px"><img src="https://www.thenurserys.com/img/the-nurserys-logo.png" style="height:75px"></strong><strong style="color:#000;font-size:16px;float:right;margin-top:20px">Quotation: #'.$data['quotation_id'].'<br>Date: '.date('d F Y',strtotime($data['create_date'])).'</strong></td></tr><tr><td style="border-collapse:collapse"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial,Helvetica,border:none;border-collapse:collapse;font-size:11px;color:#000"><tr><td style="border-collapse:collapse;padding:5px;font-size:12px"><strong style="color:#000;font-size:16px">The Nurserys</strong><br><b>Office Add:</b> Z-33 Deepak Vihar Uttam Nagar<br> New Delhi 110059<br><b>Plants Add: </b>Palam Vihar Rd, near Durga <br>Mata Mandir Bijwasan,New Delhi 110061<br><b>Email: </b><a href="mailto:info@thenurserys.com">info@thenurserys.com</a> &nbsp; &nbsp;<b>Phone: </b><a href="tel:7827734874">7827734874</a></td><td style="border-collapse:collapse;padding:5px;font-size:12px"><strong style="color:#000;font-size:16px">'.ucwords($data['customer_name']).'</strong><br><b>Add: </b>'.$data['customer_address'].'<br><b>Email: </b>'.$data['customer_email'].' &nbsp; &nbsp;<b>Phone: </b>'.$data['customer_phone'].'</td></tr></table></td></tr><tr><td style="border:solid 1px #000;border-collapse:collapse;padding:2px">'.$data['quotation_title'].'</td></tr><tr><td style="border-collapse:collapse"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial,Helvetica,border:none;border-collapse:collapse;font-size:11px;color:#000"><tr><td width="47%" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px"><strong>Item/Description</strong></td><td width="10%" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px"><div align="center"><strong>Quantity</strong></div></td><td width="22%" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px"><div align="center"><strong>Price</strong></div></td><td width="21%" bgcolor="#f1f1f1" style="border-bottom:solid 1px #000;border-collapse:collapse;padding:2px"><div align="right"><strong>Amount</strong></div></td></tr>';
    $total =0;
    foreach($itemsData as $key=>$val){
        // $total +=$val->amount;
    $html .= '<tr><td style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">'.$val->item_description.'</td><td style="border:solid 1px #000;border-collapse:collapse;padding:2px"><div align="center">'.$val->quantity.'</div></td><td style="border:solid 1px #000;border-collapse:collapse;padding:2px"><div align="center">'.$val->unit_price.'</div></td><td style="border-bottom:solid 1px #000;border-collapse:collapse;padding:2px"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$val->amount.'</strong></div></td></tr>';
    }

    if(isset($data['gstAmount']) && !empty($data['gstAmount'])){
    $html .='<tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px"><div align="right"><strong>Sub Total</strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$total.'</strong></div></td></tr>';
    
      

}


    // $html .='<tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px;border:1px solid; "><div align="right"><strong>Sub Total </strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px;border:1px solid;"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> 241000</strong></div></td></tr>';
  


    if(isset($data['labour_cost']) && !empty($data['labour_cost'])){    
      $html .=' <tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-top:solid 1px #000;border-collapse:collapse;padding:2px"><div align="right"><strong>Labour Cost </strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px;border-top: solid 1px;border-bottom: solid 1px;"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$data['labour_cost'].'</strong></div></td></tr>';
    }




    if(isset($data['flight_charges']) && !empty($data['flight_charges'])){   
    $html .='<tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px;border:1px solid; "><div align="right"><strong>Flight Charges </strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px;border:1px solid;"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$data['flight_charges'].'</strong></div></td></tr>';
    }


    if((isset($data['add_something_elese_amount']) && !empty($data['add_something_elese_amount'])) &&  (isset($data['add_something_elese']) && !empty($data['add_something_elese']))     ){   
    $html .='<tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px;border:1px solid;"><div align="right"><strong>'.$data['add_something_elese'].'</strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px;border:1px solid;"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$data['add_something_elese_amount'].'</strong></div></td></tr>';
    }
  
    
    
   $html .='<tr><td bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td><td colspan="2" bgcolor="#f1f1f1" style="border-right:solid 1px #000;border-collapse:collapse;padding:2px"><div align="right"><strong>Total Amount</strong></div></td><td bgcolor="#f1f1f1" style="border-collapse:collapse;padding:2px"><div align="right"><strong><img src="https://pamoist.com/assets/img/rupee.png" style="height:10px"> '.$data['total_amount'].'</strong></div></td></tr></table></td></tr><tr><td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px"><strong style="font-size:16px">Including</strong><br>';

    $i=1;    
    foreach($inclues as $inc){
    $html .=$i.'. '.$inc.'<br>';
    $i++;
    }
    
    $html .='</td></tr><tr><td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px"><strong style="font-size:16px">Term and Conditions</strong><br>';
    $j=1; 
    foreach($terms_and_conditions as $terms){
        $html .=$j.'. '.$terms.'<br>';
        $j++;
    }
    $html .='</td></tr><tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px">
    <b>Deals in:</b> All types of plants, trees, soil, shrubs hedges, and pot materials, etc.
    </td>
    </tr>
    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px">
    <b>Services we offer:</b> Landscape garden design & development, garden maintenance, hydroponic gardening, plants for rent, artificial and natural grass, vertical garden and gifting plants.
    </td>
    </tr><tr><td style="border:solid 1px #000;border-collapse:collapse;padding:2px 2px;font-size:12px"><div align="center">Thank you for giving us the opportunity to serve '.ucwords($data['customer_name']).'.<br>We are committed to delivering our best to complete the work and establish a long-lasting<br> relationship with '.ucwords($data['customer_name']).'.</div></td></tr></table></body></html>';

    // echo $html;
    // die;

    return $html;
}





    function numtoWord($number)
{
    
// $number = 120050;
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);

  return  ucwords($result);
}
