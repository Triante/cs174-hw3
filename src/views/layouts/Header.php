<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views\layouts;

/**
* Header class for writing the opening tags for a validating XHTML5 document or setting the header of an XML document
*/
class Header
{
    private $hasScript;
    private $scriptLoc;

    /**
    *   Contructor for Header class
    *   @param boolean $withJavaScript (true if htnl needs to render provided scrip)
    *   @param String $scriptLocation (the location of the script to be loaded along with header)
    */
    function __construct($withJavaScript, $scriptLocation) {
        $this->hasScript = $withJavaScript;
        $this->scriptLoc = $scriptLocation;
    }

    /**
    *   Renders the header of the application by writing the doctype, head, and opening body tags form an XHTML5 document
    *   or by setting the header as Content-Type: text/xml for an XML document
    *   @param String $type (whether the document is an HTML or XML document, 'xml' for XML, other string for HTML)
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
				<!DOCTYPE html>
				<html>
					<head>
						<title>CS174 HW4 Datasheets</title>
						<?php
                            //load script if needed
							if ($this->hasScript) {
								?><script type="text/javascript" src="<?= $this->scriptLoc ?>"></script><?php
							}
						?>

					</head>
			<?php
		}
    }
}