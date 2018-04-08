<?php

include( SEEDROOT."seedlib/SEEDGoogleService.php" );

class Calendar
{
    private $sess;

    function __construct( SEEDSessionAccount $sess )
    {
        $this->sess = $sess;
    }

    function DrawCalendar()
    {
        $raGoogleParms = array(
                'application_name' => "Google Calendar API PHP Quickstart",
                // If modifying these scopes, regenerate the credentials at ~/seed_config/calendar-php-quickstart.json
                'scopes' => implode(' ', array( Google_Service_Calendar::CALENDAR_READONLY ) ),
                // Downloaded from the Google API Console
                'client_secret_file' => CATS_CONFIG_DIR."google_client_secret.json",
                // Generated by getcreds.php
                'credentials_file' => CATS_CONFIG_DIR."calendar-php-quickstart.json",
        );

        $oG = new SEEDGoogleService( $raGoogleParms, false );
        if( !$oG->client ) die( "Could not create Google Client" );
        $service = new Google_Service_Calendar($oG->client);

        // Print the next 10 events on the user's calendar.
        $calendarId = '4dfi42qffha2crceil97rfimn0@group.calendar.google.com';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);

        $raEvents = $results->getItems();

        $s = "";

        if( !count($raEvents) ) {
            $s .= "No upcoming events found.";
        } else {
            $s .= "<h3>Upcoming Events</h3>";
            $lastday = "";
            foreach( $raEvents as $event ) {
                if(strtolower($event->getSummary()) != "free" && !$this->sess->CanAdmin('Calendar')){
                    continue;
                }
                $start = $event->start->date;
                if(!$start){
                    $start = substr($event->start->dateTime, 0, strpos($event->start->dateTime, "T"));
                }
                if($start != $lastday){
                    if($lastday != ""){
                        $s .= "</div>";
                    }
                    $s .= "<div class='day'>";
                    $time = new DateTime($start);
                    $s .= "<span class='dayname'>".$time->format("l F jS Y")."</span>";
                    $lastday = $start;
                }
                $s .= $this->DrawEvent($event,$this->sess->CanAdmin('Calendar'));
            }
            $s .= "</div>";
        }

        return( $s );
    }
    
    private function DrawEvent($event, $admin = FALSE){
        if(strtolower($event->getSummary()) != "free" && !$admin){
            return "";
        }
        $s = "";
        $start = $event->start->dateTime;
        $tz = "";
        if( empty($start) ) {
            $start = $event->start->date;
        }
        elseif ($event->start->timeZone) {
            $tz = $event->start->timeZone;
        }
        else{
            $tz = substr($start, -6);
            $start = substr($start, 0,-6);
        }
        $time = new DateTime($start, new DateTimeZone($tz));
        $s .= "<div class='appointment ".(strtolower($event->getSummary()) == "free"?"free":"busy")."'>";
        $s .= "<span class='appt-time'>".$time->format("g:ia")."</span>";
        $s .= ($admin?"<span class='appt-summary'>".$event->getSummary()."</span>":"");
        $s .= "</div>";
        return $s;
    }
    
}

?>