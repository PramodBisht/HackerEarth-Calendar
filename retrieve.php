<?php
	 require_once "google-api-php-client/src/Google_Client.php";
   require_once "google-api-php-client/src/contrib/Google_CalendarService.php";
   global $string_array;
   $counter=0;
   function fetch($starttime){
    $client = new Google_Client();
   $client->setUseObjects(true);
   $client->setApplicationName("HE-Cal");
   $client->setClientId("650696064454-fhrff2s8hd6kibn3cjn9ksjm3gleipir.apps.googleusercontent.compact(varname)");
   $client->setAssertionCredentials(new Google_AssertionCredentials(
           "650696064454-fhrff2s8hd6kibn3cjn9ksjm3gleipir@developer.gserviceaccount.com",
           array("https://www.googleapis.com/auth/calendar"),
           file_get_contents("certificates/58dc8debcb492676e29ba7c3d98befbf3ee251de-privatekey.p12")
       )
   );
   
   $service = new Google_CalendarService($client);

   $calendar_id = "gu8k601ev6u41q9udo7fc65hb0@group.calendar.google.com";
   
   $optParams = array( 'timeMin'=>$starttime );
   $events = $service->events->listEvents($calendar_id,$optParams);
    $counter=0;
    session_start();
    while(true) {
      	foreach ($events->getItems() as $event) {
            
            $string_array[$counter]=$event->getSummary();
            // echo $string_array[$counter]."<br/>";
            $counter3=$GLOBALS['counter']++;
             
            $_SESSION[$counter3] = $event->getSummary();
      
      	}
       

        $pageToken = $events->getNextPageToken();
        if ($pageToken) {
          echo "run";
          $events = $service->events->listEvents($calendar_id, $optParams);

        } else {
             break;
      	}
  }

}

?>