<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;
require_once ('src//views//layouts//Header.php');
require_once ('src//views//layouts//Footer.php');
use jorgeandco\hw4\views\layouts as LYOT;

class ReadSheetView {
    private $head;
    private $footer;

    function __construct() {
        $this->head = new LYOT\Header(true, "src//resources//spreadsheet.js");
        $this->footer = new LYOT\Footer();
    }


    function render() {
        $this->head->render()
        ?>
        <body>
            <h1><a href="index.php">Web Sheets</a></h1>
            <div>
                <label for="file_url">File URL:</label>
                <input id="file_url" type="text" disabled="disabled" value="V_URL&amp;arg1=8_digit_hash_f"/>
            </div>
            <div id="spreadsheet_edit"></div>
            <script>
                var spreadsheet2 = new Spreadsheet("spreadsheet_edit",
                [["Tom"],["Sally"]]); //read only
                spreadsheet2.draw();
            </script>
        </body>
        <?php
        $this->footer->render();
    }
}