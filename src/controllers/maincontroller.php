<?php
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
					$this->monologWrite("User entered landing page");
					$this->home();
					break;
				case "read":
						$data = $this->model->read($code['sheet']);

					if (!empty($data))
					{
						$bleh['title'] = $data['title'];
						$bleh['json'] = $data['json'];
						$bleh['id'] = $data['id'];
						$bleh['type'] = $data['type'];
						$bleh['codeR'] = $data['codeR'];
						$bleh['codeE'] = $data['codeE'];
						$bleh['codeF'] = $data['codeF'];
						switch($bleh['type'])
						{
							case "r":
								$this->monologWrite("User entered read page");
								$this->sheetView($bleh);
							break;
							case "e":
								$this->monologWrite("User entered edit page");
								$this->editView($bleh);
							break;
							case "f":
								$bleh['json'] = json_decode($bleh['json']);
								$this->xmlView($bleh);
							break;
							default:
								
						}
						return false;
					}
					else {
						if (isset($code['create']))
						{
							return true;
						}
						echo "<div>bad Luis</div>";
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
		$this->view = new VIEW\XMLSheetView();
		$this->view->render($data);
	}


	private function editView($data)
	{
		$this->view = new VIEW\EditSheetView();
		$this->view->render($data);
	}
	
	private function monologWrite($message)
	{
		// create a log channel
		$log = new Logger('activity');
		$log->pushHandler(new StreamHandler('app_data/spread.log', Logger::INFO));

		// add records to the log
		$log->info($message);
	}

}