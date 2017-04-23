<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4;

require_once('Config.php');
/**
  Script to create and initialize the database for Note-A-List
*/
$hostname = Config::host.":".Config::port;

//creates a mySQL connection
$db = new \mysqli($hostname, Config::user, Config::password);

//checks if the host cannot connect to the mySQL database and if so stops the script
if ($db->connect_error)
{
	die('Could not connect to the database: ');
}

echo "connection success\n";

//string query to create the database for Note-A-List
$dbcreate = 'CREATE DATABASE IF NOT EXISTS '.Config::db;

//checks if creating the Note-A-List database was successful
if ($db->query($dbcreate) === true)
{
	echo Config::db." created\n";
    $db->select_db(Config::db);
}
//gives a error message if there was an error creating the database and stops the script
else
{
	echo "Database could not be created: ".$db->error."\n";
  die;
}

/**
  array to hold all the queries to create and initalize the database
    1. creates table list(list_id, list_name)
    2. creates table list_relationship(parent_id, list_id)
    3. creates table note(note_id, note_name, note_date, note_description)
    4. inserts Note-A_list as first list to set as list_id = 1 to initiate as root list
*/
$dbtables = [];

//for loop to run each query in the array $dbtables
foreach ($dbtables as $query) {
    // success message if query runs correctly
    if ($db->query($query))
    {
        echo $query." created\n\n";
    }
    // gives error message if query did not run successfully and stops the script
    else
    {
        echo "Table could not be created: ".$db->error."\n";
        die;
    }
}
//closes the database connection
$db->close();




