<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\controllers;

use jorgeandco\hw4\models as MODEL;
use jorgeandco\hw4\views as VIEW;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
*	MainController class for handling data passing for the model and views
*/
class MainController
{
	private $model;
	private $view;
	private $user;

	/**
	*	Contructor for MainController, handles passing data from the model to either the read, edit, 
	*   or file pages for rendering. Also used to call the landing view to load
	*	@param String $ip (The IP of the user navigating through WebSheets)
	*/
	public function __construct($ip)
	{
		$this->model = new MODEL\Model();
		$this->user = $ip;
	}

	/**
	*	Calls a specific View class to render a view based on the variables in $code
	*	@param array $code (Array with the variables neede to load a web sheet and page
	*				Array contents:	$code['page'] contains the view to be loaded
	*									= 'home': render home
	*									= 'read': render edit, read, file page based on hashcode or name provided in $code['sheet']
	*								$code['sheet'] contains the name or hashcode of websheet to be loaded
	*								$code['create'] contains boolean value used to determine if a new websheet needs to be created)
	*	@return Boolean (true if websheet does not exist and needs to be created, false otherwise)
	*/
	public function view($code)
	{
		$ip = $this->user;
		//cecks of a page is set to be rendered
		if (isset($code['page']))
		{
			//switch case to determin which view to call for page rendering
			switch($code['page'])
			{
				//case home: calls helper method to render the landing view page
				case "home":
					$this->monologWrite("User ".$this->user." entered landing page");
					$this->home();
					break;
				// case read: retrives websheet from the database and calls a helper method to render the read, edit, or file page
				case "read":
					//retrieves websheet data based on name or hashcode provided
					$data = $this->model->read($code['sheet']);
					//if a websheet exists, then render sheet based on its code type (r: read, e: edit, f: file)

					if (!empty($data))
					{
						$bleh['title'] = $data['title'];
						$bleh['json'] = $data['json'];
						$bleh['id'] = $data['id'];
						$bleh['type'] = $data['type'];
						$bleh['codeR'] = $data['codeR'];
						$bleh['codeE'] = $data['codeE'];
						$bleh['codeF'] = $data['codeF'];
						//checks type of page to be loaded based on the type returned (note: if searched by name, 'e' will always be loaded)
						switch($bleh['type'])
						{
							//if type id 'r', calls helper method to render sheet view for read only
							case "r":
								$this->monologWrite($this->user.": entered read page for sheet".$bleh['codeR']);
								$this->sheetView($bleh);
							break;
							//if type id 'e', calls helper method to render sheet view for edit mode
							case "e":
								$this->monologWrite($this->user.": entered edit page for sheet".$bleh['codeE']);
								$this->editView($bleh);
							break;
							//if type id 'f', calls helper method to render file as XML in browser
							case "f":
								$this->monologWrite($this->user.": accessed file ".$bleh['codeF']);
								$bleh['json'] = json_decode($bleh['json']);
								$this->xmlView($bleh);
							break;
							default:

						}
						return false;
					}
					//if a websheet doesnt exist
					else {
						//check if the controller was called for creating a newsheet
						if (isset($code['create']))
						{
							return true;
						}
						return false;
					}
				break;
					return false;
				default:
			}
		}
		else
		{
			return false;
		}

	}

	/**
	*	helper function to call LandingView to render the home page
	*/
	private function home()
	{
		$this->view = new VIEW\LandingView();
		$this->view->render();
	}

	/**
	*	helper function to call ReadSheetView to render the websheet in read only mode
	*/
	private function sheetView($data)
	{
		$this->view = new VIEW\ReadSheetView();
		$this->view->render($data);
	}

	/**
	*	helper function to call XMLSheetView to render the websheet as an XML file in the browser
	*/
	private function xmlView($data)
	{
		$this->view = new VIEW\XMLSheetView();
		$this->view->render($data);
	}

	/**
	*	helper function to call EditSheetView to render the websheet in edit mode
	*/
	private function editView($data)
	{
		$this->view = new VIEW\EditSheetView();
		$this->view->render($data);
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