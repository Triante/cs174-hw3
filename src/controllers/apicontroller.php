<?php
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;

class ApiController
{
	private $model;
	private $view;
	private static $hasher;

	public function __construct()
	{
		$this->model = new MODEL\Model();
		self::$hasher = random_int(0,100000000);
	}

	public function update($data, $operation)
	{
		$this->model->update($data, $operation);
	}

	public function insert($name)
	{	
		self::$hasher = random_int(0,100000000);
		while (true)
		{
			$edithash = substr(md5(self::$hasher.$name."e"), 0, 8);
			if ($this->model->checkIfExists($edithash))
			{
				continue;
			}
			$readhash = substr(md5(self::$hasher.$name."r"), 0, 8);
			if ($this->model->checkIfExists($readhash))
			{
				continue;
			}
			$filehash = substr(md5(self::$hasher.$name."f"), 0, 8);
			if ($this->model->checkIfExists($filehash))
			{
				continue;
			}
			break;
		}
		$json = json_encode([["",""],["",""]]);
		$data = ["title"=>$name, "json"=>$json, "codeE"=>$edithash, "codeR"=>$readhash, "codeF"=>$filehash];
		$this->model->update($data, "insert");
	}
}