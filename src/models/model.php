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
		//creates a mySQL connection with the Web Sheets database
		self::$db = new \mysqli($host, $username, $password, $database);

		//checks if the database connection was successfull, if not echo error message
		if (self::$db->connect_error)
		{
			echo "Could not connect!";
		}
	}

	/**
	*	Function to read data from the database
	*	@param String $data (the name of the datasheet to be retrieved from the database)
    *   @return array $result (an array with the data retrieved from the database, returns null if no data exists)
	*/
    public function read($data)
	{
        //search quary by name if the $data contains any spaces
		if (preg_match("/\\s/", $data)) {
   			return $this->readWithName($data);
		}
		else {
			$len = strlen($data);
            //search quary by hach code if the $data contains is only 8 characters long
			if ($len == 8) {
				$result = $this->readWithCode($data);
                //if quary returns empty results, then search by name
				if (empty($result)) {
					$result = $this->readWithName($data);
				}
				return $result;
			}
            //otherwise search by name
			else {
				$result = $this->readWithName($data);
				return $result;
			}
		}
	}

    /**
    *   helper method to quary the database a hashcode is provided
    *   @param String $data (the hashcode of the datasheet to be retrieved from the database)
    *   @return array $datasheet (an array with the data retrieved from the database, returns null if no data exists
    *                        array contents: ["title"]: the name of the web sheet
    *                                       ["json"]: the data stored in the websheet in JSON format
    *                                       ["id"]: the id of the web sheet
    *                                       ["type"]: the type the hashcode provided was
    *                                                      'e': edit, 'r': read, 'f': file
    *                                       ["codeR"]: the has code for read
    *                                       ["codeE"]: the hash code for edit
    *                                       ["codeF"]: the hash code for the XML file)
    */
	private function readWithCode($data) {
        //quary for selecting sheet_id and code_type provided by the hash_code in $data
		$query = "SELECT `sheet_id`, `code_type` FROM `sheet_codes` WHERE '".$data."' = `hash_code`;";
        //Checks if quary was successfull
		if ($dbquery = self::$db->query($query)) {
			$obj = $dbquery->fetch_object();
            //checks if the quary returned empty results, if so return null
			if (empty($obj->sheet_id)) {
				return null;
			}
			$sheetId = $obj->sheet_id;
			$codeType = $obj->code_type;
            $codeR = "";
            $codeE = "";
            $codeF = "";
            //if hash code was type was r (read), then get hash codes for type e and f
            if ($codeType == "r") {
                $codeR = $data;
                //quary to retrive hascode for type f
                $queryF = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'f';";
                //quary to retrive hascode for type e
                $queryE = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'e';";
                $dbquery = self::$db->query($queryF);
                $obj = $dbquery->fetch_object();
                $codeF = $obj->hash_code;
                $dbquery = self::$db->query($queryE);
                $obj = $dbquery->fetch_object();
                $codeE = $obj->hash_code;;
            }
            //if hash code was type was e (edit), then get hash codes for type r and f
            else if ($codeType == "e") {
                $codeE = $data;
                //quary to retrive hascode for type f
                $queryF = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'f';";
                //quary to retrive hascode for type r
                $queryR = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'r';";
                $dbquery = self::$db->query($queryF);
                $obj = $dbquery->fetch_object();
                $codeF = $obj->hash_code;
                $dbquery = self::$db->query($queryR);
                $obj = $dbquery->fetch_object();
                $codeR = $obj->hash_code;;
            }
            //if hash code was type was f (file), then get hash codes for type e and r
            else {
                $codeF = $data;
                //quary to retrive hascode for type r
                $queryR = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'r';";
                //quary to retrive hascode for type e
                $queryE = "SELECT `hash_code` FROM `sheet_codes` WHERE '".$sheetId."' = `sheet_id` AND `code_type` = 'e';";
                $dbquery = self::$db->query($queryR);
                $obj = $dbquery->fetch_object();
                $codeR = $obj->hash_code;
                $dbquery = self::$db->query($queryE);
                $obj = $dbquery->fetch_object();
                $codeE = $obj->hash_code;;
            }
            //quary to retrive the name and websheet data from the database provided the id that was retrieved from the hashcode quary
			$query = "SELECT `sheet_name`, `sheet_data` FROM `sheet` WHERE '".$sheetId."'= `sheet_id`;";
			if ($dbquery = self::$db->query($query)) {
				$obj = $dbquery->fetch_object();
				$sheetName = $obj->sheet_name;
				$sheetData = $obj->sheet_data;
                //store the quary result data into an array to be sent back
				$datasheet = ["title" => $sheetName, "json" => $sheetData, "id" => $sheetId, "type" => $codeType, "codeR" => $codeR, "codeE" => $codeE, "codeF" => $codeF];
				return $datasheet;
			}
		}
        //null if database quary had an error
		return null;
	}

    /**
    *   helper method to quary the database a hashcode is provided
    *   @param String $data (the name of the datasheet to be retrieved from the database)
    *   @return array $datasheet (an array with the data retrieved from the database, returns null if no data exists
    *                        array contents: ["title"]: the name of the web sheet
    *                                       ["json"]: the data stored in the websheet in JSON format
    *                                       ["id"]: the id of the web sheet
    *                                       ["type"]: defualts to read since search by name will always redirect user to edit web sheet
    *                                       ["codeR"]: the hash code for read
    *                                       ["codeE"]: the hash code for edit
    *                                       ["codeF"]: the hash code for the XML file)
    */
	private function readWithName($data) {
        //quary for selecting sheet_id and code_data provided by the name of the web sheet in $data
		$query = "SELECT `sheet_id`, `sheet_data` FROM `sheet` WHERE '".$data."' = `sheet_name`;";
        //Checks if quary was successfull
		if ($dbquery = self::$db->query($query)) {
			$obj = $dbquery->fetch_object();
            //checks if the quary returned empty results, if so return null
			if (empty($obj->sheet_id)) {
				return null;
			}
			$sheetId = $obj->sheet_id;
			$sheetData = $obj->sheet_data;
            $codeR = "";
            $codeE = "";
            $codeF = "";
            //quary to retrive the hash codes for the web sheet
			$query = "SELECT `code_type`, `hash_code` FROM `sheet_codes` WHERE '".$sheetId."'= `sheet_id`;";
            //loop to get all the hashcode from the quary results
			if ($dbquery = self::$db->query($query)) {
                //loop until all results have been read
				while($obj = $dbquery->fetch_object()) {
                    $type = $obj->code_type;
                    //if type returned was 'e' (edit), then hashcode is for CodeE
                    if ($type == "e") {
                        $codeE = $obj->hash_code;
                    }
                    //if type returned was 'r' (read), then hashcode is for CodeR
                    if ($type == "r") {
                        $codeR = $obj->hash_code;
                    }
                    //if type returned was 'f' (file), then hashcode is for CodeF
                    if ($type == "f") {
                        $codeF = $obj->hash_code;
                    }
                }
                //store the quary result data into an array to be sent back, 'type' defualts to read since search by name always redirects to edit web sheets page
				$datasheet = ["title" => $data, "json" => $sheetData, "id" => $sheetId, "type" => "e", "codeR" => $codeR, "codeE" => $codeE, "codeF" => $codeF];
                return $datasheet;
			}
		}
        //returns null if quary error
		return null;
	}

	/**
	*	Function to write data to the database
	*	@param array $data (the data to be used for updating or inserting into the database)
    *           array contents for 'read' operation:    ["id"]: the id of the sheet to be updated
    *                                                   ["json"]: the web sheet data in JSON format used for the update
    *           array contents for 'insrert' operation: ["title"]: the name of the web sheet to be inserted
    *                                                   ["json"]: the web sheet data in JSON format to be inserted
    *                                                   ["codeR"]: the hash code for read to be inserted
    *                                                   ["codeE"]: the hash code for edit to be inserted
    *                                                   ["codeF"]: the hash code for the XML file to be inserted)
    *   @param String $operation (the type of update to be issued, 'update' to update a webssheet, 'insert' to insert a new web sheet)
    *   @return boolean (True if the quary was successfull, false if not)
	*/
    public function update($data, $operation)
	{
        //switch case to determine whether to update or insert into the databse
		switch ($operation) {
            //case update: update a web sheet to the database with data provided
            case 'update':
                $json = $data["json"];
                $sheetID = $data["id"];
                //quary to update the databse with
                $quarySheet = "UPDATE `sheet` SET `sheet_data` = '".$json."' WHERE `sheet_id` = '".$sheetID."';";
                //check if the quary was succesfull, if so return true
                if ($dbquery = self::$db->query($quarySheet)) {
                    return true;
                }
                break;
            //case insert: insert a new web sheet to the database with data provided
            case 'insert':
                $title = $data["title"];
                $json = $data["json"];
                $codeR = $data["codeR"];
                $codeE = $data["codeE"];
                $codeF = $data["codeF"];
                //quary to insert new web sheet data into the database
                $quaryInsert = "INSERT INTO `sheet`(`sheet_name`, `sheet_data`) VALUES ('".$title."', '".$json."');";
                //check if quary was successfull
                if ($dbquery = self::$db->query($quaryInsert)) {
                    //quary to retrieve ID for new websheet
                    $queryID = "SELECT max(`sheet_id`) AS `sheet_id` FROM `sheet`";
                    //check if quary was successfull
                    if ($dbquery = self::$db->query($queryID)) {
                        if ($obj = $dbquery->fetch_object()) {
							$id = $obj->sheet_id;
                            //quaries to insert the hash codes into the database for the new web sheet
                            $queryInsertRead = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeR."', 'r');";
                            $queryInsertEdit = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeE."', 'e');";
                            $queryInsertFile = "INSERT INTO `sheet_codes`(`sheet_id`, `hash_code`, `code_type`) VALUES ('".$id."', '".$codeF."', 'f');";
                            //check if all 3 inserting hash quaries were successfull, if so return true
                            if ($dbquery = self::$db->query($queryInsertRead)) { if ($dbquery = self::$db->query($queryInsertEdit)) { if ($dbquery = self::$db->query($queryInsertFile)) {
                                return true;
                             } } }
                        }

                    }
                }
                break;
            //case for unsipported type, returns false
            default:
                echo "unsuported type";
                return false;
                break;
            // echo the last quarry error that exited the switch-case statment
            echo $this->db->error;
            //returns false when update or insert fails
            return false;
        }
	}

    /**
    *   method to check if a hash code already exists in the database
    *   @param String $hashcode (the hash code to be checked)
    *   @return true if hash code already exists in databse, false if it does not
    */
    public function checkIfExists($hashCode) {
        //quary to search if hash code exists in database
        $query = "SELECT `sheet_id` FROM `sheet_codes` WHERE '".$hashCode."' = `hash_code`;";
        //checks if quary was successfull
        if ($dbquery = self::$db->query($query)) {
            //fetches for the results of the quary
            $ret = $dbquery->fetch_object();
            //if result is empty, then hash code does not exists, therefore returns false
            if (empty($ret->sheet_id)) {
                return false;
            }
            //retruns true if hash code exists
            return true;
        }
        //retruns true if there was an error in the quary in case of possibility that hash code does exist
        return true;
    }

}