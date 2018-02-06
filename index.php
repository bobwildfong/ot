<?php

include_once( "_config.php" );
include( SEEDCore."SEEDCore.php" );
include( SEEDROOT."Keyframe/KeyframeDB.php" );
include( "database.php" );

if( !isset($dirBootstrap) ) {
    $dirBootstrap = "./bootstrap3/";
}

$s =
"<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='stylesheet' type='text/css' href='".$dirBootstrap."dist/css/bootstrap.min.css'></link>
<script src='".$dirBootstrap."dist/js/bootstrap.min.js'></script>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src='//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
<script src='//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js'></script>
<![endif]-->

<style>
.otButton { border:1px solid #aaa; width:80%; text-align:center; padding: 20px 0px;}
</style>
<script>
function createCircle(element, radius, color) {
try {
var toStyle = document.getElementById(element);
}
catch {
console.log('Invalid element specified for createCircle');
return false;
}
toStyle.style.height = 2 * radius;
toStyle.style.width = toStyle.style.height;
toStyle.style.borderStyle = 'inset outset outset inset';
toStyle.style.borderColor = color;
toStyle.style.backgroundColor = color;
toStyle.style.borderRadius = radius;
return true;
}
</script>
</head>
<body>";


$screen = SEEDInput_Str( 'screen' );

if( $screen == 'admin' ) {
    $s .= drawAdmin();
} else if( substr( $screen, 0, 9 ) == "therapist" ) {
    $s .= drawTherapist( $screen );
} else {
    $s .= drawHome();
}

echo $s
    ."</body></html>";

    

function drawHome()
{
    $s = "";


    $s = "<h2>Home</h2>";

    $s .= "<a href='?screen=therapist'><div class='otButton'>Therapist</div></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin'><div class='otButton'>Admin</div></a>";

    return( $s );
}

function drawTherapist( $screen )
{
    global $kfdb;

    $s = "<h2>Therapist</h2>";

    switch( $screen ) {
        case "therapist":
        default:
            $s .= "<p id='test' onload='createCircle(test, 20px, red);'>What would you like to do?</p>"
                 ."<div class='container-fluid'>"
                     ."<div class='row'>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=home'><div class='otButton'>Home</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-materials'><div class='otButton'>Print Materials</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-formscharts'><div class='otButton'>Print Forms for Charts</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-linedpapers'><div class='otButton'>Print Different Lined Papers</div></a>"
                         ."</div>"
                     ."</div>"
                     ."<div class='row'>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-entercharts'><div class='otButton'>Enter Charts</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-clientRx'><div class='otButton'>Print Client Rx Activities</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-ideas'><div class='otButton'>Get Ideas</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-downloadcustommaterials'><div class='otButton'>Download and Customize Marketable Materials</div></a>"
                         ."</div>"
                     ."</div>"
                     ."<div class='row'>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-team'><div class='otButton'>Meet the Team</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-submitresources'><div class='otButton'>Submit Resources to Share</div></a>"
                         ."</div>"
                         ."<div class='col-md-3'>"
                             ."<a href='?screen=therapist-clientlist'><div class='otButton'>Clients and Providers</div></a>"
                         ."</div>"
                     ."</div>"
                 ."</div>";
            break;
        case "therapist-materials":
            $s .= "PRINT MATERIALS";
            break;
        case "therapist-formscharts":
            $s .= "PRINT FORMS FOR CHARTS";
            break;
        case "therapist-linedpapers":
            $s .= "PRINT DIFFERENT LINED PAPERS";
            break;
        case "therapist-entercharts":
            $s .= "ENTER CHARTS";
            break;
        case "therapist-clientRx":
            $s .= "PRINT CLIENT Rx ACTIVITIES";
            break;
        case "therapist-ideas":
            $s .= "GET IDEAS";
            break;
        case "therapist-downloadcustommaterials":
            $s .= "DOWNLOAD AND CUSTOMIZE MARKETABLE MATERIALS";
            break;
        case "therapist-team":
            $s .= "MEET THE TEAM";
            break;
        case "therapist-submitresources":
            $s .= "SUBMIT RESOURCES";
            break;
        case "therapist-clientlist":
            $s .= drawClientList( $kfdb );
            break;
    }

    return( $s );
}

function drawAdmin()
{
    $s = "";

    $s = "<h2>Admin</h2>";

    $s .= "<a href='?screen=home'><div class='otButton'>Home</div></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=therapist'><div class='otButton'>Therapist</div></a>"
        ."</body>"
        ."</html>";

    return( $s );
}

function drawClientList( KeyframeDatabase $kfdb )
{
    $s = "";

    $raClients = GetClients( $kfdb );
    $raPros = GetProfessionals( $kfdb );

    $s .= "<div class='container-fluid'><div class='row'>"
         ."<div class='col-md-6'>"
             ."<h3>Clients</h3>"
             .SEEDCore_ArrayExpandRows( $raClients, "<div style='padding:5px;'>[[client_name]] likes [[fav_colour]]</div>" )
         ."</div>"
         ."<div class='col-md-6'>"
             ."<h3>Providers</h3>"
             .SEEDCore_ArrayExpandRows( $raPros, "<div style='padding:5px;'>[[pro_name]] is a [[pro_role]] who likes [[fav_colour]]</div>" )
         ."</div>";


    return( $s );
}


?>
