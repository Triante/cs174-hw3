<?php
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;

class Controller
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$model = new MODEL\Model();
	}
	
	public function manage($code)
	{
		$data = manageRead($code);
		
		if (!isEmpty($data))
		{
			switch($data['type'])
			{
				case "edit":
				manageEdit($code, "insert");
				$view = new VIEW\EditSheetView();
				break;
				case "read":
				$view = new VIEW\ReadSheetView();
				break;
				case "file":
				break;
				default:
				
			}
		}
	}
	
	private function manageRead($code)
	{
		return $model.read($code);
	}
	
	private function manageEdit($code, $type)
	{
		$model.update($code, $type);
		
	}
}