<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views\layouts;

class Header
{
    private $hasScript;
    private $scriptLoc;

    function __construct($withJavaScript, $scriptLocation) {
        $this->hasScript = $withJavaScript;
        $this->scriptLoc = $scriptLocation;
    }

    /**
    *   Renders the header of the application by writing the doctype, head, and opening body tags of the application's XHTML5 document
    *   @param String $data (the current date to be used as part of the pages title)
    */
    function render($type)
    {
		if ($type == "xml")
		{
			header('Content-Type: text/xml');
		}
		else
		{
				$styleDir = 'src/styles/styles.css';
			?>
				<!doctype html>
				<html>
					<head>
						<title>CS174 HW4 Datasheets</title>
						<?php
							if ($this->hasScript) {
								?><script type="text/javascript" src="<?= $this->scriptLoc ?>"></script><?php
							}
						?>

					</head>
			<?php
		}
    }
}