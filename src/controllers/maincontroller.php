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
		if (isset($code['page']))
		{
			switch($code['page'])
			{
				case "home":
					$this->home();
					break;
				case "read":
					$data = $this->model->read($code['sheet']);
					if (!empty($data))
					{
						switch($data['type'])
						{
							case "read":
								$this->sheetView($data);
							break;
							case "edit":
							break;
							case "file":
							break;
							default:

						}
					}
					else {

					}
				break;
				default:
			}
		}
		else
		{

		}
		$data = $model.read($code);

	}

	private function home()
	{
		$view = new VIEW\LandingView();
		$view->render();
	}

	private function sheetView($data)
	{
		$view = new VIEW\ReadSheetView();
	}

	private function xmlView($data)
	{

	}


	private function editView($data)
	{

	}

}