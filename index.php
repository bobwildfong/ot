<?php
require_once "_config.php" ;
require_once "database.php" ;
require_once "cats_ui.php" ;
require_once "therapist-clientlist.php" ;
if( !($kfdb = new KeyframeDatabase( "localhost", "ot", "ot" )) ||
    !$kfdb->Connect( "ot" ) )
{
    die( "Cannot connect to database<br/><br/>You probably have to execute these two MySQL commands<br/>"
        ."CREATE DATABASE ot;<br/>GRANT ALL ON ot.* to 'ot'@'localhost' IDENTIFIED BY 'ot'" );
}
if (!file_exists('pending_resources')) {
    mkdir('pending_resources', 0777, true);
    echo "Pending Resources Directiory Created<br />";
}
if (!file_exists('accepted_resources')) {
    mkdir('accepted_resources', 0777, true);
    echo "Accepted Resources Directiory Created<br />";
}
$kfdb->SetDebug(1);

// check that the tables exist and recreate them if necessary
createTables($kfdb);

$sess = new SEEDSessionAccount( $kfdb, array(), array( 'logfile' => "seedsession.log") );
//var_dump($sess->oDB->GetUserInfo(1));

if( !$sess->IsLogin() ) {
    echo "<form style='margin:auto;border:1px solid gray; width:33%; padding: 10px; border-radius:10px; background-color:#b3f0ff; margin-top:10em;' method='post'>"
         ."<h1 style='text-align:center; font-family: sans-serif'>Login to CATS</h1>"
         ."<input type='text' placeholder='Username' style='display:block; margin:auto; border-radius:5px; border-style: inset outset outset inset; background-color:#99ff99;' name='seedsession_uid' />"
         ."<br />"
         ."<input type='password' placeholder='Password' style='display:block; margin:auto; border-radius:5px; border-style: inset outset outset inset; background-color:#99ff99;' name='seedsession_pwd' />"
         ."<br />"
         ."<input type='submit' value='Login' style='border-style: inset outset outset inset; background-color:#99ff99; border-radius:5px; display:block; margin:auto;' />"
         ."</form>";
    exit;
}

$oUI = new CATS_UI();

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
    global $sess;
    $sess->LogoutSession();
    return("<head><meta http-equiv=\"refresh\" content=\"0; URL=".CATSDIR."\"></head><body>You have Been Logged out<br /><a href=".CATSDIR."\"\">Back to Login</a></body>");
}

function drawHome()
{
    global $oUI,$sess;

    $s = $oUI->Header()."<h2>Home</h2>";
    $s .= ($sess->CanRead('therapist')?"<a href='?screen=therapist' class='toCircle catsCircle1'>Therapist</a>":"").($sess->CanRead('admin')?"<a href='?screen=admin' class='toCircle' data-format='200px red blue'>Admin</a>":"");
    return( $s );
}
function drawTherapist( $screen )
{
    global $kfdb, $sess, $oUI;
    $s = $oUI->Header()."<h2>Therapist</h2>";
    switch( $screen ) {
        case "therapist":
        default:
            $s .= "<p>What would you like to do?</p>"
                ."<div class='container-fluid'>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=home' class='toCircle catsCircle1'>Home</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-materials' class='toCircle catsCircle2'>Print Handouts</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-formscharts' class='toCircle catsCircle1'>Print Forms for Charts</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-linedpapers' class='toCircle catsCircle2'>Print Different Lined Papers</a>"
                ."</div>"
                ."</div>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-entercharts' class='toCircle catsCircle2'>Enter Clients</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-ideas' class='toCircle catsCircle2'>Get Ideas</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-downloadcustommaterials' class='toCircle catsCircle1'>Download Marketable Materials</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-team' class='toCircle catsCircle1'>Meet the Team</a>"
                ."</div>"
                ."</div>"
                ."<div class='row'>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-submitresources' class='toCircle catsCircle2'>Submit Resources to Share</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-clientlist' class='toCircle catsCircle1'>Clients and Providers</a>"
                ."</div>"
                ."<div class='col-md-3'>"
                ."<a href='?screen=therapist-calendar' class='toCircle catsCircle1'>Calendar</a>"
                ."</div>"
                ."</div>"
                ."</div>";
                break;
        case "therapist-materials":
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= "PRINT HANDOUTS";
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
            $s .= "<form action=\"share_resorces_upload.php\" method=\"post\" enctype=\"multipart/form-data\">
                Select resource to upload:
                <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\">
                <br /><input type=\"submit\" value=\"Upload File\" name=\"submit\">
                </form>";
            break;
        case "therapist-clientlist":
            $o = new ClientList( $kfdb );
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= $o->DrawClientList();
            break;
        case "therapist-calendar":
            require_once "calendar.php";
            $o = new Calendar( $sess );
            $s .= "<a href='?screen=therapist' >Therapist</a><br />";
            $s .= $o->DrawCalendar();
    }
    return( $s );
}
function drawAdmin()
{
    global $oUI,$sess;
    $s = "";
    if(SEEDInput_Str("screen") == "admin-droptable"){
        global $kfdb;
        $kfdb->Execute("drop table ot.clients");
        $kfdb->Execute("drop table ot.clients_pros");
        $kfdb->Execute("drop table ot.professionals");
        $kfdb->Execute("drop table ot.SEEDSession_Users");
        $kfdb->Execute("drop table ot.SEEDSession_Groups");
        $kfdb->Execute("drop table ot.SEEDSession_UsersXGroups");
        $kfdb->Execute("drop table ot.SEEDSession_Perms");
        $s .= "<div class='alert alert-success'> Oops I miss placed your data</div>";
    }
    $s .= $oUI->Header()."<h2>Admin</h2>";
    $s .= "<a href='?screen=home' class='toCircle catsCircle2'>Home</a><a href='?screen=therapist' class='toCircle catsCircle2'>Therapist</a>";
    if($sess->CanAdmin("DropTables")){
        $s .= "<button onclick='drop();' class='toCircle catsCircle2'>Drop Tables</button>"
        ."<script>function drop(){
          var password = prompt('Enter the admin password');
          $.ajax({
                url: 'administrator-password.php',
                type: 'POST',
                data: {'password':password},
                cache: 'false',
                success: function(result){
                    location.href = '?screen=admin-droptable';
                },
                error: function(jqXHR, status, error){
                    alert('You are not authorized to preform this action');
                }
          });
          }</script>";
    }
    if($sess->CanWrite("admin")){$s .= "<a href='review_resources.php' class='toCircle catsCircle2'>Reveiw Resources</a>";}
        return( $s );
}
?>