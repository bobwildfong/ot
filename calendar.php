<?php

include( SEEDROOT."seedlib/SEEDGoogleService.php" );

class Calendar
{
    private $oApp;
    private $sess;  // remove this, use oApp->sess instead

    function __construct( SEEDAppSessionAccount $oApp )
    {
        $this->oApp = $oApp;
        $this->sess = $oApp->sess;  // remove this
    }

    function DrawCalendar()
    {
        $s = "";

        $oGC = new CATS_GoogleCalendar();

        /* Get a list of all the calendars that this user can see
         */
        list($raCalendars,$sCalendarIdPrimary) = $oGC->GetAllMyCalendars();

        /* Get the id of the calendar that we're currently looking at. If there isn't one, use the primary.
         */
        $calendarIdCurrent = $this->sess->SmartGPC( 'calendarIdCurrent' ) ?: $sCalendarIdPrimary;

        /* If the user has booked a free slot, store the booking
         */
        if( ($bookSlot = SEEDInput_Str("bookSlot")) && ($sSummary = SEEDInput_Str("bookingSumary")) ) {
            $oGC->BookSlot( $calendarIdCurrent, $bookSlot, $sSummary );
            echo("<head><meta http-equiv=\"refresh\" content=\"0; URL=".CATSDIR."\"></head><body><a href=".CATSDIR."\"\">Redirectn</a></body>");
            die();
        }

        /* Show the list of calendars so we can choose which one to look at
         * The current calendar will be selected in the list.
         */
        $oForm = new SEEDCoreForm('Plain');

        $s .= "<form method='post'>"
             .$oForm->Select( 'calendarIdCurrent', $raCalendars, "Calendar",
                              array( 'selected' => $calendarIdCurrent, 'attrs' => "onchange='submit();'" ) )
             ."</form>";


        // Yes, php can do this and a lot of other cool natural-language dates
        // The idea is to get the dates of the monday-sunday that contain the current time. Usually, the sunday can be gotten via
        // 'next sunday' or 'this sunday' but if it happens to be sunday evening right now you can get the next sunday i.e. a two-week
        // span. Instead we get the date of the next monday and subtract an hour to make it sunday.
        // "this monday" means the monday contained within the next 7 days
        $tMonThisWeek = strtotime('last monday');
        $tSunThisWeek = strtotime('this monday')-3600;
        $dMonThisWeek = date('Y-m-d', $tMonThisWeek);
        $dSunThisWeek = date('Y-m-d', $tSunThisWeek);

        $raEvents = $oGC->GetEvents( $calendarIdCurrent, $tMonThisWeek, $tSunThisWeek );

        $oApptDB = new AppointmentsDB( $this->oApp );

        /* Get the list of calendar events from Google
         */
        $sList = "";
        if( !count($raEvents) ) {
            $sList .= "No upcoming events found.";
        } else {
            $sList .= "<h3>Appointments from $dMonThisWeek to $dSunThisWeek</h3>";
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
                        $sList .= "</div>";
                    }
                    $sList .= "<div class='day'>";
                    $time = new DateTime($start);
                    $sList .= "<span class='dayname'>".$time->format("l F jS Y")."</span>";
                    $lastday = $start;
                }
                $sList .= $this->DrawEvent($event,$this->sess->CanAdmin('Calendar'));

                if(!$kfr = $oApptDB->KFRel()->GetRecordFromDB("google_event_id = '".$event->id."'")){
                    $kfr = $oApptDB->KFRel()->CreateRecord();
                    $kfr->SetValue("google_event_id", $event->id);
                    $kfr->SetValue("start_time", substr($event->start->dateTime, 0, 19) );  // yyyy-mm-ddThh:mm:ss is 19 chars long; trim the timezone part
                    $kfr->PutDBRow();
                }

            }
            $sList .= "</div>";
        }




        /* Get the list of appointments known in CATS
         */
        $sAppts = "<h3>CATS appointments</h3>";
        $raAppts = $oApptDB->GetList( "eStatus in ('NEW','REVIEWED')" );
        foreach( $raAppts as $ra ) {
            $eventId = $ra['google_event_id'];
            $eStatus = $ra['eStatus'];
            $startTime = $ra['start_time'];
            $clientId = $ra['fk_clients'];

            // Now look through the $raEvents that you got from google and try to find the google event with the same event id.
            // If the date/time is different (someone changed it it google calendar), give a warning in $sAppts.
            // If the client is not known clientId==0, give a warning in $sAppts.
//this was just temporary; the CATS appointments will be built into the main calendar now
//            $sAppts .= "<div>$startTime : $clientId</div>";
        }

        $s .= "<div class='row'><div class='col-md-6'>$sList</div><div class='col-md-6'>$sAppts</div></div>";

        $s .= "
    <style>
       span.appt-time,span.appt-summary {
	       font-family: 'Roboto', sans-serif;
        }
       .drop-arrow {
	       transition: all 0.2s ease;
	       width: 10px;
	       height: 10px;
	       display: inline;
	       transform: none;
        }
        .collapsed .drop-arrow {
	       transform: rotate(-90deg);
        }
        .appointment {
	       transition: all 0.2s ease;
	       overflow: hidden;
	       border: 1px dotted gray;
	       border-radius: 5px;
	       width: 105px;
	       padding: 2px;
	       background-color: #99ff99;
	       margin-top: 5px;
	       margin-bottom: 5px;
           box-sizing: content-box;
        }
        .collapsed .appointment {
	       height: 0;
	       border: none;
	       padding: 0;
	       margin: 0;
        }
        .day {
	       margin: 2px;
        }
    </style>
    <script>
        var x = document.createElement('img');
        x.src = 'https://cdn1.iconfinder.com/data/icons/pixel-perfect-at-16px-volume-2/16/5001-128.png';
        x.className = 'drop-arrow';
        var z = document.getElementsByClassName('day');
        for(y = 0; y < z.length; y++) {
	       var w = x.cloneNode();
	       z[y].insertBefore(w, z[y].firstChild);
	       w.onclick = rotateMe;
        }
        function rotateMe() {
	       this.parentElement.classList.toggle('collapsed');
        }
        function expand() {
	       var days = document.getElementsByClassName('day');
	       for (var loop = 0; loop < days.length; loop++) {
		   days[loop].classList.remove('collapsed');
	   }
    }
    function collapse() {
	   var days = document.getElementsByClassName('day');
	   for (var loop = 0; loop < days.length; loop++) {
	       days[loop].classList.add('collapsed');
	   }
    }
</script>";

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
        if( !$tz ) $tz = 'America/Toronto';
        $time = new DateTime($start, new DateTimeZone($tz));
        $s .= "<div class='appointment ".(strtolower($event->getSummary()) == "free"?"free":"busy")."'".(strtolower($event->getSummary()) == "free"?$this->bookable($event->id):"").">";
        $s .= "<span class='appt-time'>".$time->format("g:ia")."</span>";
        $s .= ($admin?"<span class='appt-summary'>".$event->getSummary()."</span>":"");
        $s .= "</div>";
        return $s;
    }

    private function bookable($id){
        $s = " onclick=\"";
        $s .= "";
        $s .= "window.location='?bookSlot=$id&bookingSumary=";
        $s .= "' + prompt('Who is this appointment for?');";
        $s .= "\"";
        return $s;
    }
}


class CATS_GoogleCalendar
{
    var $service;

    function __construct()
    {
        $raGoogleParms = array(
                'application_name' => "Google Calendar for CATS",
                // If modifying these scopes, regenerate the credentials at ~/seed_config/calendar-php-quickstart.json
//                'scopes' => implode(' ', array( Google_Service_Calendar::CALENDAR_READONLY ) ),
                'scopes' => implode(' ', array( Google_Service_Calendar::CALENDAR ) ),
                // Downloaded from the Google API Console
                'client_secret_file' => CATS_CONFIG_DIR."google_client_secret.json",
                // Generated by getcreds.php
                'credentials_file' => CATS_CONFIG_DIR."calendar-php-quickstart.json",
        );

        $oG = new SEEDGoogleService( $raGoogleParms, false );
        $oG->GetClient();
        $this->service = new Google_Service_Calendar($oG->client);
    }


    function GetAllMyCalendars()
    {
        $raCalendars = array();
        $sCalendarIdPrimary = "";

        if( !$this->service ) goto done;

        $opts = array();
        // calendars are paged; pageToken is not specified on the first time through, then nextPageToken is specified as long as it exists
        while( ($calendarList = $this->service->calendarList->listCalendarList( $opts )) ) {
            foreach ($calendarList->getItems() as $calendarListEntry) {
                $raCalendars[$calendarListEntry->getSummary()] = $calendarListEntry->getId();
                if( $calendarListEntry->getPrimary() ) {
                    $sCalendarIdPrimary = $calendarListEntry->getId();
                }
            }
            if( !($opts['pageToken'] = $calendarList->getNextPageToken()) ) {
                break;
            }
        }
        done:
        return( array($raCalendars,$sCalendarIdPrimary) );
    }

    function GetEvents( $calendarId, $startdate, $enddate )
    {
        $optParams = array(
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date("Y-m-d\TH:i:s\Z", $startdate),
            'timeMax' => date("Y-m-d\TH:i:s\Z", $enddate),
        );
        $results = $this->service->events->listEvents($calendarId, $optParams);

        $raEvents = $results->getItems();

        return( $raEvents );
    }

    function BookSlot( $calendarId, $slot, $sSummary )
    {
        if( ($event = $this->service->events->get($calendarId, $slot)) ) {
            $event->setSummary($sSummary);
            $this->service->events->update($calendarId, $event->getId(), $event);
        }
    }

}


?>
