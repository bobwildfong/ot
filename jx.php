<?php

/* Entry point for CATS AJAX
 *
 * Input:
 *     cmd = the command to execute
 *
 * Output:
 *     bOk   = true/false; the command was successful
 *     sOut  = text, string, html output for a successful command
 *     raOut = json-ized array of output values for a successful command
 *     sErr  = error message if !bOk
 */

require_once "_config.php" ;
require_once "database.php" ;

//header( "Content-type: application/json" );

$rJX = array( 'bOk' => false,
              'sOut' => "",
              'raOut' => array(),
              'sErr' => "",
);

$cmd = SEEDInput_Str('cmd');

/* Establish secure session here - this is an open entry point where anyone can fake an ajax request with their own url.
 * Just like the main index.php we have to be sure that the request is coming from an authorized user.
 * Fortunately, this ajax request should be coming from the same browser that's running the main CATS program so we will have
 * the same PHP_SESSION here. That means we can just use the same authentication method as index.php
 */


switch( $cmd ) {
    case 'appt-newform':
        require_once "calendar.php";
        $oApp = new SEEDAppSessionAccount( array( 'kfdbUserid' => 'ot',
            'kfdbPassword' => 'ot',
            'kfdbDatabase' => 'ot',
            'sessPermsRequired' => array(),
            'sessParms' => array( 'logfile' => "seedsession.log")
        ) );
        $o = new Calendar( $oApp );
        $o->createAppt($_POST);
        $rJX['sOut'] = "Hello World";
        $rJX['bOk'] = true;
        break;
}



done:

echo json_encode($rJX);

?>