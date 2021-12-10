<?php
global $command;
$theHost = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
$theServer = php_uname('n')."_".$_SERVER['SERVER_ADDR'];
$thisDir = __DIR__;
$scriptBase = $_SERVER['PHP_SELF'];//web name of running script 
$scriptPath = dirname($scriptBase);//web directory of running script
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$protocol="http".((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')?"s":"")."://";

$myGlobals = array(
    'command'=>$command,
    'theHost'=>$theHost,
    'siteProtocol'=>$protocol,
    'theServer'=>$theServer,
    'thePort'=> $_SERVER['SERVER_PORT'],
    'thisDir'=>$thisDir,
    'thisURL'=>$_SERVER['REQUEST_URI'],
    'scriptBase'=>$scriptBase,
    'libraries'=>'lib',
    'include'=>'inc',
    'getURI' => (isset($_GET['url'])? $_GET['url'] : null),
    'requestMethod'=> $_SERVER['REQUEST_METHOD'],
    'site'=>'site',
    'domainName'=>'localhost/organization-management',
    'companyName'=> 'OG-Manager',
    'companyAddress' => 'Cloud Based',
    'media'=>'media',
    'inc'=>'inc',
    'siteName'=>'OG-Manager',
    'templateFolder'=>'base',
    'dashboard'=>'dashboard',
    'admin'=>'root',
    'baselinePath'=>"",
    'dbName'=> 'organization_manager',
    'dbHost'=> 'localhost',
    'dbUser'=> 'root',
    'dbPass'=> '',
    'sitePage'=>'home',
    'dateNow'=> date("Y-m-d H:i:s"),
    'dateNow2'=> date("Y-m-d"),
    'dateY'=> date("Y"),
    'dateM'=> date("F"),
    'startTime'=>strtotime("09:00:59"),
    'nextThursday'=> date("Y-m-d H:i:s", strtotime("next thursday")),
    'nextMonday'=> date("Y-m-d H:i:s", strtotime("next monday")),
    'lastThursday'=> date("Y-m-d H:i:s", strtotime("previous thursday")),
    'lastMonday'=> date("Y-m-d H:i:s", strtotime("previous monday")),
    'today'=> date('l'), 
    'emptyArray'=> array(),
    'membershipSub' => array(
        
    ),
    
    'passwordAuth'=> array('0' => '@[A-Z]@', '1' => '@[0-9]@', '2' => '@[a-z]@', '3' => '@[^\w]@'), 
);
foreach ($myGlobals as $key => $value) {
    $$key = $value;
}


/*System Mail Addres Config*/
$mailConfig= new stdClass;
$mailConfig->developer["email"]=" ";

$mailConfig->contact["email"]=" ";
$mailConfig->contact["title"]=" ";
$mailConfig->contact["subject"]="";
$mailConfig->contact["password"]="";
$mailConfig->signup["email"]=" ";
$mailConfig->signup["title"]="signup@$companyName";
$mailConfig->instructor["email"]=" ";
$mailConfig->instructor["title"]="Conatct Instructor@$companyName";
$mailConfig->instructor["subject"]="";
$mailConfig->instructor["password"]="";
$mailConfig->newsletter["email"]="newsletter@$domainName";
$mailConfig->newsletter["title"]="Newsletter@$companyName";
$mailConfig->newsletter["subject"]="$companyName Newsletter Subscription";
$mailConfig->newsletter["password"]="";
$mailConfig->payment["email"]="payments@$domainName";
$mailConfig->payment["title"]="Payments@$companyName";
$mailConfig->payment["subject"]="$companyName";
$mailConfig->payment["password"]="";
$mailConfig->dropmsg["email"]=" ";
$mailConfig->dropmsg["title"]="Messaging@$companyName";
$mailConfig->dropmsg["subject"]="Message Form $companyName";
$mailConfig->auth["email"]=" ";
$mailConfig->auth["password"]=" ";

/*System Payment Key Config*/
$paymentConfig= new stdClass;
$paymentConfig->paypal_dev["test_sk"]="EAWmvxjA8hC5nleLdPJjhXcaLRjYh_tK312_cwPjSebB-wVtGJpepS9CVKJVSrmgFAtk4x3i6KMNIgtc";//,  
$paymentConfig->paypal_dev["test_pk"]="AclsOoMBhT3moFae6-xTYEEQOkqwmYiTB7ofW-ml-es2df8tbEbjC5U1ckk24iFMhn5sjphgBxx2XlLc"; //, 
$paymentConfig->paypal_dev["live_sk"]="ENFIVCqfjTq4t1_aokJory3CzZYotIOoIn-jkneCPn51Y4eP3RL2hQjHpbb52mCqlIm-ERCSUJB1AgUv";//, 
$paymentConfig->paypal_dev["live_pk"]="AQE4spvPdhciUHIjV-4LxnVZ56Yp_ldJ-wOVUZuDp1Mwglwy7hrStMOadcNgXZkUdYtdRnCm1etBTVP5"; //,  

$paymentConfig->paypal["test_sk"]="EIARGTritNGUyoUJkHAozT147WQHt0ON2ue9GkbDxB0KRpdq5NhHXxFLOoCuDjkwL_MSYX1oDLN03H9h";//,  
$paymentConfig->paypal["test_pk"]="AXS8Q3MvB6ped1wMnJaxtTRVP678b0z6M_pSYjB-u4Xvr22v2by0MYCt-ZgaK2RQa9okBvmGiMU1pDzb"; //, 
$paymentConfig->paypal["live_sk"]="EGImgQlTSkQaGxrdClNggHnV8B4KYwGsMphFFcztMcAItLoz5EduSMOCp6ww9derlpK7lJevNLO7TZSe";//, 
$paymentConfig->paypal["live_pk"]="ARPUQd-a9JezWI0mK20A_HOkcYyUKboJzl8ME01n_M-mSaP2BoFYwIAcrY7cgKaW3P1JQwh90TOS98u-"; //,  

$paymentConfig->paystack["test_sk"]="sk_test_76da07ac2c7a7222879bd30540f681d4399189f5";//"sk_test_12befe597de2e6c195484b6a00e41ab2b5ebfcbf";//"sk_test_12befe597de2e6c195484b6a00e41ab2b5ebfcbf";//"sk_test_5c00c59bbc3a4f0ad0a3b8abd9f20cd6801e9835"; //sk_test_019bfa15ac3eb5b41f9a7f6cfd05e2f92ccc76e3, sk_test_5c00c59bbc3a4f0ad0a3b8abd9f20cd6801e9835
$paymentConfig->paystack["test_pk"]="pk_test_8845baca4e1720d1ecbb60524ff716699cbb439a";//"pk_test_31d1fd54eed88bea9411de151272f5ed4fb72f0f";//"pk_test_31d1fd54eed88bea9411de151272f5ed4fb72f0f";//"pk_test_0b45ef7c842021250468cd5e3e2d4e076cad5ee1"; //pk_test_de2dd33aed79ae537562f4927a76db653a9357f8,pk_test_0b45ef7c842021250468cd5e3e2d4e076cad5ee1
$paymentConfig->paystack["live_pk"]=""; //, 
$paymentConfig->paystack["live_sk"]=""; //, 