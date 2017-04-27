<?php
/**
* @author Jorge Aguiniga, Luis Otero
*/
namespace jorgeandco\hw4\views;
require_once ('src//views//layouts//Header.php');
require_once ('src//views//layouts//Footer.php');
use jorgeandco\hw4\views\layouts as LYOT;

/**
* View class for displaying the home page
*/
class LandingView {
    private $head;
    private $footer;

    /**
    * Constructor for the LandingView, initializes header with no script
    */
    function __construct() {
        $this->head = new LYOT\Header(false, "");
        $this->footer = new LYOT\Footer();
    }

    /**
    * Renders the read data sheet view page with the following properties:
    *   1. Header
    *   2. Script for go button
    *   3. HTML Body
    *   4. Footer
    */
    function render() {
        //call header to render an html document
        $this->head->render("html")
        //start of html body, uses script to check data in the field to accept only alphanumeric or space characters before submitting the post request
        ?>
        <body>
            <script type="text/javascript">
                var reg = /^[A-Za-z\d\s]+$/ ;

                function onGoClick() {
                    var textBox = document.getElementById("sheet_search");
                    text = textBox.value;
                    if (verifyText(text)) {
					var form = document.getElementById("set_form");
					form.innerHTML = "<input type='hidden' name='name' value='" + text + "' />";
					form.submit();
                    }
                    else {
                        alert("Please enter alphanumerical or space characters");
                    }

                }

                function verifyText (text) {
                    if (text == "") {
                        return false;
                    }
                    return reg.test(text);

                }
            </script>
            <h1><a href="index.php">Web Sheets</a></h1>
            <div id="test">
                <input type="text" name="sheet_search" placeholder="New sheet name or code" id="sheet_search">
                <button id="bSheetSearch" onclick="return onGoClick();">Go</button>
				<form id="set_form" action="index.php?c=main&m=view" method="post"></form>
            </div>
        </body>
        <?php
        //end of html body
        //draw footer items
        $this->footer->render();
    }


}
