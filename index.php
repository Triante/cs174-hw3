<?php
/**
* @author Jorge Aguiniga, Luis Otero
*   This is a applicationthat allows users to view and edit web sheets online.
*/
namespace jorgeandco\hw4;

require_once('vendor//autoload.php');
use jorgeandco\hw4\controllers as CTV;

//Fun stuff (IP) to use for logging in Monolog
$ip = "";
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

//redirects the user to a websheet when entered through the URL
if(isset($_REQUEST['arg1']))
{
    //MainController, directs the user either to the either the websheets edit, read, or file based on the sheet code provided in arg1
	$class = new CTV\MainController($ip);
	$method = "view";
	$class->$method(["page" => "read", "sheet" => $_REQUEST['arg1']]);
}
//redirects to landing page if the controller variable are not set
else if (!isset($_REQUEST['c']) || !isset($_REQUEST['m']))
{
    //MainController, directs the user either to the landing page
	$class = new CTV\MainController($ip);
	$method = "view";
	$class->$method(["page"=>"home"]);
}
//used to manipuate web sheet database in the background when an XMLHTTPRequest is issued in the WebSheet edit page
else if(isset($_REQUEST['m']) && isset($_REQUEST['c']))
{
    //used to verify that data was passed for updating a websheet in the database
	if (isset($_POST['json']) && isset($_POST['id']))
	{
		$method = $_REQUEST['m'];
        //used to verify that the the data passed is only being used to update a database
		if($method == "update")
		{
			echo "<script>console.log(".$_POST['json'].");</script>";
			$data = ['json'=>$_POST["json"], 'id'=>$_POST["id"]];
            //ApiController used for  storing data in the database and not redirecting the user to another page
			$class = new CTV\ApiController($ip);
			$class->$method($data, $method);
		}
        //redirects to landing page if 'm' is set improperly
		else
		{
			header("Location: index.php?");
		}
	}
    //used to verify that data was passed for adding a new websheet in the database
	else if(isset($_POST['name']))
	{
		$method = $_REQUEST["m"];
        //redirects the user to the Web Sheet edit page of a newly created web sheet
		if($method == "view")
		{
            //MainController to be used after the web sheet is created in the database
			$class = new CTV\MainController($ip);
			$data = ['sheet'=>$_POST['name'], 'page'=>'read', 'create'=>true];
            //used to verify that the the data passed is only being used to update a database
			if($class->$method($data))
			{
                //ApiController used for creating a new web sheet in the database and not redirecting the user to another page
				$class2 = new CTV\ApiController($ip);
				$method2 = 'insert';
                //inserts new sheet in database
				$class2->$method2($_POST['name']);
                //redirects user to edit page of newly created websheet
				$class->$method($data);
			}
		}
        //redirects to landing page if 'm' is set improperly
		else
		{
			header("Location: index.php?");
		}
	}
    //redirects to landing page if any post variables are set inproperly
	else
	{
        //MainController, directs the user either to the landing page
		$class = new CTV\MainController($ip);
		$method = "view";
		$class->$method(["page"=>"home"]);

	}
}

