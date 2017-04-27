<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;

use jorgeandco\hw4\views\layouts as LYOT;
use jorgeandco\hw4 as CFG;

/**
* View class for displaying the read page for a spread sheet
*/
class ReadSheetView {
    private $head;
    private $footer;

    /**
    * Constructor for the ReadSheetView, initializes header with spreadsheet.js for its script
    */
    function __construct() {
        $this->head = new LYOT\Header(true, "src//resources//spreadsheet.js");
        $this->footer = new LYOT\Footer();
    }

    /**
    * Renders the read data sheet view page with the following properties:
    *   1. Header
    *   2. File location
    *   3. Web Sheet
    *   4. Footer
    *   @param array $data (array that contains the is and data for the spreadsheet to be drawn
    *                         array contents: $data['id']: the id of the spreadsheet
    *                                         $data['json']: the data of the spreadsheet as a JSON
    *                                         $data['title']: the title of the spreadsheet)
    */
    function render($data) {
        //call header to render an html document
        $this->head->render("html")
        //start of html body, uses script to draw the spreadsheet based on the data provided in $data, only displays file url
        ?>
        <body>
            <h1><a href="index.php">Web Sheets</a> : <?= $data['title'] ?></h1>
            <div>
                <label class="url_label" for="file_url">File URL:</label>
                <input id="file_url" type="text" disabled="disabled" value="<?=CFG\Config::V_URL?>c=main&m=view&amp;arg1=<?= $data['codeF'] ?>"/>
            </div>
            <div id="spreadsheet_edit"></div>
            <script>
                var json_string = '<?= $data['json'] ?>';
                var sheet_id = '<?= $data['id'] ?>';
                var json_array = JSON.parse(json_string);
                var spreadsheet2 = new Spreadsheet(sheet_id, "spreadsheet_edit", json_array); //read
                spreadsheet2.draw();
            </script>
        </body>
        <?php
        //end of html body
        //draw footer items
        $this->footer->render();
    }
}