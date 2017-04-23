<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;
require_once ('src//views//layouts//Header.php');
require_once ('src//views//layouts//Footer.php');
use jorgeandco\hw4\views\layouts as LYOT;

class LandingView {
    private $head;
    private $footer;

    function __construct() {
        $this->head = new LYOT\Header(false, "");
        $this->footer = new LYOT\Footer();
    }

    function render() {
        $this->head->render()
        ?>
        <body>
            <h1><a href="index.php">Web Sheets</a></h1>
            <div>
                <input type="text" name="sheet_search" placeholder="New sheet name or code" id="sheet_search">
                <button id="bSheetSearch">Go</button>
            </div>
        </body>
        <?php
        $this->footer->render();
    }


}
