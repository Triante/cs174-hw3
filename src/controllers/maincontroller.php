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
		$this->model = new MODEL\Model();
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
						$code['sheet'] = "test";
						$data = $this->model->read($code['sheet']);

						#$bleh['title'] = "test";
						#$bleh['json'] = '[["10", "3", "=(A1+A1)"], ["Java", "JS", "Javascript"]]';
						$bleh['title'] = $data['title'];
						$bleh['json'] = $data['json'];
						$bleh['id'] = $data['id'];
						$bleh['type'] = $data['type'];
						$bleh['codeR'] = $data['codeR'];
						$bleh['codeE'] = $data['codeE'];
						$bleh['codeF'] = $data['codeF'];

					if (!empty($data))
					{
						switch($bleh['type'])
						{
							case "r":
								$this->sheetView($bleh);
							break;
							case "e":
								echo '<script>console.log("Enters Edit")</script>';
								$this->editView($bleh);
							break;
							case "f":
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

	}

	private function home()
	{
		$this->view = new VIEW\LandingView();
		$this->view->render();
	}

	private function sheetView($data)
	{
		$this->view = new VIEW\ReadSheetView();
		$this->view->render($data);
	}

	private function xmlView($data)
	{

	}


	private function editView($data)
	{
		$this->view = new VIEW\EditSheetView();
		$this->view->render($data);
	}

}