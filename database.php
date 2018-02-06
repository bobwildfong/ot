<?php

$kfdb = new KeyframeDatabase( "localhost", "ot", "ot" );

if( !($kfdb && $kfdb->Connect( "ot" )) ) {
    die( "Cannot connect to database<br/><br/>You probably have to execute these two MySQL commands<br/>"
        ."CREATE DATABASE ot;<br/>GRANT ALL ON ot.* to 'ot'@'localhost' IDENTIFIED BY 'ot'" );
}

if( !$kfdb->Query1( "SELECT count(*) FROM ot.clients" ) ) {

    // Assume the tables have not been created yet

    echo "Creating the database tables";

    $kfdb->SetDebug(2);
    $kfdb->Execute( "CREATE TABLE ot.clients (
        _key        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        _created    DATETIME,
        _created_by INTEGER,
        _updated    DATETIME,
        _updated_by INTEGER,
        _status     INTEGER DEFAULT 0,

        client_name VARCHAR(200) NOT NULL DEFAULT '',
        fav_colour  VARCHAR(200) NOT NULL DEFAULT '')" );

    $kfdb->Execute( "INSERT INTO ot.clients (_key,client_name,fav_colour) values (null,'Eric','blue')" );
    $kfdb->Execute( "INSERT INTO ot.clients (_key,client_name,fav_colour) values (null,'Joe','red')" );

    $kfdb->Execute( "CREATE TABLE ot.professionals (
        _key        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        _created    DATETIME,
        _created_by INTEGER,
        _updated    DATETIME,
        _updated_by INTEGER,
        _status     INTEGER DEFAULT 0,

        pro_name VARCHAR(200) NOT NULL DEFAULT '',
        pro_role VARCHAR(200) NOT NULL DEFAULT '',
        fav_colour        VARCHAR(200) NOT NULL DEFAULT '')" );

    $kfdb->Execute( "INSERT INTO ot.professionals (_key,pro_name,pro_role,fav_colour) values (null,'Jose','Dentist','blue')" );
    $kfdb->Execute( "INSERT INTO ot.professionals (_key,pro_name,pro_role,fav_colour) values (null,'Darth Vader','Surgeon','red')" );

    $kfdb->SetDebug(0);
}


function GetClients( $kfdb )
{
    $raClients = array();

    if( ($dbc = $kfdb->CursorOpen( "SELECT * FROM ot.clients" )) ) {
        while( ($ra = $kfdb->CursorFetch( $dbc )) ) {
            $raClients[] = $ra;
        }
    }

    return( $raClients );
}

function GetProfessionals( $kfdb )
{
    $raPros = array();

    if( ($dbc = $kfdb->CursorOpen( "SELECT * FROM ot.professionals" )) ) {
        while( ($ra = $kfdb->CursorFetch( $dbc )) ) {
            $raPros[] = $ra;
        }
    }

    return( $raPros );
}


?>