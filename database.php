<?php

require SEEDROOT."Keyframe/KeyframeRelation.php";



class ClientsDB
{
    private $kfrel;
    private $raClients;

    private $kfreldef = array(
        "Tables" => array( "Clients" => array( "Table" => 'ot.clients',
                                               "Fields" => "Auto",
    )));

    function KFRel()  { return( $this->kfrel ); }

    function __construct( KeyframeDatabase $kfdb, $uid = 0 )
    {
        $this->kfrel = new KeyFrame_Relation( $kfdb, $this->kfreldef, $uid );
    }

    function GetClient( $key )
    {
        return( $this->kfrel->GetRecordFromDBKey( $key ) );
    }

    function PutClient( $client_info, $key )
    {
        $ok = false;

        if( $key ) {
            $kfr = $this->kfrel->GetRecordFromDBKey( $key );
        } else {
            $kfr = $this->kfrel->CreateRecord();
        }

        if( $kfr ) {
            foreach( $client_info as $k => $v ) {
                $kfr->SetValue( $k, $v );
            }
            $ok = $kfr->PutDBRow();
        }

        return( $ok );
    }
}



$kfdb = new KeyframeDatabase( "localhost", "ot", "ot" );

if( !($kfdb && $kfdb->Connect( "ot" )) ) {
    die( "Cannot connect to database<br/><br/>You probably have to execute these two MySQL commands<br/>"
        ."CREATE DATABASE ot;<br/>GRANT ALL ON ot.* to 'ot'@'localhost' IDENTIFIED BY 'ot'" );
}

if( !$kfdb->Query1( "SELECT count(*) FROM ot.clients" ) ) {
    createTables($kfdb);
}

$oClientsDB = new ClientsDB( $kfdb );



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

function createTables( KeyframeDatabase $kfdb )
{
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
        parents_name VARCHAR(200) NOT NULL DEFAULT '',
        parents_separate BIT(1) NOT NULL DEFAULT b'0',
        address VARCHAR(200) NOT NULL DEFAULT '',
        city VARCHAR(200) NOT NULL DEFAULT '',
        postal_code VARCHAR(200) NOT NULL DEFAULT '',
        dob VARCHAR(200) NOT NULL DEFAULT '',
        phone_number VARCHAR(200) NOT NULL DEFAULT '',
        email VARCHAR(200) NOT NULL DEFAULT '',
        family_doc VARCHAR(200) NOT NULL DEFAULT '',
        paediatrician VARCHAR(200) NOT NULL DEFAULT '',
        slp VARCHAR(200) NOT NULL DEFAULT '',
        psychologist VARCHAR(200) NOT NULL DEFAULT '',
        referal VARCHAR(500) NOT NULL DEFAULT '',
        background_info VARCHAR(500) NOT NULL DEFAULT '')" );

    $kfdb->Execute( "INSERT INTO ot.clients (_key,client_name) values (null,'Eric')" );
    $kfdb->Execute( "INSERT INTO ot.clients (_key,client_name) values (null,'Joe')" );

    $kfdb->Execute( "CREATE TABLE ot.professionals (
        _key        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
        _created    DATETIME,
        _created_by INTEGER,
        _updated    DATETIME,
        _updated_by INTEGER,
        _status     INTEGER DEFAULT 0,

        pro_name VARCHAR(200) NOT NULL DEFAULT '',
        pro_role VARCHAR(200) NOT NULL DEFAULT '',
        address VARCHAR(200) NOT NULL DEFAULT '',
        city VARCHAR(200) NOT NULL DEFAULT '',
        postal_code VARCHAR(200) NOT NULL DEFAULT '',
        phone_number VARCHAR(200) NOT NULL DEFAULT '',
        fax_number VARCHAR(200) NOT NULL DEFAULT '',
        email VARCHAR(200) NOT NULL DEFAULT '')" );

    $kfdb->Execute( "INSERT INTO ot.professionals (_key,pro_name,pro_role) values (null,'Jose','Dentist')" );
    $kfdb->Execute( "INSERT INTO ot.professionals (_key,pro_name,pro_role) values (null,'Darth Vader','Surgeon')" );

    $kfdb->SetDebug(0);
}

?>