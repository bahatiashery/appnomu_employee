<?php
session_name("admin");
session_start();

$dbServername = "localhost";
$dbUsername = "appnomu_admin";
$dbPassword = "appnomu";
$dbName = "appnomu_admin";

$connect = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);

$phone=$_SESSION["PHONE"];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST["verify"])){
        $otp = $_SESSION['otp'];
        $otp_code = $_POST['otp_code'];

  $email=$_SESSION["EMAIL"];
  
  $query=mysqli_query($connect, "SELECT * FROM users where email = '$email'") or mysqli_error("Error in query!!!");
	$fetch=mysqli_fetch_assoc($query);
		$_SESSION["ID_NO"]=$fetch["id_no"];
  
        if($otp != $otp_code){
            ?>
           <script>
               alert("Invalid OTP code");
           </script>
           <?php
        }else{
            ?>
             <script>
                   window.location.replace("html/index.php");
             </script>
             <?php
        }

    }

if (isset($_POST["resend"])) {
    $otp = rand(100000,999999);
    $phone = $_SESSION['PHONE'];
    $fname = $_SESSION['FNAME'];
    $mname = $_SESSION['MNAME'];
    $lname = $_SESSION['LNAME'];
    $_SESSION['otp'] = $otp;
  
                    require 'vendor/autoload.php';
                     
                    $mail = new PHPMailer(true);
                     
                    try {
                        $mail->SMTPDebug = 2;                                       
                        //$mail->isSMTP();                                            
                        $mail->Host         = "tls://smtp.gmail.com";                    
                        $mail->SMTPAuth   = true;                             
                        $mail->Username   = 'Bahati@appnomu.net';
                        $mail->Password   = 'UgandanN256';                       
                        $mail->SMTPSecure = 'tls';                              
                        $mail->Port       = 587;  
                        $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
                     
                        $mail->setFrom('Bahati@appnomu.net', 'AppNomu Digital Markets');           
                        $mail->addAddress($email);
                          
                        $mail->isHTML(true);                                  
                        $mail->Subject = "Login Verification";
                        $mail->Body= "<div style='margin:15px; border: 1px solid blue; width:80%; padding:20px;'><p style='font-family:Georgia;'> 
                    <b>Hi $fname $mname $lname, </b> <br>
                    Welcome back! To ensure the security of your account, please enter the verification code below to log in to the AppNomu Employee Portal:<br><br>
                   <b>Verification Code: $otp</b><br><br>
                    If you didn't request this, please let us know right away.<br><br>
                      <hr>
                      Cheers. <br>
                      Bahati Asher Faith</p></div>";
    
                        //$mail->AltBody = 'Body in plain text for non-HTML mail clients';
                        $mail->send();
                        ?>
                                <script>
                                    alert("<?php echo "" ?>");
                                </script>
                                <?php
                                echo "<script>location.href='verification.php';</script>";
                        //echo "Mail has been sent successfully!";
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    } 
   
}
?>

<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>AppNomu Employee portal</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/appnom.jpeg" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="index.php" class="app-brand-link gap-2">
                  <img width="150" src="assets/img/favicon/appnom.jpeg">
                </a>
              </div>
              <!-- /Logo -->
              <!-- <h4 class="mb-2">Welcome to AppNomu Employee Portal! 👋</h4> -->
              <p class="mb-4">Enter the verification code sent to your email
              <a href="mailto:help@appnomu.com">
                  <span style="color:blue;"><?php echo ($_SESSION["EMAIL"]);?></span>
                </a></p>

              <form id="formAuthentication" class="mb-3" action="verification.php" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Enter the OTP code here</label>
                  <input
                    type="text" required
                    class="form-control"
                    id="otp_code"
                    name="otp_code"
                    placeholder="Enter the OTP code here"
                    autofocus
                  />
                </div>
                <div class="mb-3">
                  <button name="verify" class="btn btn-primary d-grid w-100" type="submit">Verify</button>
                </div>
                </form>
                <form role="form" action="verification.php" method="POST">
                            <fieldset>
                              <p> Resend OTP in <span id="countdowntimer">60 </span> Seconds</p>
                              <input class="btn btn-primary" id="btn" style="display:none;" type="submit" name="resend" value="Resend">
                            </fieldset>
                        </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
var timeleft = 60;
    var downloadTimer = setInterval(function(){
    timeleft--;
      
  if(timeleft<=0){
       var x = document.getElementById("btn");
      x.style.display = "block";
  }
    document.getElementById("countdowntimer").textContent = timeleft;
    if(timeleft <= 0)
        clearInterval(downloadTimer);
    },1000);
</script>

  </body>
</html>
