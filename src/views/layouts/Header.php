<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views\layouts;

class Header
{
    /**
    *   Renders the header of the application by writing the doctype, head, and opening body tags of the application's XHTML5 document
    *   @param String $data (the current date to be used as part of the pages title)
    */
    function render()
    {
        $styleDir = 'src/styles/styles.css';
        ?>
            <!doctype html>
            <html>
                <head>
                    <title>CS174 HW4 Datasheets</title>
                    <link rel="stylesheet" type="text/css" href="<?=$styleDir;?>" />
                </head>
        <?php
    }
}