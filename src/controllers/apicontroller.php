<?php
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;

class ApiController
{
	private $model;
	private $view;
	
	public function __construct()
	{
		$model = new MODEL\Model();
	}
	
	public function updateModel($code, $type)
	{
		$model.updateModel($code, $type);
	}
}