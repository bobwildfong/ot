<?php
include_once "_config.php" ;
include_once SEEDCore."SEEDCore.php" ;
include_once SEEDROOT."Keyframe/KeyframeForm.php" ;
include_once SEEDROOT."Keyframe/KeyframeDB.php" ;
require_once "therapist-clientlist.php" ;
require_once "database.php" ;
if( !($kfdb = new KeyframeDatabase( "localhost", "ot", "ot" )) ||
    !$kfdb->Connect( "ot" ) )
{
    die( "Cannot connect to database<br/><br/>You probably have to execute these two MySQL commands<br/>"
        ."CREATE DATABASE ot;<br/>GRANT ALL ON ot.* to 'ot'@'localhost' IDENTIFIED BY 'ot'" );
}
var_dump($_REQUEST);
$kfdb->SetDebug(1);
if( !isset($dirBootstrap) ) { $dirBootstrap = "./bootstrap3/"; }
if( !isset($dirJQuery) )    { $dirJQuery =    "./jquery/"; }
$s =
"<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">
<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
<style>
.otButton { border:1px solid #aaa; width:80%; text-align:center; padding: 20px 0px;};
</style>
<script>
function createCircle(toStyle, radius, color, textColor) {
	toStyle.style.display = 'flex';
	toStyle.style.height = 2 * radius + 'px';
	toStyle.style.width = toStyle.style.height;
	toStyle.style.justifyContent = 'center';
	toStyle.style.alignItems = 'center';
	toStyle.style.marginBottom = '20px';
    toStyle.style.textAlign = 'center';
	toStyle.style.color = textColor;
	toStyle.style.borderStyle = 'inset outset outset inset';
	toStyle.style.borderColor = color;
	toStyle.style.backgroundColor = color;
	toStyle.style.borderRadius = radius + 'px';
	return true;
}
function run() {
    var x = document.querySelectorAll('a.toCircle');
    for(var y = 0; y < x.length; y++) {
	   var classes = x[y].classList;
	   for(var loop in classes) {
		  if (classes.item(loop).search(/format-\d+-#?[\d\w]+-#?[\d\w]+/) !== -1) {
			 var parse = classes.item(loop).split('-');
			 createCircle(x[y], parse[1], parse[2], parse[3]);
		  }
	   }
    }
}
</script>
</head>
<body>";
$screen = SEEDInput_Str( 'screen' );
if( substr($screen,0,5) == 'admin' ) {
    $s .= drawAdmin();
} else if( substr( $screen, 0, 9 ) == "therapist" ) {
    $s .= drawTherapist( $screen );
} else {
    $s .= drawHome();
}
echo $s
."<script> run(); </script>"
."<script src='https://code.jquery.com/jquery-3.2.1.slim.min.js' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>"
."<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js' integrity='sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q' crossorigin='anonymous'></script>"
."<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'>"
."</script></body></html>";
function drawHome()
{
    $s = "<h2>Home</h2>";
    $s .= "<a href='?screen=therapist' class='toCircle format-100-#b3f0ff-blue'>Therapist</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=admin' class='toCircle format-100-red-black'>Admin</a>";
    return( $s );
}
function drawTherapist( $screen )
{
    global $kfdb;
    $s = "<h2>Therapist</h2>";
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
                ."<a href='?screen=therapist-downloadcustommaterials' class='toCircle format-100-#99ff99-blue'>Download Marketable Materials</a>"
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
                ."<a href='?screen=therapist-clientlist' class='toCircle format-100-#99ff99-blue'>Clients and Providers</a>"
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
    $s = "";
    if(SEEDInput_Str("screen") == "admin-droptable"){
        global $kfdb;
        $kfdb->Execute("drop table ot.clients");
        $kfdb->Execute("drop table ot.clients_pros");
        $kfdb->Execute("drop table ot.professionals");
        $s .= "<div class='alert alert-success'> Oops I miss placed your data</div>";
    }
    $s .= "<h2>Admin</h2>";
    $s .= "<a href='?screen=home' class='toCircle format-100-#99ff99-blue'>Home</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?screen=therapist' class='toCircle format-100-#99ff99-blue'>Therapist</a>"
        ."<a href='?screen=admin-droptable' class='toCircle format-100-#99ff99-blue'>Drop Tables</a>";
        return( $s );
}
?>