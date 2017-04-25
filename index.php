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
	$method = 'view';
	//$method = manage;
}
if (!isset($_REQUEST['c']) || !isset($_REQUEST['m']))
{
	$class = new CTV\MainController();
	$method = "view";
	$class->$method(["page"=>"read"]);
}
if (isset($_POST["operation"])) {
    if (isset($_POST["json"])) {
        $data = ['json'=>$_POST["json"], 'id'=>$_POST["id"]];
        $class = new CTV\ApiController();
        $method = $_POST["operation"];
        $class->$method($data);
    }
}

