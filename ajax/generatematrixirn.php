<?php
ini_set('serialize_precision',-1);
date_default_timezone_set("Asia/Kolkata");
$date = date('d/m/Y');
include('../phpqrcode/qrlib.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$authquery = "select * from inv_einvoiceauth;";
$authfetch = runmysqlqueryfetch($authquery);
$authenticationToken = $authfetch['authenticationtoken'];
$subscriptionId = $authfetch['subscriptionid'];
$authdatetime = $authfetch['authdatetime'];
$tokenExpiry = $authfetch['tokenexpiry'];

$authdquery0 = "select * from inv_einvauthdetails where gstin_code= '".$branch_gst_code."';";
$authfetch0 = runmysqlqueryfetch($authdquery0);
$username = $authfetch0['username'];
$password = $authfetch0['password'];
$gstin = $authfetch0['gstin'];
$gstin_code = $authfetch0['gstin_code'];

$authcurrentdate = date('Y-m-d H:i:s');
//$authdatetime = date('Y-m-d H:i:s',strtotime($authdatetime));
//$tokenExpiry = date('Y-m-d H:i:s',strtotime($tokenExpiry));

if($authcurrentdate > $tokenExpiry)
{
    $result = getauthapicall($authenticationToken,$subscriptionId,$username,$password,$gstin);
    // echo $result; exit;
    if($result == 'Invalid Token')
    {
        if($authcurrentdate > $authdatetime)
        {
            //fisrt API
            $authresult = getauthenticationdata();

            $data = json_decode($authresult);
            $authenticationToken = $data->authenticationToken;
            $subscriptionId = $data->subscriptionId;
            $authenticationValidTillDateTime = date('Y-m-d H:i:s',strtotime($data->authenticationValidTillDateTime));
            $isValid = $data->isValid;

            //second API(Authentication E-Invoice)
            $result = getauthapicall($authenticationToken,$subscriptionId,$username,$password,$gstin);
            
            $einvoicedata = json_decode($result,true);
            $UserName = $einvoicedata['data']['userName'];
            $AuthToken = $einvoicedata['data']['authToken'];
            $sek = $einvoicedata['data']['sek'];
            $tokenExpiry = date('Y-m-d H:i:s',strtotime($einvoicedata['data']['tokenExpiry']));

            $authquery = "update inv_einvoiceauth set updateddate = '".date('Y-m-d').' '.date('H:i:s')."',authenticationtoken = '".$authenticationToken."' ,subscriptionid ='".$subscriptionId."',authdatetime ='".$authenticationValidTillDateTime."',authtoken='".$AuthToken."',sek='".$sek."',tokenexpiry='".$tokenExpiry."'";
            $authresult = runmysqlquery($authquery);

        }
        else
        {
            //second API(Authentication E-Invoice)
            $result = getauthapicall($authenticationToken,$subscriptionId,$username,$password,$gstin);
            
            $einvoicedata = json_decode($result,true);
            $UserName = $einvoicedata['data']['userName'];
            $AuthToken = $einvoicedata['data']['authToken'];
            $sek = $einvoicedata['data']['sek'];
            $tokenExpiry = date('Y-m-d H:i:s',strtotime($einvoicedata['data']['tokenExpiry']));

            $authquery = "update inv_einvoiceauth set updateddate = '".date('Y-m-d').' '.date('H:i:s')."',authtoken='".$AuthToken."',sek='".$sek."',tokenexpiry='".$tokenExpiry."'";
            $authresult = runmysqlquery($authquery);
        }
        
    }
    else
    {
        //echo "valid";
        $einvoicedata = json_decode($result,true);
        $UserName = $einvoicedata['data']['userName'];
        $AuthToken = $einvoicedata['data']['authToken'];
        $sek = $einvoicedata['data']['sek'];
        $tokenExpiry = date('Y-m-d H:i:s',strtotime($einvoicedata['data']['tokenExpiry']));

        $authquery = "update inv_einvoiceauth set updateddate = '".date('Y-m-d').' '.date('H:i:s')."',authtoken='".$AuthToken."',sek='".$sek."',tokenexpiry='".$tokenExpiry."'";
        $authresult = runmysqlquery($authquery);
    }
}
else if($authcurrentdate > $authdatetime)
{
    //fisrt API
    $authresult = getauthenticationdata();

    $data = json_decode($authresult);
    $authenticationToken = $data->authenticationToken;
    $subscriptionId = $data->subscriptionId;
    $authenticationValidTillDateTime = date('Y-m-d H:i:s',strtotime($data->authenticationValidTillDateTime));
    $isValid = $data->isValid;

    //second API(Authentication E-Invoice)
    $result = getauthapicall($authenticationToken,$subscriptionId,$username,$password,$gstin);
    
    $einvoicedata = json_decode($result,true);
    $UserName = $einvoicedata['data']['userName'];
    $AuthToken = $einvoicedata['data']['authToken'];
    $sek = $einvoicedata['data']['sek'];
    $tokenExpiry = date('Y-m-d H:i:s',strtotime($einvoicedata['data']['tokenExpiry']));

    $authquery = "update inv_einvoiceauth set updateddate = '".date('Y-m-d').' '.date('H:i:s')."',authenticationtoken = '".$authenticationToken."' ,subscriptionid ='".$subscriptionId."',authdatetime ='".$authenticationValidTillDateTime."',authtoken='".$AuthToken."',sek='".$sek."',tokenexpiry='".$tokenExpiry."'";
    $authresult = runmysqlquery($authquery);

}
else
{
   $AuthToken = $authfetch['authtoken'];
    $sek = $authfetch['sek'];
   $UserName = $username;
}



function getauthapicall($token,$subid,$username,$password,$gstin)
{
    //second api call(Authentication E-Invoice)
    //Prepare you post parameters
    $postData = array(
        'AuthenticationToken' => $token,
        'SubscriptionId' => $subid,
    );
    $post_data = json_encode($postData);

    $einvoiceurl = "https://demo.saralgsp.com/eivital/v1.04/auth";

    //open connection
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $einvoiceurl);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POST, true);

    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "AuthenticationToken: $token",
        "SubscriptionId: $subid",
        "UserName: $username",
        "Password: $password",
        "Gstin: $gstin",
        "Content-Type: application/json",
        )
    );

    //print_r($post_data); exit;
    //to find the content-length for header we use postdata
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    //execute post
    $apicall = curl_exec($ch);
    
    //Print error if any
    if(curl_errno($ch))
    {
        'error:' . curl_error($ch);
    } 
    curl_close($ch);
    return $apicall;
}

function getauthenticationdata()
{
    //first api call(Authenticate)
    $url = "https://demo.saralgsp.com/authentication/Authenticate";

    // init the resource
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
       "ClientId: 43cc5941-2be5-4e2f-9a09-2536f0e29fbf",
       "ClientSecret: Fsx2IecwjFtWEsk0fxqEKs18jMjCN62v",
    );

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}
?>