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
		$view = new VIEW\View();
	}
	
	public function manage($code)
	{
		//code for manage function
	}
}