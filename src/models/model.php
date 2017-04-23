<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw3\models;

use jorgeandco\hw3 as CFG;

/**
*	Model class
*	Holds data for the model
*/
abstract class Model
{
	private static $db;

	/**
	*	Constructor for the Model class
	*/
	public function __construct()
	{
		//creates the host for connecting to the database
		$hostname = CFG\Config::host.(':'.CFG\Config::port);

		//if the database has not been connected to yet, connect to it
		if(empty($db))
		{
			self::connect($hostname, CFG\Config::user, CFG\Config::password, CFG\Config::db);
		}

	}

	/**
	*	Connects to a database based on the variables passed
	*	@param String $host (the name of the host where the database is hosted)
	*	@param String $username (the username to connect to the host)
	*	@param String $password (the password to connect to the host)
	*	@param String $database (the name of the database to connect to)
	*/
	private static function connect ($host, $username, $password, $database)
	{
		//creates a mySQL connection with the Note-a-List database
		self::$db = new \mysqli($host, $username, $password, $database);

		//checks if the database connection was successfull, if not echo error message
		if (self::$db->connect_error)
		{
			echo "Could not connect!";
		}
	}
	
	/**
	*	Function to read data from the database
	*	@param array $array (the data to be processed)
	*/
    public function read($data)
	{
		//to do
	}
	
	/**
	*	Function to write data to the database
	*	@param array $array (the data to be processed)
	*/
    public function update($data, $operation)
	{
		//to do
	}
	
	

}