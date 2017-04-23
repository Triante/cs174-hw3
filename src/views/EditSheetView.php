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
        $this->head = new LYOT\Header(true, "src//resources//spreadsheet.js");
        $this->footer = new LYOT\Footer();
    }


    function render($data) {
        $this->head->render()
        ?>
		var json = "<?php echo json_encode($data['json']); ?>";
		var obj = JSON.parse(json);
		var spreadsheet2 = new Spreadsheet("spreadsheet_edit",
		[["john"],["carry"]], {"mode":"write"}); //editable
		spreadsheet2.draw();
		</script>
        <body>
            <h1><a href="index.php">Web Sheets</a><?= $data['title'] ?></h1>
            <div>
                <label for="edit_url">Edit URL:</label>
                <input id="edit_url" type="text" disabled="disabled" value="V_URL&amp;arg1=8_digit_hash_e"/>
            </div>
            <div>
                <label for="read_url">Read URL:</label>
                <input id="read_url" type="text" disabled="disabled" value="V_URL&amp;arg1=8_digit_hash_r"/>
            </div>
            <div>
                <label for="file_url">File URL:</label>
                <input id="file_url" type="text" disabled="disabled" value="V_URL&amp;arg1=8_digit_hash_f"/>
            </div>
            <div id="spreadsheet_edit"></div>
        </body>
        <?php
        $this->footer->render();
    }
}