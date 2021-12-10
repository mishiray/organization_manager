<?php



function getSession(){
  global $Site;
  $return=null;
  if ( !empty($Site["session"]['Site']) ) {
      $return=$Site["session"];
  }else{
    @session_destroy();
  }
  return $return;
}

function setSession($value) {
  global $Site;
  $_SESSION["Site"] = $value;
  $Site["session"] = $_SESSION;
}

function sessionExists($session){
  global $ezDb,$siteName;
  $return=null;
  if( !empty($ezDb->get_var("SELECT `token` FROM `userprofile` WHERE `token`='".sha1(md5(base64_encode($session)))."';")) ):
    $return=true;
  endif;
  return $return;
}

function setCookies($name,$value=null,$second=86400){
   setcookie($name, $value, time() + ($second), "/");
}

function getCookie($name){
   $cookie=null;
   if(isset($_COOKIE[$name])):
      $cookie = $_COOKIE[$name];
      if(empty($cookie)):
         unset($_COOKIE[$name]);
         $cookie = null;
      endif;
   endif;
   return $cookie;
}

function destroyCookie($name){
   if( isset($_COOKIE[$name]) ){
      $_COOKIE[$name]="";
      setCookies($name, "", time() - 3600);
      unset($_COOKIE[$name]);
   }
}

function useIfPosted($theField, $theDefaultValue = "") {
  if (isset($_POST["$theField"])) {
      return filter_var($_POST["$theField"], FILTER_SANITIZE_STRING);
  } else {
      return $theDefaultValue;
  }
}

function useIfIntPosted($theField, $theDefaultValue = "") {
  if (isset($_POST["$theField"])) {
      return filter_var($_POST["$theField"], FILTER_SANITIZE_NUMBER_INT);
  } else {
      return $theDefaultValue;
  }
}

function useIfSent($theField, $theDefaultValue = "") {
  //works? like use_if_posted but for all post/get  form variables
  if (isset($_REQUEST["$theField"])) {
      return filter_var($_REQUEST["$theField"], FILTER_SANITIZE_STRING);
  } else {
      return $theDefaultValue;
  }
}

function useIfIntSent($theField, $theDefaultValue = "") {
  if (isset($_REQUEST["$theField"]) && $_REQUEST["$theField"] != "") {
      return filter_var($_REQUEST["$theField"], FILTER_SANITIZE_NUMBER_INT);
  } else {
      return $theDefaultValue;
  }
}

function validateDate($date, $format = 'Y-m-d'){
  $d = DateTime::createFromFormat($format, $date);
  return $d && $d->format($format) === $date;
}

function checkEmail($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function checkPhone($phone){
    // Alt preg_match('/^[A-z0-9_\-]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{2,4}$/', $email);
    // Allow +, - and . in phone number
    $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    // Remove "-" from number
    $phone_to_check = str_replace("-", "", $filtered_phone_number);
    $phone_to_check = str_replace(")", "", $phone_to_check);
    $phone_to_check = str_replace("(", "", $phone_to_check);
    // Check the lenght of number
    // This can be customized if you want phone number from a specific country
    if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
      return false;
    } else {
      return true;
    }
}

function useIfExists($filename, $actionFilename, $alternative = "", $actionAlternative = "") {
   //phase out in favour of use_if
   //echo("$filename $alternative $actionFilename $actionAlternative"); //debug
   if (file_exists($filename)) {
      //        echo("file exists, doing $actionFilename($filename)"); //debug
      $theFunction = $actionFilename;
      //        echo $theFunction.",".$filename;//debug
   } else {
      if ($alternative != "") {
         $filename = $alternative;
      }
      //        echo("doesn't exist, using alternative: $actionFilename($alternative)");//debug
      if ($actionAlternative != "") {
         $theFunction = $actionAlternative;
      }
   }
   if (isset($theFunction)) {
      $theFunction("$filename");
   }
   //    echo("$actionFilename($filename)");//debug
}

function includeIt($filename) {
  include $filename;
}

function requireIt($filename) {
  require $filename;
}

function redirect($theUrl) {
  header("Location: $theUrl");
  exit;
}

function cryptoRandSecure($min, $max) {
   //http://us1.php.net/manual/en/function.openssl-random-pseudo-bytes.php#104322
   $range = $max - $min;
   if ($range < 0) {
      return $min; // not so random...
   } $log = log($range, 2);
   $bytes = (int) ($log / 8) + 1; // length in bytes
   $bits = (int) $log + 1; // length in bits
   $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
   do {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
      $rnd = $rnd & $filter; // discard irrelevant bits
   } while ($rnd >= $range);
   return $min + $rnd;
}

function getToken($length) {
    //http://us1.php.net/manual/en/function.openssl-random-pseudo-bytes.php#104322
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[cryptoRandSecure(0, strlen($codeAlphabet))];
    }
    return $token;
}

function testInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES);
  return $data;
}

function testInputln($data) {
  $data=str_replace(array("\r\n", "\r", "\n"), "<br/>", $data);
  $data=str_replace(array("\r\t", "\r", "\t"), "  ", $data);
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES);
  return $data;
}

function testInputReverse($data) {
  $data = htmlspecialchars_decode($data, ENT_QUOTES);
  $data = stripcslashes($data);
  $data = trim($data);
  return $data;
}

function testInputReverseln($data) {
  $data = htmlspecialchars_decode($data, ENT_QUOTES);
  $data = stripcslashes($data);
  $data=str_replace("<br/>", "\n", $data); 
  $data=str_replace("  ", "\t", $data);
  $data = trim($data);
  return $data;
}

function nl2br2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), "<br/>", $string);
return $string;
}
function nl2sp2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), " ", $string);
return $string;
}
function br2nl2($string) {
$string = str_replace("<br/>", "\n", $string);
return $string;
}
function tb2sp2($string) {
$string = str_replace(array("\r\t", "\r", "\t"), "  ", $string);
return $string;
}
function tb2br2($string) {
$string = str_replace(array("\r\t", "\r", "\t"), "<br/>", $string);
return $string;
}

function stripeInputReverse($content){
  $content_stripe=str_replace("&lt;", "<", $content);
  $content_stripe=str_replace("&gt;", ">", $content_stripe);
  return $content_stripe;
}
function stripeInput($content){
  $content_stripe=str_replace("<", "&lt;", $content);
  $content_stripe=str_replace(">", "&gt;", $content_stripe);
}

// smarty display function;

function smartyDisplay($template){
  global $smarty;
  if ($smarty->templateExists($template)==true) {
    $smarty->display($template);
  } elseif($smarty->templateExists($template)==false){
      $smarty->display("default.html");
  }
}

//logout control
function logoutUser($page="login"){
  global $Site;
  unset($Site["session"]["User"]);
  unset($Site["session"]['Site']["Page"]);
  $_SESSION=$Site["session"];
  redirect("$page");
}

// login authentication function
function loginUser(){
  global $ezDb, $siteName, $dashboard, $admin, $smarty, $Site, $domainName, $siteProtocol, $sitePage;
  $userName=$Site["post"]['dataUsername'];
  $password=$Site["post"]['dataPass'];
  $userType= ($sitePage=="admin"? 'admin': 'client');
  $data=$ezDb->get_row("SELECT `password`,`username`, `email`,`active` FROM `userprofile` WHERE (`username`='$userName' OR `email`='$userName') AND `usertype`='$userType' AND `verified`='1';");
  
  if( !empty($data) && is_object($data) ){
    //print_r($data);
    if($data->active == 0){
      $smarty->assign("fail",'Account Inactive, Please contact the Adminisitrator!');
      return false;
    }
    elseif( $data->password==base64_encode($password) ){
      $Site["session"]['User'][$userType]['Token'] = getToken(6);
      $ezDb->query("UPDATE `userprofile` SET `token`='".sha1(md5(base64_encode($Site["session"]["User"][$userType]["Token"])))."' WHERE `username`='$data->username';");
      $Site["session"]["User"]["Username"]=$data->username;
      $Site["session"]['Site']["Page"]= ($sitePage=="admin"? $admin: $dashboard);
      logEvent($data->email, "login", $userType, 'userprofile', array());
      $_SESSION=$Site["session"];
      return true;
    }else{
      $smarty->assign("fail",'Invalid password!');
      return false;
    }
  }else{
    $smarty->assign("fail",'Username/Email does not exist!');
    return false;
  }
}

//true logics
/*Latest Real Functions*/
// Delete Directory and Files
function deleteDirAndFilesInIt($target) {
  if(file_exists($target) and is_dir($target)){
      $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
      foreach( $files as $file ){
        deleteDirAndFilesInIt( $file );      
      }
      if (file_exists($target) and is_dir($target)) {
        rmdir( $target );
      }
      return true;
  } elseif(file_exists($target) and is_file($target)){
    unlink( $target );
    return true;  
  }else{
    return false;
  }
}

//user user information functions
function userInfo($username=null) {
  global $ezDb, $Site, $smarty;
  $username = ( !empty($username)? $username: $Site["session"]["User"]["Username"] );

  $return =$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$username' OR `email`='$username';");
  if(!empty($return) && $return->usertype=='admin'){
    try {
      $return->department =  $ezDb->get_var("SELECT d.name FROM `department` as d INNER JOIN `employees` as e ON d.id = e.department_id where e.email = '$return->email'");
      $return->employeeid =  $ezDb->get_var("SELECT `employeeid` FROM `employees` WHERE `email` = '$return->email'");
	  } catch (\Throwable $th) {
      $return->department = NULL;
    }
	}

  if( !empty($return) and empty($username)){
    if( !empty($Site["session"]["User"]) ){
      // $return->profiledocs=json_decode($return->profiledocs);
      $return->initials= ucfirst( substr($return->firstname, 0,1) ) ."". ucfirst( substr($return->lastname, 0,1) );
      $Site["session"]["User"]["userinfo"] = $return;
    }
  }
  return $return;
}

function dateDifference($lowerDate, $laterDate){
  $datetime1 = new DateTime($lowerDate);
  $datetime2 = new DateTime($laterDate);
  $return = $datetime1->diff($datetime2);
  $return = dateDiffToNum($return);
  // error_log(json_encode($return));
  return $return;
}
function dateDiffToNum($date,$property='min')
{
  // to be improved
  switch ($property) {
    case 'min':
      $date->min= (($date->d * 24 * 60)+ ($date->h * 60)+ ($date->i)+ ($date->m * 60)+ ($date->y * 60));
      break;
    
    default:
       $date->min= (($date->d * 24 * 60)+ ($date->h * 60)+ ($date->i)+ ($date->m * 60 * 24 * 28)+ ($date->y * 60 * 24 * 365));
      break;
  }
  // {"y":0,"m":0,"d":11,"h":7,"i":11,"s":0,"weekday":0,"weekday_behavior":0,"first_last_day_of":0,"invert":0,"days":11,"special_type":0,"special_amount":0,"have_weekday_relative":0,"have_special_relative":0}

  return $date;
}

/*Project Functions*/
function getCourseIN($value=array()){
  $ret="";
  if (is_array($value) and !empty($value)) {
    foreach ($value as $key => $val) {
      $ret.=($key==0 ?"'$val'" :", '$val'");
    }
    $ret="WHERE `courseid` IN($ret)";
  }
  return $ret;
}

function getQuestions($courseid){
  global $ezDb;
  $questions=$ezDb->get_results("SELECT * FROM `questions` WHERE `courseid`='$courseid';");
    if (!empty($questions)):
      foreach ($questions as $key => $question) :
        $questions[$key]->options=@json_decode($question->options);
      endforeach;
    endif;
  return $questions;
}

function getOQuestions($matid){
  global $ezDb;
  $questions=$ezDb->get_results("SELECT * FROM `mat_questions` WHERE `parentid`='$matid';");
    if (!empty($questions)):
      foreach ($questions as $key => $question) :
        $questions[$key]->options=@json_decode($question->options);
      endforeach;
    endif;
  return $questions;
}
function getRandomQuestion($questions, $searchArray){
  do{
    $randVal=rand(0, count($questions)-1);
  }while(in_array($randVal, $searchArray));
  return array($questions[$randVal], $randVal);
}

function secToHMS($seconds) {
  $hours = floor($seconds / 3600);
  $minutes = floor(($seconds / 60) % 60);
  $seconds = $seconds % 60;
  $hours=(strlen($hours)==1?"0":"").$hours;
  $minutes=(strlen($minutes)==1?"0":"").$minutes;
  $seconds=(strlen($seconds)==1?"0":"").$seconds;
  return array("hh"=> $hours, "mm"=> $minutes, "ss"=> $seconds);
}

function getExamTiming(){
  global $sessions, $Site, $posts, $ezDb, $dateNow, $siteProtocol, $domainName;
  $assessment=$ezDb->get_row("SELECT *, TIMESTAMPDIFF(SECOND, `startdate`, '$dateNow') AS `attempttime` FROM `attempt` WHERE `regid`='".$sessions->assessment['regid']."' AND `courseid`='".$sessions->assessment['courseid']."' AND `user`='".$sessions->assessment['user']."'");
  $assessment->usedtime=$assessment->attempttime;
  $ezDb->query("UPDATE `attempt` SET `usedtime`='$assessment->usedtime' WHERE `id`='$assessment->id'");
  if (($assessment->settime+5) <= $assessment->attempttime) {
    $assessment->submitstatus='toresult';
    $ezDb->query("UPDATE `attempt` SET `submitstatus`='$assessment->submitstatus', `enddate`='$dateNow' WHERE `regid`='".$sessions->assessment['regid']."' AND `courseid`='".$sessions->assessment['courseid']."' AND `user`='".$sessions->assessment['user']."'");
    if (empty($ezDb->get_var("SELECT `regid` FROM `results` WHERE `regid`='".$sessions->assessment['regid']."' AND `courseid`='".$sessions->assessment['courseid']."' AND `user`='".$sessions->assessment['user']."' AND `examtype`='".$sessions->assessment['examtype']."'")) ) {
      $ezDb->query("INSERT INTO `results` (`regid`, `courseid`, `totalquestions`, `score`, `datetaken`, `examtype`, `user`, `expectedduration`, `usedduration`) VALUES ('$assessment->regid', '$assessment->courseid', '$assessment->attainable', '$assessment->score', '$assessment->startdate', '".$sessions->assessment['examtype']."', '$assessment->user', '$assessment->settime', '".($assessment->usedtime-5)."')");
      unset($Site["session"]['assessment']);
      $_SESSION= $Site["session"];
    }
    redirect("$siteProtocol"."$domainName/dashboard/exam?type=".$sessions->assessment['examtype']."&course=".$sessions->assessment['courseid']."&regid=".$sessions->assessment['regid']."&page=intro");
    exit;
    $assessment->remains=array("hh"=> "00", "mm"=> "00", "ss"=> "00");
  }else{
    $assessment->remains=secToHMS(($assessment->settime-$assessment->usedtime));
  }
  return $assessment->remains;
}

function getExamTiming2(){
  global $sessions, $Site, $posts, $ezDb, $dateNow, $siteProtocol, $domainName;
  $assessment=$ezDb->get_row("SELECT *, TIMESTAMPDIFF(SECOND, `startdate`, '$dateNow') AS `attempttime` FROM `mat_attempt` WHERE `regid`='".$sessions->assessment['regid']."' AND `parentid`='".$sessions->assessment['matid']."' AND `user`='".$sessions->assessment['user']."'");
  $assessment->usedtime=$assessment->attempttime;
  $ezDb->query("UPDATE `mat_attempt` SET `usedtime`='$assessment->usedtime' WHERE `id`='$assessment->id'");
  if (($assessment->settime+5) <= $assessment->attempttime) {
    $assessment->submitstatus='toresult';
    $ezDb->query("UPDATE `mat_attempt` SET `submitstatus`='$assessment->submitstatus', `enddate`='$dateNow' WHERE `regid`='".$sessions->assessment['regid']."' AND `parentid`='".$sessions->assessment['matid']."' AND `user`='".$sessions->assessment['user']."'");
    if (empty($ezDb->get_var("SELECT `regid` FROM `results` WHERE `regid`='".$sessions->assessment['regid']."' AND `parentid`='".$sessions->assessment['matid']."' AND `user`='".$sessions->assessment['user']."' AND `examtype`='".$sessions->assessment['examtype']."'")) ) {
      $ezDb->query("INSERT INTO `mat_results` (`regid`, `parentid`, `totalquestions`, `score`, `datetaken`, `examtype`, `user`, `expectedduration`, `usedduration`) VALUES ('$assessment->regid', '$assessment->parentid', '$assessment->attainable', '$assessment->score', '$assessment->startdate', '".$sessions->assessment['examtype']."', '$assessment->user', '$assessment->settime', '".($assessment->usedtime-5)."')");
      unset($Site["session"]['assessment']);
      $_SESSION= $Site["session"];
    }
    redirect("$siteProtocol"."$domainName/dashboard/exam?type=".$sessions->assessment['examtype']."&course=".$sessions->assessment['courseid']."&regid=".$sessions->assessment['regid']."&material=".$sessions->assessment['matid']."&page=intros");
    exit;
    $assessment->remains=array("hh"=> "00", "mm"=> "00", "ss"=> "00");
  }else{
    $assessment->remains=secToHMS(($assessment->settime-$assessment->usedtime));
  }
  return $assessment->remains;
}

function attachAnswers($answers, $questions){
  if (is_array($answers)) {
    foreach ($answers as $kee => $value) {
      $questions[$value->key]->choice=$value->answer;
      $questions[$value->key]->ansKey=$kee;
    }
  }
  return $questions;
}

function generateScore($answers, $questions){
  $score=0;
  if (is_array($answers)) {
    foreach ($answers as $kee => $value) {
      if (array_key_exists($value->key, $questions) and (($questions[$value->key]->answer == $value->answer and $questions[$value->key]->qtype=='objective') 
        or ($questions[$value->key]->qtype=='theory' and !empty($questions[$value->key]->marked) and $questions[$value->key]->marked=='1'))) {
        $score++;
      }
    }
  }
  return $score;
}

function answerExists($answers, $qid){
  $return=-1;
  if (is_array($answers)) {
    foreach ($answers as $kee => $value) {
      if ($value->key==$qid) {
        $return=$kee;
        break;
      }
    }
  }
  return $return;
}


/*End Project Functions*/

function optimizeJSON($variable){
  $variable=json_encode($variable);
  $variable=str_replace('""', '', $variable);
  $variable=rtrim($variable,'"');
  $variable=ltrim($variable,'"');
  return $variable;
}

/*End Latest Real Function*/

// return system settings
function getSettings($field=null){
  global $ezDb;
  $return=false;
  if(empty($field)){
    $return=$ezDb->get_row("SELECT * FROM `settings`;");
    if (!empty($return)) {
      foreach ($return as $key=>$value) {
        if ($key=='id' || $key=='updated') {
          continue;
        }
        $return->$key=@json_decode($value);
      }
    }
  }else{
    $return=$ezDb->get_var("SELECT `$field` FROM `settings`;");
    $return=@json_decode($return);
  }
  $return=(empty($return)? (new stdClass()): $return);
  return $return;
}

// return system settings
function getnSettings($field=null){
  global $ezDb;
  $return=false;
  if(empty($field)){
    $return=$ezDb->get_row("SELECT * FROM `settings`;");
    if (!empty($return)) {
      foreach ($return as $key=>$value) {
        if ($key=='id' || $key=='updated') {
          continue;
        }
        $return->$key=@json_decode($value);
      }
    }
  }else{
    $return=$ezDb->get_var("SELECT `value` FROM `settings` WHERE `item` = '$field';");
    $return=@json_decode($return);
  }
  $return=(empty($return)? (new stdClass()): $return);
  return $return;
}

//return all stored countries in database
function getCountries($id=null){
  global $ezDb, $siteName, $Site;
  $filter=( !empty($id)? "WHERE (`id`='$id' OR `name`='$id')" : "");
  return $ezDb->get_results("SELECT * FROM `countries` $filter ORDER BY `name` ASC;");
}

function getStates($cid=null,$id=null){
  global $ezDb, $siteName, $Site;
  if (empty(trim($cid))) {
    $cid=1;
  }
  $filter=( !empty($cid)? "AND (`cn`.`id`='$cid' OR `cn`.`name`='$cid')": ""). (!empty($id)? " AND (`st`.`id`='$id' OR `st`.`name`='$id')": "");
  return $ezDb->get_results("SELECT `st`.`id`, `st`.`name`, `st`.`country_id` FROM `states` AS `st`, `countries` AS `cn` WHERE `st`.`country_id`=`cn`.`id` $filter ORDER BY `st`.`name` ASC;");
}

function getCities($sid=null,$id=null){
  global $ezDb, $siteName, $Site;
  if (empty(trim($sid))) {
    $sid=1;
  }
  $filter=( !empty($sid)? "AND (`st`.`id`='$sid' OR `st`.`name`='$sid')": ""). (!empty($id)? " AND (`ct`.`id`='$id' OR `ct`.`name`='$id')": "");
  return $ezDb->get_results("SELECT `ct`.`id`, `ct`.`name`, `ct`.`state_id` FROM `cities` AS `ct`, `states` AS `st` WHERE `ct`.`state_id`=`st`.`id` $filter ORDER BY `ct`.`name` ASC;");
}

//Function for Security Token Generation
function generateSecurityToken(){
  global $ezDb, $siteName, $companyName, $sitePage, $Site;
  $dateNow=date("Y-m-d H:i:s");
  $eol = "\r\n";
  $headers = "From: Lawcon <admin@cityhoppers.com.ng>" . $eol;
  $headers .= "Organisation: $companyName" . $eol;
  $headers .= "MIME-Version: 1.0" . $eol;
  $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
  $prefix="";
  $user = base64_encode($Site["session"]["User"]["Username"]);
  $user_email = base64_decode($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `username`='$user'"));
  $sec_token = $prefix.getToken(20);
  $the_subject = "Security Token Request";
  $the_message = "Token: $eol $eol $sec_token";
  if ($ezDb->query("UPDATE `userprofile` SET `recKey`='$sec_token', `recDate`='$dateNow' WHERE `username`='$user' AND `active`='1' AND `verified`='1'")):
    if (mail($user_email, $the_subject, $the_message, $headers)):
      return 'Security token has been successfully sent to your email';
    else:
      return 'There is a problem sending a security token to your email';
    endif;
  endif;
}
function nullifySecurityToken(){
  global $ezDb, $siteName, $companyName, $sitePage, $Site;
  $user = base64_encode($Site["session"]["User"]["Username"]);
  $ezDb->query("UPDATE `userprofile` SET `recKey`=NULL WHERE `username`='$user' AND `active`='1' AND `verified`='1'");
}
//log visit
function getLogger($user='', $token='', $table='', $event='', $usertype=''){
  global $ezDb, $siteName, $Site;
  $loggerWhere=(empty($user)?'':"`user`='$user'");
  $loggerWhere.=(empty($loggerWhere)?'':" AND ").(empty($token)?'':"`token`='$token'");
  $loggerWhere.=(empty($loggerWhere)?'':" AND ").(empty($table)?'':"`table`='$table'");
  $loggerWhere.=(empty($loggerWhere)?'':" AND ").(empty($event)?'':"`event`='$event'");
  $loggerWhere.=(empty($loggerWhere)?'':" AND ").(empty($usertype)?'':"`usertype`='$usertype'");
  $loggerWhere=(empty(trim($loggerWhere))? trim($loggerWhere): "WHERE $loggerWhere");
  $logger=$ezDb->get_results("SELECT * FROM `logger` $loggerWhere");
  if (!empty($logger)) {
    if (count($logger)==1) {
      $logger=$logger[0];
      $logger->content=@json_decode($logger->content);
    }else{
      foreach ($logger as $value) {
        $value->content=@json_decode($value->content);
      }
    }
  }
  return $logger;
}

//Log admin Events
function logEvent($user, $event, $usertype='', $tablename='', $content=''){
  global $ezDb, $siteName, $dateNow, $Site;
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `logger` ORDER BY `id` DESC LIMIT 1;");
  $content=@json_encode($content);
  $return=$ezDb->query("INSERT INTO `logger` (`token`, `user`, `event`, `usertype`, `table`, `content`, `dateadded`) VALUES ('$token','$user','$event', '$usertype','$tablename','$content','$dateNow')");
}

// Get the real extension of a file.
function getMime($file) {
  if (function_exists("finfo_file")) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    $mime = finfo_file($finfo, $file);
    finfo_close($finfo);
    return $mime;
  } else if (function_exists("mime_content_type")) {
    return mime_content_type($file);
  } else if (!stristr(ini_get("disable_functions"), "shell_exec")) {
    // http://stackoverflow.com/a/134930/1593459
    $file = escapeshellarg($file);
    $mime = shell_exec("file -bi " . $file);
    return $mime;
  } else {
    return false;
  }
}

function tableRoutine($tablename, $fields, $where, $order, $orderBy="DESC", $defField="`id`", $entryDefault="6"){
  global $dateNow, $gets, $smarty, $ezDb;

  if ( strpos($tablename, '`')===FALSE ) {
    $tablename="`$tablename`";
  }

  $gets->search=(empty($gets->search)? "": $gets->search);
  $filter=" ";
  if(!empty($gets->search) and !empty($columns=$ezDb->get_row("SELECT * FROM $tablename LIMIT 1")) ){
    $cnter=0;
    foreach ($columns as $key => $value) {
      if (strtolower($key)=='id') {
        continue;
      }
      $filter.=($cnter==0? "`$key` LIKE '%$gets->search%' ": "OR `$key` LIKE '%$gets->search%' ");
      $cnter++;
    }
    $where=(empty(trim($where))? "WHERE ($filter)": "WHERE $where AND ($filter)");
  }else{
    $where=(empty(trim($where))? "": "WHERE $where");
  }
  // $where=(empty($where)? "": "WHERE $where");
  $order=(empty($order)? "": "ORDER BY $order $orderBy");

  $gets->totalRecs=$ezDb->get_var("SELECT COUNT($defField) FROM $tablename $where;");
  $gets->entry=((empty($gets->entry) or !is_numeric($gets->entry) or $gets->entry<5)? $entryDefault : $gets->entry);
  $totalPages=($gets->entry>=$gets->totalRecs? 1: ($gets->totalRecs/$gets->entry) );
  $totalPagesArray=explode(".", "$totalPages");
  $gets->totalPages=(count($totalPagesArray)>1? $totalPagesArray[0]+1: $totalPages);

  $gets->page=((empty($gets->page) or $gets->page<0 or $gets->page > $gets->totalPages)? "1": $gets->page);
  if ( ( $gets->page * $gets->entry ) > $gets->totalRecs ) {
    // $gets->from= (( $gets->page * $gets->entry ) % $gets->totalRecs);
    $gets->from=  ($gets->page-1) * $gets->entry ;
    $gets->to= $gets->totalRecs;
  }elseif( ( $gets->page * $gets->entry ) <= ($gets->totalRecs) ){
    $gets->from=($gets->page-1) * $gets->entry;
    $gets->to= ($gets->page * $gets->entry );
  }
  // error_log(json_encode($gets));
  // error_log("SELECT $fields FROM $tablename $where $order LIMIT $gets->from, $gets->entry;");

  $ret=$ezDb->get_results("SELECT $fields FROM $tablename $where $order LIMIT $gets->from, $gets->entry;");
  $smarty->assign("Pager", $gets)->assign("countStarted", $gets->from+1);
  return $ret;
}

/*Generating Schedule Routine*/
function scheduleRoutineGenerator(){
  global $ezDb, $dateNow;
  
}

/* Alerting*/

/*regular*/
function alertUser($receivers, $severity, $content=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  //$receivers = $ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$receivers' AND `verified`='1'");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}


function alertDept($department, $severity, $content=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id where d.name LIKE'%$department%';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}

function alertManager($email, $severity, $content=''){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $manager = $ezDb->get_var("SELECT `manager_id` FROM `employees` WHERE `email` = '$email' ;");
  $manager_email = idToEmail($manager);
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($manager_email)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}

function alertHRManager($severity, $content=''){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where d.name LIKE'%Human Resources%' AND u.userrole = 'level2'  AND u.usertype = 'admin';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}

function alertAccountingManager($severity, $content=''){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where d.name LIKE'%Accounting%' AND u.userrole = 'level2'  AND u.usertype = 'admin';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}

function alertMd($severity, $content=''){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where d.name LIKE'%Administrative%' AND u.userrole = 'level0'  AND u.usertype = 'admin';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$dateNow')");
}

/*New alerts*/


function alertManager2($email, $severity, $content='', $page){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $manager = $ezDb->get_var("SELECT `manager_id` FROM `employees` WHERE `email` = '$email' ;");
  $manager_email = idToEmail($manager);
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`,`page`, `dateadded`) VALUES ('$token','".@json_encode($manager_email)."', '".@json_encode($emptyArray)."','$severity','$content','$page','$dateNow')");
}

function alertHRManager2($severity, $content='', $page, $table, $table_content){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where d.name LIKE'%Human Resources%' AND u.userrole = 'level2'  AND u.usertype = 'admin';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`,`page`,`table`,`table_content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$page','$table','$table_content','$dateNow')");
}

function alertDept2($department, $severity, $content='', $page){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray; 
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id where d.name LIKE'%$department%';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return =$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`,`page`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$page','$dateNow')");
}

function alertMd2($severity, $content='', $page){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT u.email FROM `userprofile` as u where u.userrole = 'level0'  AND u.usertype = 'admin';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`,`page`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$page','$dateNow')");
}
function alertUser2($receivers, $severity, $content='', $page){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  //$receivers = $ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$receivers' AND `verified`='1'");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `alerts` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `alerts` (`token`, `receivers`, `readers`, `severity`,`content`,`page`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$severity','$content','$page','$dateNow')");
}


/* Drop Memo */
function memoUser($receivers, $content=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray, $userinfo;
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `memo` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `memo` (`token`,`sender`, `receivers`, `readers`, `content`, `dateadded`) VALUES ('$token','$userinfo->email','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$content','$dateNow')");
  //alertUser($receivers, 0, "Memo Received");
}
function memoUserCrm($receivers, $content=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray, $userinfo;
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `memo` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `memo_crm` (`token`,`sender`, `receivers`, `readers`, `content`, `dateadded`) VALUES ('$token','$userinfo->email','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$content','$dateNow')");
  //alertUser($receivers, 0, "Memo Received");
}

function memoDept($department, $severity, $content=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id where d.name LIKE'%$department%';");
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `memo` ORDER BY `id` DESC LIMIT 1;");
  $content= trim($content);
  $return=$ezDb->query("INSERT INTO `memo` (`token`, `receivers`, `readers`, `content`, `dateadded`) VALUES ('$token','".@json_encode($receivers)."', '".@json_encode($emptyArray)."','$content','$dateNow')");
  //alertDept($receivers, 0, "Memo Received");
}


function get_employees_in_dept($department){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $receivers = $ezDb->get_col("SELECT e.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id where d.name LIKE'%$department%';");
  return $receivers;
}


function getMyManager($email){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  $manager = $ezDb->get_var("SELECT `manager_id` FROM `employees` WHERE `email` = '$email' ;");
  $manager_email = idToEmail($manager);
  return $manager_email;
}

function getManager($department){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_col("SELECT u.email FROM `employees` AS e INNER JOIN `department` as d on d.id = e.department_id INNER JOIN `userprofile` as u on u.email = e.email where  u.userrole = 'level2' AND d.name = '$department';");
}

function getUserRole($email = ''){ 
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT `userrole` FROM `userprofile` WHERE `email` = '$email'");
}

function idToEmail($employeeid=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT `email` FROM `employees` WHERE `employeeid` = '$employeeid'");
}

function emailToId($email=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT `employeeid` FROM `employees` WHERE `email` = '$email'");
}

function idtodepartment($employeeid=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT d.name FROM `department` AS d INNER JOIN `employees` as e on e.department_id = d.id where e.employeeid = '$employeeid'");
}
function emailtodepartment($email=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT d.name FROM `department` AS d INNER JOIN `employees` as e on e.department_id = d.id where e.email = '$email'");
}

function depttodepid($deptName=''){
  global $ezDb, $siteName, $dateNow, $Site, $emptyArray;
  return $ezDb->get_var("SELECT `id` FROM `department` where `name` LIKE'%$deptName%'");
}

function fireEmployee($employeeid=''){
  global $ezDb, $siteName, $dateNow, $dateNow2, $Site;
  $ezDb->query("UPDATE `employee` SET `status` = 0 WHERE `employeeid` = '$employeeid')");
  $ezDb->query("UPDATE `userprofile` SET `datefired` = $dateNow2, `verified` = 0 , `active` = 0  WHERE `refid` = '$employeeid')");

}

function getAMG($employeeid=''){
  global $ezDb, $siteName, $dateNow, $dateNow2, $Site;
  $head = $ezDb->get_row("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `userid` = '$employeeid'");
  $head->level1 = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$employeeid' AND `active` = 1 AND `email` != '' ");
  if(!empty($head->level1)){
    foreach($head->level1 as $value){
      $value->level2 = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$value->userid' AND `active` = 1 AND `email` != ''  ");
      $level2 = $value->level2;
      if(!empty($level2)){
        foreach($level2 as $value2){
          $value2->level3 = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$value2->userid' AND `active` = 1 AND `email` != '' ");
        }
      }
    }
  }
  return $head;
}

function getConsultTree($employeeid=''){
  global $ezDb, $siteName, $dateNow, $dateNow2, $Site;
  $node = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$employeeid' AND `active` = 1 AND `email` != '' ");
  if(!empty($node)){
    foreach($node as $value){
     $value->next =  getConsultTree($value->userid);
    }
  }
  return $node;
}


function getAMGTree($employeeid=''){
  global $ezDb, $siteName, $dateNow, $dateNow2, $Site;
  $head = $ezDb->get_row("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `userid` = '$employeeid'");
  $head->level = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$employeeid' AND `active` = 1 AND `email` != '' ");
  if(!empty($head->level)){
    foreach($head->level1 as $value){
      //getConsult($value->userid);
      $value->level2 = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$value->userid' AND `active` = 1 AND `email` != ''  ");
      $level2 = $value->level2;
      if(!empty($level2)){
        foreach($level2 as $value2){
          $value2->level3 = $ezDb->get_results("SELECT `email`,`gender`,`userid`,`firstname`,`lastname` FROM `userprofile` WHERE `refid` = '$value2->userid' AND `active` = 1 AND `email` != '' ");
        }
      }
    }
  }
  return $head;
}

function getAMGLine($employeeid=''){
  global $ezDb, $siteName, $dateNow, $dateNow2, $Site;
  $head = $ezDb->get_row("SELECT `email`,`gender`,`userid`,`firstname`,`lastname`,`refid` FROM `userprofile` WHERE `userid` = '$employeeid'");
  $head->level1 = $ezDb->get_row("SELECT `email`,`gender`,`userid`,`firstname`,`lastname`,`refid` FROM `userprofile` WHERE `userid` = '$head->refid' AND `active` = 1 AND `email` != '' ");
  if(!empty($head->level1)){
      $level2 = $head->level1;
      $head->level2 = $ezDb->get_row("SELECT `email`,`gender`,`userid`,`firstname`,`lastname`,`refid` FROM `userprofile` WHERE `userid` = '$level2->refid' AND `active` = 1 AND `email` != ''  ");
  }
  return $head;
}


function get_month_diff($start, $end = FALSE)
{
	$end OR $end = time();

	$start = new DateTime("$start");
	$end   = new DateTime("$end");
	$diff  = $start->diff($end);

	return $diff->format('%y') * 12 + $diff->format('%m');
}

//Log transaction Events
function logTransaction($transid, $user, $amount='', $tablename='', $purpose='', $status){
  global $ezDb, $siteName, $dateNow, $Site;
  $token=getToken(5).$ezDb->get_var("SELECT IFNULL((`id`+1), 1) FROM `transactions` ORDER BY `id` DESC LIMIT 1;");
  $return=$ezDb->query("INSERT INTO `transactions` (`token`,`transid`, `madeby`, `amount`, `tablename`, `purpose`, `status`, `dateadded`) VALUES ('$token','$transid','$user','$amount','$tablename','$purpose','$status','$dateNow')");
}

function logFinance($tbl_token,$amount,$bankaccount,$payment_type,$beneficiary,$category,$sub_category,$details,$description,$transdate,$status){
  global $ezDb;
  $token= getToken(5).$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `finances` ORDER BY `id` DESC LIMIT 1;");
	$financeId = 'fin'.getToken(8);
	$query = "INSERT INTO `finances` (`token`,`tbl_token`, `financeid`, `bankaccount`, `payment`, `beneficiary`, `category`, `sub_category`, `details`, `description`,`amount` ,`transdate`,`status`) VALUES 
												 ('$token','$tbl_token','$financeId','$bankaccount','$payment_type','$beneficiary','$category','$sub_category','$details','$description','$amount','$transdate',$status)";
  if($ezDb->query($query)){
    return true;
  }else{
    return false;
  }
}

function updatelogTransaction($transid, $status){
  global $ezDb, $siteName, $dateNow, $Site;
  $return=$ezDb->query("UPDATE `transactions` SET `status` = '$status' WHERE `transid` = '$transid')");
}

function escape_string($param) {
  if(is_array($param))
      return array_map(__METHOD__, $param);

  if(!empty($param) && is_string($param)) {
      return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $param);
  }

  return $param;
}

function cleanUp($value) {
  $newVal = trim($value);
  $newVal = htmlspecialchars($newVal);
  $newVal = escape_string(stripslashes($newVal));
  return $newVal;
}

function numberTowords($num)
{
  $ones = array(
    0 =>"ZERO",
    1 => "ONE",
    2 => "TWO",
    3 => "THREE",
    4 => "FOUR",
    5 => "FIVE",
    6 => "SIX",
    7 => "SEVEN",
    8 => "EIGHT",
    9 => "NINE",
    10 => "TEN",
    11 => "ELEVEN",
    12 => "TWELVE",
    13 => "THIRTEEN",
    14 => "FOURTEEN",
    15 => "FIFTEEN",
    16 => "SIXTEEN",
    17 => "SEVENTEEN",
    18 => "EIGHTEEN",
    19 => "NINETEEN",
    "014" => "FOURTEEN"
);
$tens = array( 
    0 => "ZERO",
    1 => "TEN",
    2 => "TWENTY",
    3 => "THIRTY", 
    4 => "FORTY", 
    5 => "FIFTY", 
    6 => "SIXTY", 
    7 => "SEVENTY", 
    8 => "EIGHTY", 
    9 => "NINETY" 
); 
$hundreds = array( 
    "HUNDRED", 
    "THOUSAND", 
    "MILLION", 
    "BILLION", 
    "TRILLION", 
    "QUARDRILLION" 
); /*limit t quadrillion */
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr,1); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){
    while(substr($i,0,1)=="0")
        $i=substr($i,1,5);
    if($i < 20){ 
    /* echo "getting:".$i; */
    $rettxt .= $ones[$i]; 
    }elseif($i < 100){ 
        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
    }else{ 
        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0){ 
         $rettxt .= " ".$hundreds[$key]." "; 
       }
}   
if($decnum > 0){
  $rettxt .= " Point ";
  if($decnum < 20){
    $rettxt .= $ones[$decnum];
  }elseif($decnum < 100){
      $rettxt .= $tens[substr($decnum,0,1)];  
      $rettxt .= " ".$ones[substr($decnum,1,1)];
  }
}
return $rettxt;
}