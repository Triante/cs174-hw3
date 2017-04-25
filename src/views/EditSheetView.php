<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;

use jorgeandco\hw4\views\layouts as LYOT;

class EditSheetView {
    private $head;
    private $footer;

    function __construct() {
        $this->head = new LYOT\Header(true, "src/resources/spreadsheet.js");
        $this->footer = new LYOT\Footer();
    }


    function render($data) {
        $this->head->render()
        ?>
		<body>
            <h1><a href="index.php">Web Sheets</a> : <?= $data['title'] ?></h1>
            <div>
                <label for="edit_url">Edit URL:</label>
                <input id="edit_url" type="text" disabled="disabled" value="V_URL&amp;arg1=<?= $data['codeE'] ?>"/>
            </div>
            <div>
                <label for="read_url">Read URL:</label>
                <input id="read_url" type="text" disabled="disabled" value="V_URL&amp;arg1=<?= $data['codeR'] ?>"/>
            </div>
            <div>
                <label for="file_url">File URL:</label>
                <input id="file_url" type="text" disabled="disabled" value="V_URL&amp;arg1=<?= $data['codeF'] ?>"/>
            </div>
            <div id="spreadsheet_edit"></div>
			<script>
				var json_string = '<?= $data['json'] ?>';
                var sheet_id = <?= $data['id'] ?>;
				var json_array = JSON.parse(json_string);
				var spreadsheet2 = new Spreadsheet(sheet_id, "spreadsheet_edit",
				json_array, {"mode":"write"}); //editable
				spreadsheet2.draw();
			</script>
        </body>
        <?php
        $this->footer->render();
    }
}