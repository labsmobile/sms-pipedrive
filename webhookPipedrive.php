<?php 
//echo "Hello!! Webhook";
//The file_get_contents() function is used in order to obtain the endpoint data
$data = file_get_contents('php://input');
//A variable is assigned to store the object
$activity = json_decode($data);
//print_r($activity);

//Declare the variables that will be used in the body of the message.
$person_id = $activity->current->person_id; //<-- Variable responsible for storing the person's ID
$activiy_id = $activity->current->id;       //<-- Variable in charge of storing the ID of the activity
$type_name =  $activity->current->type_name;//<-- Variable responsible for storing the type of activity
$message =  $activity->current->note;       //<-- Variable responsible for storing message content


//declare the variables to pass them to the Pipedrive API. In this case, the data of a person with person_id and the update of an activity will be consulted.
$token = 'xxxxxxxxxxxxxxxxx'; // <-- Pipedrive API token must be indicated
$urlGetPer = "https://labsmobile.pipedrive.com/v1/persons/{$person_id}?api_token={$token}";     // <-- Variable in charge of storing the URL to consult the person with the ID
$urlPutAct = "https://labsmobile.pipedrive.com/v1/activities/{$activiy_id}?api_token={$token}"; // <-- Variable responsible for storing the URL to update the activity

//If to filter the type of notification
if ($type_name === "SMS") {
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $urlGetPer,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json',
      'Cookie: __cf_bm=.9mreT2e1G8rsIlVskv.krQekNrwZu42QQQUWwznc.4-1677780370-0-AZhfOhg8WW2y4zNrToKDkIjKX+niAnJwJgXTcdzpq2EykUIE50BxgGytmNLT87m15/w6IkgMWwuBXnPHIHwMJwA='
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  //echo $response;

  //The object of the person's phone numbers is obtained
  $person = json_decode($response);
  $arrayMobile = $person->data->phone;
  //var_dump(count($arrayMobile));

  //A new array is iterated to pass it in the body of the message
  for ($i=0; $i < count($arrayMobile) ; $i++) { 
    $mobile[] = array('msisdn' => $arrayMobile[$i]->value);
  }
  //var_dump($mobile);
  
  //The body of the message is created with the different parameters
  $body = array(
    "message"=> strip_tags($message),
    "tpoa"=> "Sender",
    "recipient"=> $mobile
  );
  //print_r($body);

  //The call will be made to the LabsMobile API for sending
  $auth_basic = base64_encode("myusername:mytoken"); //<-- The username and password or username and token of the LasbsMobile account must be indicated
  $curl = curl_init();


  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.labsmobile.com/json/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($body),
    CURLOPT_HTTPHEADER => array(
      "Authorization: Basic ".$auth_basic,
      "Cache-Control: no-cache",
      "Content-Type: application/json"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }

  //Get the status of the message
  $statusSMS = json_decode($response);
  $notificacion = $statusSMS->message;
  $notificacion = array('subject' => $notificacion);

  //Activity update in pipedrive.
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $urlPutAct,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => json_encode($notificacion),
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Accept: application/json',
      'Cookie: __cf_bm=YorCMpO5qrVSPbRXBXCEKl.FMo91nDqvtyjFtSs.t4M-1677869663-0-AZ8frpI/7I1FUlZlYoicImhiIefq3pMoFZ02c4oj1Jcbvn5cS8AMvbuX6vFDLdyP5ZU+WXfBl3HVPTEQbQjtsD0='
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  //echo $response;

}else{
  echo "Se esta enviando una actividad que no es un SMS: ".$type_name;
}

?>