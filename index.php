<?php

include_once( "_config.php" );
include( SEEDCore."SEEDCore.php" );

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
    $s = "<h2>Therapist</h2>";

    switch( $screen ) {
        case "therapist":
        default:
            $s .= "<p>What would you like to do?</p>"
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
    }

    return( $s );
}

function drawAdmin()
{
    $s = "";

    $s = "<h2>Admin</h2>";

    $s .= "<a href='?screen=home'><div class='otButton'>Home</div></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=therapist'><div class='otButton'>Therapist</div></a>";

    return( $s );
}

?>
