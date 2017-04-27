<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;

use jorgeandco\hw4\views\layouts as LYOT;
use jorgeandco\hw4 as CFG;

/**
* View class for displaying a spread sheet as an XML File
*/
class XMLSheetView {
    private $head;
    private $footer;

    /**
    * Constructor for the XMLSheetView, initializes header with no script
    */
    function __construct() {
        $this->head = new LYOT\Header(false, "");
        $this->footer = new LYOT\Footer();
    }


        /**
    * Renders the read data sheet view page with the following properties:
    *   1. Header
    *   2. XML Document
    *   @param array $data (array that contains the is and data for the spreadsheet to be drawn
    *                         array contents: $data['title']: the name of the spreadsheet
    *                                         $data['json']: the data of the spreadsheet as a JSON)
    */
    function render($data) {
        //call header to render an XML document
        $this->head->render("xml");
        //initializing row number
		$rowNumber = 1;
        //initializing column letter
		$columnName = 'A';
        //starting tags for XML document along with dtd
        $xml =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE spreadsheet SYSTEM \"spreadsheet.dtd\">\n";
        //opening tag for spread sheet object
        $xml = $xml."<spreadsheet name='".$data['title']."'>\n";
		foreach($data['json'] as $row)
		{
            //start new row object
			$xml =$xml."<row num='".$rowNumber."'>\n";
			foreach ($row as $column)
			{
                //New column object
				$xml =$xml."<column letter='".$columnName."'>".$column."</column>\n";

                //if last column was Z, loop back to column A
				if ($columnName == 'Z')
				{
					$columnName == 'A';
					continue;
				}
                //increment column
				$columnName++;
			}
            //close tag for row object
			$xml =$xml."</row>\n";
            //reset current column
			$columnName = 'A';
            //increment row
			$rowNumber++;

		}
        //close tag for spread sheet object
		$xml =$xml."</spreadsheet>";
        //pring the XML document
        print($xml);
    }
}