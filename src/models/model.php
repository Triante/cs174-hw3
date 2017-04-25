<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\models;

use jorgeandco\hw4 as CFG;

/**
*	Model class
*	Holds data for the model
*/
class Model
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
		if (preg_match("/\\s/", $data)) {
   			// there are spaces
   			readWithName($data);
		}
		else {
			$len = strlen($data);
			if ($len == 8) {
				$result = readWithCode($data);
				if (empty($result)) {
					$result = readWithName($data);
				}
				return $result;
			}
			else {
				$result = readWithName($data);
				return $result;
			}
		}
	}

	private function readWithCode($data) {
		$query = "SELECT `sheet_id`, `code_type` FROM `sheet_codes` WHERE '".$data."' = `hash_code`;";
		if ($dbquery = self::$db->query($query)) {
			$obj = $dbquery->fetch_object();
			if (empty($obj->sheet_id)) {
				return null;
			}
			$sheetId = $obj->sheet_id;
			$codeType = $obj->code_type;
			$query = "SELECT `sheet_name`, `sheet_data` FROM `sheet` WHERE '".$sheetId."'= `sheet_id`;";
			if ($dbquery = self::$db->query($query)) {
				$obj = $dbquery->fetch_object();
				$sheetName = $obj->sheet_name;
				$sheetData = $obj->sheet_data;
				$datasheet = ["title" => $sheetName, "json" => $sheetData, "id" => $sheetId, "type" => $codeType, "code" => $data];
				return $datasheet;
			}
		}
		return null;
	}

	private function readWithName($data) {
		$query = "SELECT `sheet_id`, `sheet_data` FROM `sheet` WHERE '".$data."' = `sheet_name`;";
		if ($dbquery = self::$db->query($query)) {
			$obj = $dbquery->fetch_object();
			if (empty($obj->sheet_id)) {
				return null;
			}
			$sheetId = $obj->sheet_id;
			$sheetData = $obj->sheet_data;
			$query = "SELECT `code_type`, `hash_code` FROM `sheet_codes` WHERE '".$sheetId."'= `sheet_id`;";
			if ($dbquery = self::$db->query($query)) {
				$obj = $dbquery->fetch_object();
				$codeType = $obj->code_type;
				$hashCode = $obj->hash_code;
				$datasheet = ["title" => $data, "json" => $sheetData, "id" => $sheetId, "type" => $codeType, "code" => $hashCode];
				return $datasheet;
			}
		}
		return null;
	}

	/**
	*	Function to write data to the database
	*	@param array $array (the data to be processed)
	*/
    public function update($data, $operation)
	{
		switch ($operation) {
            case 'update':
                $json = $data["json"];
                $sheetID = $data["id"];
                $quarySheet = "UPDATE `sheet` SET `sheet_data` = `".$json."` WHERE `sheet_data` = '".$sheetID."';";
                if ($dbquery = self::$db->query($quarySheet)) {
                    return true;
                }
                break;
            case 'insert':
                $title = $data["title"];
                $json = $data["json"];
                $codeR = $data["codeR"];
                $codeE = $data["codeE"];
                $codeF = $data["codeF"];
                $quaryInsert = "INSERT INTO `sheet`(`sheet_name`, `sheet_data`) VALUES ('".$title."', '".$json."');";
                if ($dbquery = self::$db->query($quarySheet)) {
                    $queryID = "SELECT max(`sheet_id`) AS `sheet_id` FROM `sheet`";
                    if ($dbquery = self::$db->query($queryID)) {
                        if ($id = $dbquery->fetch_object()) {
                            $queryInsertRead = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeR."', 'r');";
                            $queryInsertEdit = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeE."', 'e');";
                            $queryInsertFile = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeF."', 'f');";
                            if ($dbquery = self::$db->query($queryInsertRead)) { if ($dbquery = self::$db->query($queryInsertEdit)) { if ($dbquery = self::$db->query($queryInsertFile)) {
                                return true;
                             } } }
                        }

                    }
                }
                break;
            default:
                echo "unsuported type";
                return false;
                break;
            $this->db->error;
            return false;
        }
	}

    public function checkIfExist($hashCode) {
        $query = "SELECT `sheet_id` FROM `sheet_codes` WHERE '".$hashCode."' = `hash_code`;";
        if ($dbquery = self::$db->query($query)) {
            $ret = $dbquery->fetch_object();
            if (empty($ret->sheet_id)) {
                return false;
            }
            return true;
        }
        return true;
    }



}