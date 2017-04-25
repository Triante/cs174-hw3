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
        //echo '<script>console.log("Enters Model Construct")</script>';

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
   			$this->readWithName($data);
		}
		else {
			$len = strlen($data);
			if ($len == 8) {
				$result = $this->readWithCode($data);
				if (empty($result)) {
					$result = $this->readWithName($data);
				}
				return $result;
			}
			else {
				$result = $this->readWithName($data);
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
            $codeR = "";
            $codeE = "";
            $codeF = "";
            if ($codeType == "r") {
                $codeR = $data;
                $queryF = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'f';";
                $queryE = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'e';";
                $dbquery = self::$db->query($queryF);
                $obj = $dbquery->fetch_object();
                $codeF = $obj->hash_code;
                $dbquery = self::$db->query($queryE);
                $obj = $dbquery->fetch_object();
                $codeE = $obj->hash_code;;
            }
            else if ($codeType == "e") {
                $codeE = $data;
                $queryF = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'f';";
                $queryR = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'r';";
                $dbquery = self::$db->query($queryF);
                $obj = $dbquery->fetch_object();
                $codeF = $obj->hash_code;
                $dbquery = self::$db->query($queryR);
                $obj = $dbquery->fetch_object();
                $codeR = $obj->hash_code;;
            }
            # else if -> $codeType == "f"
            else {
                $codeF = $data;
                $queryR = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'r';";
                $queryE = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'e';";
                $dbquery = self::$db->query($queryR);
                $obj = $dbquery->fetch_object();
                $codeR = $obj->hash_code;
                $dbquery = self::$db->query($queryE);
                $obj = $dbquery->fetch_object();
                $codeE = $obj->hash_code;;
            }
			$query = "SELECT `sheet_name`, `sheet_data` FROM `sheet` WHERE '".$sheetId."'= `sheet_id`;";
			if ($dbquery = self::$db->query($query)) {
				$obj = $dbquery->fetch_object();
				$sheetName = $obj->sheet_name;
				$sheetData = $obj->sheet_data;
				$datasheet = ["title" => $sheetName, "json" => $sheetData, "id" => $sheetId, "type" => $codeType, "codeR" => $codeR, "codeE" => $codeE, "codeF" => $codeF];
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
            $codeR = "";
            $codeE = "";
            $codeF = "";
			$query = "SELECT `code_type`, `hash_code` FROM `sheet_codes` WHERE '".$sheetId."'= `sheet_id`;";
			if ($dbquery = self::$db->query($query)) {
				while($obj = $dbquery->fetch_object()) {
                    $type = $obj->code_type;
                    if ($type == "e") {
                        $codeE = $obj->hash_code;
                    }
                    if ($type == "r") {
                        $codeR = $obj->hash_code;
                    }
                    if ($type == "f") {
                        $codeF = $obj->hash_code;
                    }
                }
				$datasheet = ["title" => $data, "json" => $sheetData, "id" => $sheetId, "type" => "e", "codeR" => $codeR, "codeE" => $codeE, "codeF" => $codeF];
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
        echo '<script>console.log("enter update function")</script>';
		switch ($operation) {
            case 'update':
                echo '<script>console.log("enter update case")</script>';
                $json = $data["json"];
                $sheetID = $data["id"];
                $quarySheet = "UPDATE `sheet` SET `sheet_data` = '".$json."' WHERE `sheet_id` = '".$sheetID."';";
                echo '<div>'.$quarySheet.'</div>';
                if ($dbquery = self::$db->query($quarySheet)) {
                    echo '<script>console.log("successfull save")</script>';
                    return true;
                }
                else {
                    echo '<script>console.log("db error")</script>';
                }
                break;
            case 'insert':
                $title = $data["title"];
                $json = $data["json"];
                $codeR = $data["codeR"];
                $codeE = $data["codeE"];
                $codeF = $data["codeF"];
                $quaryInsert = "INSERT INTO `sheet`(`sheet_name`, `sheet_data`) VALUES ('".$title."', '".$json."');";
                if ($dbquery = self::$db->query($quaryInsert)) {
                    $queryID = "SELECT max(`sheet_id`) AS `sheet_id` FROM `sheet`";
                    if ($dbquery = self::$db->query($queryID)) {
                        if ($obj = $dbquery->fetch_object()) {
							$id = $obj->sheet_id;
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
            echo $this->db->error;
            return false;
        }
	}

    public function checkIfExists($hashCode) {
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