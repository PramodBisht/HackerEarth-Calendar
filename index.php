<?php

   require_once "google-api-php-client/src/Google_Client.php";
   require_once "google-api-php-client/src/contrib/Google_CalendarService.php";
   include 'retrieve.php';
   global $event_end_day;
   date_default_timezone_set('Asia/Calcutta');
   $tday=date('d');
   $tmonth=date('m');
   $tyear=date('y');
   $today=$tyear."-".$tmonth."-".$tday."T00:01:00.000+05:30";
   fetch($today);   //this will prefetch the event already stored in our google calendar from today onwards.
  
  for($i=0;$i<$counter;$i++){
    $string_array[$i]=$_SESSION[$i];
    echo $string_array[$i]."<br/>";
  } 
   session_destroy();
   function add_event($e_name,$location,$event_detail,$es_year,$es_month,$es_day,$el_year,$el_month,$el_day,$es_hour,$es_minute,$el_hour,$el_minute){
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
   $starteventtime=$es_year."-".$es_month."-".$es_day."T".$es_hour.":".$es_minute.":00.000+05:30";
   $endeventtime=$el_year."-".$el_month."-".$el_day."T".$el_hour.":".$el_minute.":00.000+05:30";
   $service = new Google_CalendarService($client);

   $event = new Google_Event();
   $event->setSummary($e_name);
   $event->setLocation($location);
   $event->setDescription($event_detail);
   $start = new Google_EventDateTime();
   //old date and time format '2014-4-22T19:00:00.000+01:00'     '2014-4-25T19:25:00.000+01:00'
   $start->setDateTime($starteventtime);
   $start->setTimeZone('Asia/Kolkata');
   $event->setStart($start);
   $end = new Google_EventDateTime();
   $end->setDateTime($endeventtime);
   $end->setTimeZone('Asia/Kolkata');
   $event->setEnd($end);
   
   $calendar_id = "gu8k601ev6u41q9udo7fc65hb0@group.calendar.google.com";
   
   $new_event = null;
   
   try {
       $new_event = $service->events->insert($calendar_id, $event);
       $new_event_id= $new_event->getId();
   } catch (Google_ServiceException $e) {
       syslog(LOG_ERR, $e->getMessage());
       echo $e->getMessage();
   }
   
   $event = $service->events->get($calendar_id, $new_event->getId());
   
   if ($event != null) {
       echo "Inserted:";
       echo "EventID=".$event->getId();
       echo "Summary=".$event->getSummary();
       echo "Status=".$event->getStatus()."<br/><br/>";
   }
 } //end of addevent fun
   

   $url = 'http://www.hackerearth.com/chrome-extension/events/';
   $JSON = file_get_contents($url);

  $data = json_decode($JSON);
   foreach ($data as $item) {
  
    $event_title=$item->title;


    $whatToStrip = array("?","!",",",";"); // Add what you want to strip in this array
    str_replace($whatToStrip, " ", $event_title);
    $event_description=$item->description."<br/>".$item->url;
    $str=explode(" ",$item->time);
    $str2=explode(":", $str[0]);
    $date=explode(" ",$item->date);
    $event_start_day=$date[1];
    if($date[2]=='Jan'){
     $event_start_month=$event_end_month=1; 
    }else
     if($date[2]=='Feb'){
     $event_start_month=$event_end_month=2; 
    }else
     if($date[2]=='Mar'){
     $event_start_month=$event_end_month=3; 
    }else
     if($date[2]=='Apr'){
     $event_start_month=$event_end_month=4; 
    }else
     if($date[2]=='May'){
     $event_start_month=$event_end_month=5; 
    }else
     if($date[2]=='Jun'){
     $event_start_month=$event_end_month=6; 
    }else
     if($date[2]=='Jul'){
     $event_start_month=$event_end_month=7; 
    }else
     if($date[2]=='Aug'){
     $event_start_month=$event_end_month=8; 
    }else
     if($date[2]=='Sep'){
     $event_start_month=$event_end_month=9; 
    }else
     if($date[2]=='Oct'){
     $event_start_month=$event_end_month=10; 
    }else
     if($date[2]=='Nov'){
     $event_start_month=$event_end_month=11; 
    }else
     if($date[2]=='Dec'){
     $event_start_month=$event_end_month=12; 
    }
    
    $event_start_year=$event_end_year=$date[3];
     $event_end_day=$event_start_day;
    if($str[1]=='PM'){
      if($str2[0]!=12){
      $event_start_hour=$str2[0]+12;
      }
     
      $event_end_hour=($event_start_hour+4)%24;
      if($event_end_hour<12){
        $event_end_day=$event_start_day+1;
      }
    }
    else{
      $event_start_hour=$str2[0];
      $event_end_hour=$event_start_hour+4;
      $event_end_day=$event_start_day;
      
    }
    $event_start_minute=$str2[1];
    $event_end_minute=$str2[1];
    if(in_array($event_title, $string_array)==false){
        add_event($event_title,"New Delhi",$event_description,$event_start_year,$event_start_month,$event_start_day,$event_end_year,$event_end_month,$event_end_day,$event_start_hour,$event_start_minute,$event_end_hour,$event_end_minute);
           }
    

  }
    
  
 
?>