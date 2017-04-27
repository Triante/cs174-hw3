<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;

/**
*	ApiController class for handling data passing from the views to the database
*/
class ApiController
{
	private $model;
	private $view;
	private static $hasher;
	private $user;

	/**
	*	Contructor for ApiController, handles passing data from the view to the model when inserting a new web sheet
	*	or updating an existing web sheet in the background.
	*	@param String $ip (The IP of the user navigating through WebSheets)
	*/
	public function __construct($ip)
	{
		$this->model = new MODEL\Model();
		self::$hasher = random_int(0,100000000);
		$this->user = $ip;
	}

	/**
	*	Calls the Model to update a websheet with content provided
	*	@param array $data (Array with the variables needed to load a web sheet and page
	*					Array contents:	$data['id'] the id of the sheet to be updated, not its hashcode
	*								$data['json'] the new data for the update)
	*	@param String $operation (the type of update to be called, should 'update' to update the database)
	*/
	public function update($data, $operation)
	{
		$ip = $this->user;
		$this->model->update($data, $operation);
		$this->monologWrite($this->user.": editted the sheet ".$data["id"]);
	}

	/**
	*	Creates an emplty websheet and generates its 3 different hashes, then calls the Model to insert it in the database 
		with the content provided
	*	@param Array $name (the name of the new web sheet to be created and added to the database)
	*/
	public function insert($name)
	{
		$ip = $this->user;
		self::$hasher = random_int(0,100000000);
		while (true)
		{
			//generates 8 character hash using md5 to be used for the edit code
			$edithash = substr(md5(self::$hasher.$name."e"), 0, 8);
			//checks if the newly generated edit hash code exists in the database already, if it exists, redo the loop until a new hash is found
			if ($this->model->checkIfExists($edithash))
			{
				continue;
			}
			//generates 8 character hash using md5 to be used for the read code
			$readhash = substr(md5(self::$hasher.$name."r"), 0, 8);
			//checks if the newly generated read hash code exists in the database already, if it exists, redo the loop until a new hash is found
			if ($this->model->checkIfExists($readhash))
			{
				continue;
			}
			//generates 8 character hash using md5 to be used for the file code
			$filehash = substr(md5(self::$hasher.$name."f"), 0, 8);
			//checks if the newly generated file hash code exists in the database already, if it exists, redo the loop until a new hash is found
			if ($this->model->checkIfExists($filehash))
			{
				continue;
			}
			//once all three hashes are made, exit loop
			break;
		}
		//new 2x2 websheet data
		$json = json_encode([["",""],["",""]]);
		$data = ["title"=>$name, "json"=>$json, "codeE"=>$edithash, "codeR"=>$readhash, "codeF"=>$filehash];
		//call Model to insert new websheet into databse
		$this->model->update($data, "insert");
		$this->monologWrite($this->user.": created new sheet ".$data["title"]);
	}

	/**
	*	helper function to call log user activity in WebSheets using Monolog
	*/
	private function monologWrite($message)
	{
		// create a log channel
		$log = new Logger('activity');
		$log->pushHandler(new StreamHandler('app_data/spread.log', Logger::INFO));

		// add records to the log
		$log->info($message);
	}
}