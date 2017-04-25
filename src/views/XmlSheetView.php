<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;

use jorgeandco\hw4\views\layouts as LYOT;
use jorgeandco\hw4 as CFG;

class XMLSheetView {
    private $head;
    private $footer;

    function __construct() {
        $this->head = new LYOT\Header(true, "spreadsheet.dtd");
        $this->footer = new LYOT\Footer();
    }


    function render($data) {
        $this->head->render("xml")
        ?>
			<spreadsheet name="<?= $data['title'] ?>">
		<?php
			$rowNumber = 1;
			$columnName = 'A';
			foreach($data['json'] as $row)
			{
		?>
					<row num="<?=$rowNumber?>">
		<?php
					foreach ($row as $column)
					{
		?>
							<column letter="<?= $columnName ?>"><?=$column?></column>
		<?php
							if ($columnName == 'Z')
							{
								$columnName == 'A';
								continue;
							}
							$columnName++;
					}
		?>
					</row>
		<?php
					$columnName = 'A';
					$rowNumber++;				
			}
		?>
			</spreadsheet>
		<?php
		
    }
}