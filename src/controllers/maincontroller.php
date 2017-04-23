<?php
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;

class MainController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$model = new MODEL\Model();
	}
	
	public function view($code)
	{
		$data = $model.read($code);
		
		if (!isEmpty($data))
		{
			switch($data['type'])
			{
				case "read":
				$view = new VIEW\ReadSheetView();
				break;
				case "file":
				break;
				default:
				
			}
		}
	}
	
}