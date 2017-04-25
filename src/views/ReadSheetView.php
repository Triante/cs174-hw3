<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;

use jorgeandco\hw4\views\layouts as LYOT;
use jorgeandco\hw4 as CFG;

class ReadSheetView {
    private $head;
    private $footer;

    function __construct() {
        $this->head = new LYOT\Header(true, "src//resources//spreadsheet.js");
        $this->footer = new LYOT\Footer();
    }


    function render($data) {
        $this->head->render("html")
        ?>
        <body>
            <h1><a href="index.php">Web Sheets</a> : <?= $data['title'] ?></h1>
            <div>
                <label for="file_url">File URL:</label>
                <input id="file_url" type="text" disabled="disabled" value="<?=CFG\Config::V_URL?>c=main&m=view&amp;arg1=<?= $data['codeF'] ?>_f"/>
            </div>
            <div id="spreadsheet_edit"></div>
            <script>
                var json_string = '<?= $data['json'] ?>';
                var sheet_id = '<?= $data['id'] ?>';
                var json_array = JSON.parse(json_string);
                var spreadsheet2 = new Spreadsheet(sheet_id, "spreadsheet_edit",
                json_array); //read
                spreadsheet2.draw();
            </script>
        </body>
        <?php
        $this->footer->render();
    }
}