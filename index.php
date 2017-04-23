<?php
/**
* @author Jorge Aguiniga, Luis Otero
*   This is a applicationthat allows users to post categorized messages and classified listings.
*/
namespace jorgeandco\hw4;

require_once('src//views//LandingView.php');
use jorgeandco\hw4\views as CTV;

$class = new CTV\LandingView();
$class->render();