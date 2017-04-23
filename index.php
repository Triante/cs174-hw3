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
	//$class = new CTV\Controller();
	//$method = manage;
}
if (!isset($_REQUEST['c']) || !isset($_REQUEST['m']))
{
	$class = new CTV\MainController();
	$method = "view";
	$class->$method(["page"=>"home"]);
}

