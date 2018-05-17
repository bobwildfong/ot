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
$oApp = new SEEDAppSessionAccount( array(
    'kfdbUserid' => 'ot', 'kfdbPassword' => 'ot', 'kfdbDatabase' => 'ot',
    'sessPermsRequired' => array(),
    'sessParms' => array( 'logfile' => "seedsession.log")
) );



/* The permission level of ajax commands is defined by the format of the command.
 *
 * Commands containing --- are only available to admin users.
 *                      -- are only available to leader users or greater.
 *                       - are only available to therapist users or greater.
 *            (no hyphens) are available to CATS clients.
 */

if( strpos( $cmd, "---" ) !== false && !$oApp->sess->CanRead( 'admin' ) ) {
    $rJX['sErr'] = "Command requires admin permission";
    goto done;
} else
if( strpos( $cmd, "--" ) !== false && !$oApp->sess->CanRead( 'leader' ) ) {
    $rJX['sErr'] = "Command requires leader permission";
    goto done;
} else
if( strpos( $cmd, "-" ) !== false && !$oApp->sess->CanRead( 'therapist' ) ) {
    $rJX['sErr'] = "Command requires therapist permission";
    goto done;
} else {
    // anyone can use this command
}


switch( $cmd ) {
    case 'appt-newform':
        require_once "calendar.php";
        if( ($clientId = @$_POST['cid']) ) {
            $o = new Calendar( $oApp );
            $o->createAppt($_POST);
            $rJX['sOut'] = (new ClientsDB($oApp->kfdb))->getClient($clientId)->Value("client_name");
            $rJX['bOk'] = true;
        } else {
            $rJX['sErr'] = "Unspecified client";
        }
        break;
}



done:

echo json_encode($rJX);

?>