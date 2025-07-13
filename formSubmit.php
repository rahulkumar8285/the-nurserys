<?php
if(isset($_POST) && !empty($_POST) && $_POST['submit'] == 'Submit' ){
$name = $_POST['name'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$service = $_POST['service'];
$message_content = $_POST['message'];

$to = "rahul.kumar@thenurserys.com"; // this is your Email address
$subject = "Website Leads";
$from = 'info@thenurserys.com'; // this is the sender's Email address

$headers = "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = '
<html>
<head>
  <title>Website Leads</title>
</head>
<body>
  <div >
    <img src="https://www.thenurserys.com/img/the-nurserys-logo.png" alt="Logo" style="width: 150px; height: auto;">
  </div>
  <p>New lead details:</p>
  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th>Name</th><td>' . htmlspecialchars($name) . '</td>
    </tr>
    <tr>
      <th>Email</th><td>' . htmlspecialchars($email) . '</td>
    </tr>
    <tr>
      <th>Mobile</th><td>' . htmlspecialchars($mobile) . '</td>
    </tr>
    <tr>
      <th>Service Needed</th><td>' . htmlspecialchars($service) . '</td>
    </tr>
    <tr>
      <th>Message</th><td>' . nl2br(htmlspecialchars($message_content)) . '</td>
    </tr>
  </table>
</body>
</html>';


if(mail($to,$subject,$message,$headers)){
echo '<script> alert("request sent successfully"); </script>' ;
header("Location: https://www.thenurserys.com/thank-you");
}else{
echo '<script> alert("Mail is Not Working Please contact on Number in the top section"); </script>' ;
header("Location: https://www.thenurserys.com");
}


}else{
    echo '<script> alert("Mail is Not Working Please contact on Number in the top section"); </script>' ;
    header("Location: https://www.thenurserys.com");
}