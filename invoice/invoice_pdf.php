<?php
include_once("includes/config.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// include 'dbconnect.php';
use Dompdf\Dompdf; 
use Dompdf\Options; 

$html ='';
 if($_GET['invoise_id']&& !empty($_GET['invoise_id'])){
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
    $dompdf->stream("invoice.pdf", array("Attachment" => false));
 




function createInvoicePDF($id){

    $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
    $query = "SELECT * FROM invoices where id =".$id;
	$results = $mysqli->query($query);
    $data = $results->fetch_assoc();

  

    
    $html ='
    <html xmlns=http://www.w3.org/1999/xhtml>
    <head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <title>Tax Invoice</title>
    <style>table tr td{border-collapse:collapse}</style>
    </head>
    <body style="margin:0;padding:0 0">
    <table align=center width=550 border=0 cellspacing=0 cellpadding=0 style="border:solid 1px #000;font-family:Arial,Helvetica,sans-serif;line-height:18px;border-collapse:collapse;font-size:13px;color:#000;padding:5px">
    <tbody>
    <tr style=background:#fff>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px"><strong style=color:#000;font-size:14px>
    <img src="https://www.thenurserys.com/img/the-nurserys-logo.png" style=height:75px></strong>
    <strong style=color:#000;font-size:16px;float:right;margin-top:20px>
    Tax Invoice
    <br>Invoice: '.$data['invoice_num'].'<br>Date: '.date('d-M-Y',strtotime($data['invoice_date'])).'</strong>
    </td>
    </tr>
    <tr>
    <td style=border-collapse:collapse>
    <table width=100% border=0 cellspacing=0 cellpadding=0 style=font-family:Arial,Helvetica,border:none;border-collapse:collapse;font-size:11px;color:#000>
    <tbody>
    <tr>
    <td style=border-collapse:collapse;padding:5px;font-size:12px><strong style=color:#000;font-size:16px>The Nurserys</strong><br><b>Office Add:</b>
    Z-33 Deepak Vihar Uttam Nagar<br> New Delhi 110059<br><b>Plants Add: </b>Palam Vihar Rd, near Durga <br>Mata Mandir Bijwasan,New Delhi 110061<br><b>Email:
    </b><a href="mailto:info@thenurserys.com" target="_blank">info@thenurserys.com</a> &nbsp; &nbsp;<b>Phone: </b><a href="tel:7827734874" target="_blank" >7827734874</a><br>
    <b>GST: </b>07HSTPK9389C1ZU</td><td>
    </td><td style=border-collapse:collapse;padding:5px;font-size:12px><strong style=color:#000;font-size:16px>'.$data['customer_name'].'</strong><br><b>Add:
    </b>'.$data['customer_address_1'].'<br><b>Email:
    </b>'.$data['customer_email'].' &nbsp; &nbsp;<b>Phone: </b>'.$data['customer_phone'].'<br><b>GST: </b>'.$data['custmore_gst'].' </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>

    <tr>
    <td style=border-collapse:collapse;border>
    <table width=100% border=0 cellspacing=0 cellpadding=0 style=font-family:Arial,Helvetica,border:none;border-collapse:collapse;font-size:11px;color:#000>
    <tbody>
    <tr>
    <td width=47% bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-top:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">
    <strong>Item/Description</strong></td>
    <td width=10% bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-top:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=center><strong>Quantity</strong></div>
    </td>
    <td width=22% bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-top:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=center><strong>Price</strong></div>
    </td>
    <td width=22% bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-top:solid 1px #000; border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=center><strong>HSN Code / SAC</strong></div>
    </td>
    <td width=21% bgcolor=#f1f1f1 style="border-bottom:solid 1px #000; border-top:solid 1px #000; border-collapse:collapse;padding:2px">
    <div align=right><strong>Amount</strong></div>
    </td>
    </tr>';
    $itemsData  = json_decode($data['itesData']);
    $subTotalIn = 0;
    foreach($itemsData as $val){
    $subTotalIn = $subTotalIn  + $val->invoice_product_sub;
        // print_r($val);
    $html .='<tr>
            <td style="border-right:solid 1px #000;border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">'.$val->invoice_product.'</td>
            <td style="border:solid 1px #000;border-collapse:collapse;padding:2px">
            <div align=center>'.$val->invoice_product_qty.'</div>
            </td>
            <td style="border:solid 1px #000;border-collapse:collapse;padding:2px">
            <div align=center>'.$val->invoice_product_price.'</div>
            </td>
            <td style="border:solid 1px #000;border-collapse:collapse;padding:2px">
            <div align=center>'.$val->hncCode.'</div>
            </td>
            <td style="border-bottom:solid 1px #000;border-collapse:collapse;padding:2px">
            <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$val->invoice_product_sub.'</strong></div>
            </td>
            </tr>';
    }
    $html .='<tr>
    <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
    <td colspan=3 bgcolor=#f1f1f1 style="border:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=right><strong>Sub Total</strong></div>
    </td>
    <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-bottom:1px solid">
    <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$subTotalIn.'</strong></div>
    </td>
    </tr>';

    if(isset($data['invoice_shipping']) && !empty($data['invoice_shipping'])){
        $html .='<tr>
        <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
        <td colspan=3 bgcolor=#f1f1f1 style="border:solid 1px #000;border-collapse:collapse;padding:2px">
        <div align=right><strong>Flight charges</strong></div>
        </td>
        <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-bottom:1px solid">
        <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$data['invoice_shipping'].'</strong></div>
        </td>
        </tr>';
    }   

    if((isset($data['cgst_persant']) && !empty($data['cgst_persant'])) && (isset($data['cgst_value']) && !empty($data['cgst_value'])) ){

        $html.= '<tr>
        <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
        <td colspan=3 bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">
        <div align=right><strong>CGST : '.$data['cgst_persant'].'%</strong> </div>
        </td>
        <td bgcolor=#f1f1f1 style=border-collapse:collapse;padding:2px>
        <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$data['cgst_value'].'</strong></div>
        </td>
        </tr>';

    }else{
        $html.= '<tr>
        <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
        <td colspan=3 bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">
        <div align=right><strong>CGST : --</strong> </div>
        </td>
        <td bgcolor=#f1f1f1 style=border-collapse:collapse;padding:2px>
        <div align=right><strong> --</strong></div>
        </td>
        </tr>';
    }


    if((isset($data['sgst_persant']) && !empty($data['sgst_persant'])) && (isset($data['sgst_value']) && !empty($data['sgst_value'])) ){
        $html .='<tr>
        <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
        <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
        <div align=right><strong>SGST : '.$data['sgst_persant'].'%</strong></div>
        </td>
        <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
        <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$data['sgst_value'].'</strong></div>
        </td>
        </tr>';
    }else{
        $html .='<tr>
        <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
        <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
        <div align=right><strong>SGST : --</strong></div>
        </td>
        <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
        <div align=right><strong> --</strong></div>
        </td>
        </tr>';
    }



   if((isset($data['igst_persant']) && !empty($data['igst_persant'])) && (isset($data['igst_value']) && !empty($data['igst_value'])) ){

    $html .='
    <tr>
    <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
    <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=right><strong>IGST : '.$data['igst_persant'].'%</strong></div>
    </td>
    <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
    <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$data['igst_value'].'</strong></div>
    </td>
    </tr>';
   }else{
    $html .='
    <tr>
    <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
    <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=right><strong>IGST : --</strong></div>
    </td>
    <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
    <div align=right><strong> --</strong></div>
    </td>
    </tr>';
   }
   
   
    if((isset($data['invoice_discount']) && !empty($data['invoice_discount']))){


   $html .='
    <tr>
    <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-collapse:collapse;padding:2px">&nbsp;</td>
    <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=right><strong>Discount</strong></div>
    </td>
    <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
    <div align=right><strong>'.$data['invoice_discount'].'</strong></div>
    </td>
    </tr>';
    
    }
    

    $html .='
    <tr>
    <td bgcolor=#f1f1f1 style="border-right:solid 1px #000;border-top:solid 1px #000;border-collapse:collapse;padding:2px">
    <b>'.numtoWord($data['invoice_total']).' </b>
    </td>
    <td colspan=3 bgcolor=#f1f1f1 style="border-top:solid 1px #000;border-collapse:collapse;padding:2px">
    <div align=right><strong>Grand Total</strong></div>
    </td>
    <td bgcolor=#f1f1f1 style="border-collapse:collapse;padding:2px;border-top:1px solid;border-left:1px solid">
    <div align=right><strong><img src="https://pamoist.com/assets/img/rupee.png" style=height:10px> '.$data['invoice_total'].'</strong></div>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px"><strong style=font-size:15px>E.&O.E</strong>
    <br>Subject to Delhi jurisdiction & standard terms & conditions.</td>
    </tr>

    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px">
    <b>For The Nurserys</b><br>
    This is a computer generated document no signature is required</td>
    </tr>

    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px"><strong style=font-size:15px>FOR RTGS / NEFT BANK DETAILS</strong><br>
    <br>A/C Name : THE NURSERYS
    <br>A/C No : 50200079564892
    <br>Bank Name : HDFC BANK
    <br>Bank Branch : VIKAS PURI
    <br>IFSC Code : HDFC0007032 <br>
    </td>
    </tr>
    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px">
    <b>Deals in:</b> All types of plants, trees, soil, shrubs hedges, and pot materials, etc.
    </td>
    </tr>
    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:10px 2px;font-size:12px">
    <b>Services we offer:</b> Landscape garden design & development, garden maintenance, hydroponic gardening, plants for rent, artificial and natural grass, vertical garden and gifting plants.
    </td>
    </tr>
    <tr>
    <td style="border:solid 1px #000;border-collapse:collapse;padding:2px 2px;font-size:12px">
    <div align=center>Thank you for giving us the opportunity to serve '.$data['customer_name'].'.<br>We are committed to delivering our best to complete the work and establish a long-lasting<br>
    relationship with '.$data['customer_name'].'.For more information, please visit <a  href="www.thenurserys.com">www.thenurserys.com</a></div>

    
    </td>
    </tr>
    </tbody>
    </table>
    </body>
    </html>';
//    echo $html;
//    die;
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
