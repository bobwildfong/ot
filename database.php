<?php

require_once SEEDROOT."Keyframe/KeyframeRelation.php";

define( "DBNAME", "ot" );

class ClientsDB
{
    private $kfrel;
    private $raClients;

    private $kfreldef = array(
        "Tables" => array( "Clients" => array( "Table" => DBNAME.'.clients',
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

}

class ProsDB
{
    private $kfrel;
    private $raPros;

    private $kfreldef = array(
        "Tables" => array( "Pros" => array( "Table" => DBNAME.'.professionals',
            "Fields" => "Auto",
        )));

    function KFRel()  { return( $this->kfrel ); }

    function __construct( KeyframeDatabase $kfdb, $uid = 0 )
    {
        $this->kfrel = new KeyFrame_Relation( $kfdb, $this->kfreldef, $uid );
    }

    function GetPro( $key )
    {
        return( $this->kfrel->GetRecordFromDBKey( $key ) );
    }
}

class Clients_ProsDB
/*******************
    The connections between clients and professionals
 */
{
    private $kfrel;     // just the clients_pros table
    private $kfrel_X;   // the join of clients X clients_pros X professionals
    private $raPros;

    private $kfreldef = array(
        "Tables" => array( "Pros" => array( "Table" => DBNAME.'.clients_pros',
                                            "Fields" => "Auto",
        )));

    private $kfreldef_X = array(
        "Tables" => array( "Clients" => array( "Table" => DBNAME.'.clients',
                                               "Fields" => "Auto" ),
                           "Pros"    => array( "Table" => DBNAME.'.professionals',
                                               "Fields" => "Auto" ),
                           "CxP"     => array( "Table" => DBNAME.'.clients_pros',
                                               "Fields" => "Auto" )
        ));

    function KFRelBase()  { return( $this->kfrel ); }       // just the base table
    function KFRel()      { return( $this->kfrel_X ); }     // the whole join of three tables

    function __construct( KeyframeDatabase $kfdb, $uid = 0 )
    {
        $this->kfrel   = new KeyFrame_Relation( $kfdb, $this->kfreldef,   $uid );
        $this->kfrel_X = new KeyFrame_Relation( $kfdb, $this->kfreldef_X, $uid );
    }

    function GetClientInfoForProfessional( $pro_key )
    {
        return( $this->kfrel_X->GetRecordFromDB( "Pros._key='$pro_key'" ) );
    }

    function GetProfessionalInfoForClient( $client_key )
    {
        return( $this->kfrel_X->GetRecordFromDB( "Clients._key='$client_key'" ) );
    }
}

function createTables( KeyframeDatabase $kfdb )
{
    if( !tableExists( $kfdb, DBNAME.".clients" ) ) {
        echo "Creating the Client table";

        $kfdb->SetDebug(2);
        $kfdb->Execute( "CREATE TABLE ".DBNAME.".clients (
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
            referal VARCHAR(500) NOT NULL DEFAULT '',
            background_info VARCHAR(500) NOT NULL DEFAULT '')" );

        $kfdb->Execute( "INSERT INTO ".DBNAME.".clients (_key,client_name) values (null,'Eric')" );
        $kfdb->Execute( "INSERT INTO ".DBNAME.".clients (_key,client_name) values (null,'Joe')" );
        $kfdb->SetDebug(0);
    }

    if( !tableExists( $kfdb, DBNAME.".professionals" ) ) {
        echo "Creating the Pros table";

        $kfdb->SetDebug(2);
        $kfdb->Execute( "CREATE TABLE ".DBNAME.".professionals (
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

        $kfdb->Execute( "INSERT INTO ".DBNAME.".professionals (_key,pro_name,pro_role) values (null,'Jose','Dentist')" );
        $kfdb->Execute( "INSERT INTO ".DBNAME.".professionals (_key,pro_name,pro_role) values (null,'Darth Vader','Surgeon')" );
        $kfdb->SetDebug(0);
    }

    if( !tableExists( $kfdb, DBNAME.".clients_pros" ) ) {
        echo "Creating the Client X Pros table";

        $kfdb->SetDebug(2);
        $kfdb->Execute( "CREATE TABLE ".DBNAME.".clients_pros (
            _key        INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
            _created    DATETIME,
            _created_by INTEGER,
            _updated    DATETIME,
            _updated_by INTEGER,
            _status     INTEGER DEFAULT 0,

            # when these foreign keys equal the _key fields of tables 'clients' and 'professionals', it means
            # that client and professional are connected.
            #
            # e.g. if fk_clients==2 and fk_professionals==3 it means the professional with _key==3 is a provider
            #      for the client with _key=2.
            #
            # Notice this means every client can have any number of providers because you can put any
            # number of rows in this table for a particular client.

            fk_clients       INTEGER NOT NULL DEFAULT 0,
            fk_professionals INTEGER NOT NULL DEFAULT 0 )" );

        $kfdb->Execute( "INSERT INTO ".DBNAME.".clients_pros (_key,fk_clients,fk_professionals) values (null,1,1)" );  // Jose is Eric's dentist
        $kfdb->Execute( "INSERT INTO ".DBNAME.".clients_pros (_key,fk_clients,fk_professionals) values (null,2,2)" );  // Darth Vader is Joe's surgeon
        $kfdb->SetDebug(0);
    }
}

function tableExists( KeyframeDatabase $kfdb, $tablename )
{
    return( $kfdb->Query1( "SELECT count(*) FROM $tablename" ) ? true : false );
}

?>