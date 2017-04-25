<?php
/**
* @author Jorge Aguiniga, Luis Otero
*   This is a applicationthat allows users to post categorized messages and classified listings.
*/
namespace jorgeandco\hw4;

require_once('vendor//autoload.php');
use jorgeandco\hw4\controllers as CTV;


if(isset($_REQUEST['arg1']))
{
	$class = new CTV\MainController();
	$method = "view";
	$class->$method(["page" => "read", "sheet" => $_REQUEST['arg1']]);
}
else if (!isset($_REQUEST['c']) || !isset($_REQUEST['m']))
{
	$class = new CTV\MainController();
	$method = "view";
	$class->$method(["page"=>"home"]);
}
else if(isset($_REQUEST['m']) && isset($_REQUEST['c']))
{
	if (isset($_POST['json']) && isset($_POST['id']))
	{
		$method = $_REQUEST['m'];
		if($method == "update")
		{
			$data = ['json'=>$_POST["json"], 'id'=>$_POST["id"]];
			$class = new CTV\ApiController();
			$class->$method($data);
		}
		else
		{
			Header("Location: index.php?");
		}
	}
	else if(isset($_POST['name']))
	{
		$method = $_REQUEST["m"];
		if($method == "insert")
		{
			$data = ['name'=>$_POST["name"]];
			$class = new CTV\ApiController();
			$class->$method($data);
		}
		else
		{
			Header("Location: index.php?");
		}
	}
	else
	{
		
		$class = new CTV\MainController();
		$method = "view";
		$class->$method(["page"=>"home"]);
		
	}
}

