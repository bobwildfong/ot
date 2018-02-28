<?php
include_once "_config.php" ;
require_once "database.php" ;
require_once "cats_ui.php" ;
require_once "therapist-clientlist.php" ;
if( !($kfdb = new KeyframeDatabase( "localhost", "ot", "ot" )) ||
    !$kfdb->Connect( "ot" ) )
{
    die( "Cannot connect to database<br/><br/>You probably have to execute these two MySQL commands<br/>"
        ."CREATE DATABASE ot;<br/>GRANT ALL ON ot.* to 'ot'@'localhost' IDENTIFIED BY 'ot'" );
}
$kfdb->SetDebug(1);

$sess = new SEEDSession();

$oUI = new CATS_UI();


if( ($userid = SEEDInput_Str('userid')) ) {
    $sess->VarSet( 'userid', $userid );
} else {
    $userid = $sess->VarGet( 'userid' );
}
if( !$userid ) {
    echo $oUI->OutputPage( $oUI->Header().$oUI->Login() );
    exit;
}

//var_dump($_REQUEST);
//var_dump($_SESSION);

$s = "";

$screen = SEEDInput_Str( 'screen' );
$oUI->SetScreen($screen == ""?"home":$screen);
if( substr($screen,0,5) == 'admin' ) {
    $s .= drawAdmin();
} else if( substr( $screen, 0, 9 ) == "therapist" ) {
    $s .= drawTherapist( $screen );
} else if($screen == "logout"){
    $s .= drawLogout();
} else {
    $s .= drawHome();
}
echo $oUI->OutputPage( $s );

function  drawLogout(){
    $_SESSION['userid'] = "";
    return("<head><meta http-equiv=\"refresh\" content=\"0; URL=".CATSDIR."\"></head><body>You have Been Logged out<br /><a href=".CATSDIR."\"\">Back to Login</a></body>");
}

function drawHome()
{
    global $oUI;

    $s = $oUI->Header()."<h2>Home</h2>";
    $s .= "<a href='?screen=therapist' class='toCircle format-100-#b3f0ff-blue'>Therapist</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin' class='toCircle format-100-red-black'>Admin</a>";
    return( $s );
}
function drawTherapist( $screen )
{
    global $kfdb, $oUI;
    $s = $oUI->Header()."<h2>Therapist</h2>";
    switch( $screen ) {
        case "therapist":
        default:
            $s .= "<p>What would you like to do?</p>"
                ."<div class='container-fluid'>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=home' class='toCircle format-100-#b3f0ff-blue'>Home</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-materials' class='toCircle format-100-#99ff99-blue'>Print Materials</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-formscharts' class='toCircle format-100-#b3f0ff-blue'>Print Forms for Charts</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-linedpapers' class='toCircle format-100-#99ff99-blue'>Print Different Lined Papers</a>"
                ."</div>"
                ."</div>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-entercharts' class='toCircle format-100-#99ff99-blue'>Enter Charts</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-clientRx' class='toCircle format-100-#b3f0ff-blue'>Print Client Rx Activities</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-ideas' class='toCircle format-100-#99ff99-blue'>Get Ideas</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-downloadcustommaterials' class='toCircle format-100-#b3f0ff-blue'>Download Marketable Materials</a>"
                ."</div>"
                ."</div>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-team' class='toCircle format-100-#b3f0ff-blue'>Meet the Team</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-submitresources' class='toCircle format-100-#99ff99-blue'>Submit Resources to Share</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-clientlist' class='toCircle format-100-#b3f0ff-blue'>Clients and Providers</a>"
                ."</div>"
                ."</div>"
                ."</div>";
                break;
        case "therapist-materials":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "PRINT MATERIALS";
            break;
        case "therapist-formscharts":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "PRINT FORMS FOR CHARTS";
            break;
        case "therapist-linedpapers":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "PRINT DIFFERENT LINED PAPERS";
            break;
        case "therapist-entercharts":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "ENTER CHARTS";
            break;
        case "therapist-clientRx":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "PRINT CLIENT Rx ACTIVITIES";
            break;
        case "therapist-ideas":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "GET IDEAS";
            break;
        case "therapist-downloadcustommaterials":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "DOWNLOAD AND CUSTOMIZE MARKETABLE MATERIALS";
            break;
        case "therapist-team":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "MEET THE TEAM";
            break;
        case "therapist-submitresources":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "SUBMIT RESOURCES";
            break;
        case "therapist-clientlist":
            $o = new ClientList( $kfdb );
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= $o->DrawClientList();
            break;
    }
    return( $s );
}
function drawAdmin()
{
    global $oUI;
    $s = "";
    if(SEEDInput_Str("screen") == "admin-droptable"){
        global $kfdb;
        $kfdb->Execute("drop table ot.clients");
        $kfdb->Execute("drop table ot.clients_pros");
        $kfdb->Execute("drop table ot.professionals");
        $s .= "<div class='alert alert-success'> Oops I miss placed your data</div>";
    }
    $s .= $oUI->Header()."<h2>Admin</h2>";
    $s .= "<a href='?screen=home' class='toCircle format-100-#99ff99-blue'>Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=therapist' class='toCircle format-100-#99ff99-blue'>Therapist</a>"
        ."<button onclick='drop();' class='toCircle format-100-#99ff99-blue'>Drop Tables</button>"
        ."<script>function drop(){
          var password = prompt('Enter the admin password');
          $.ajax({
                url: 'administrator-password.php',
                type: 'POST',
                data: {'password':password},
                cache: 'false',
                success: function(result){
                    location.href('?screen=admin-droptable');
                },
                error: function(jqXHR, status, error){
                    alert('You are not authorized to preform this action');
                }
          });
          }</script>";
        return( $s );
}
?>