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
        $this->head->render("xml");
		$rowNumber = 1;
		$columnName = 'A';
        $xml =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE spreadsheet SYSTEM \"spreadsheet.dtd\" >\n";
        $xml = $xml."<spreadsheet name='".$data['title']."'>\n";
		foreach($data['json'] as $row)
		{
				$xml =$xml."<row num='".$rowNumber."'>\n";
				foreach ($row as $column)
				{
						$xml =$xml."<column letter='".$columnName."'>".$column."</column>\n";

						if ($columnName == 'Z')
						{
							$columnName == 'A';
							continue;
						}
						$columnName++;
				}
				$xml =$xml."</row>\n";
				$columnName = 'A';
				$rowNumber++;

		}
		$xml =$xml."</spreadsheet>";
        print($xml);
    }
}