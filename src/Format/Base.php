<?php
// +---------------------------------------------------------------+
// | Base Table Class                                              |
// +---------------------------------------------------------------+
// | Parent for all the other table classes                        |
// +---------------------------------------------------------------+
// | Licence: MIT                                                  |
// +---------------------------------------------------------------+
// | Copyright:                                                    |
// |     Jamie Curnow  <jc@jc21.com>                               |
// |     Tim Nelson <tnelson@doghouse.agency>                      |
// +---------------------------------------------------------------+
//


namespace PHPTable\Format;

abstract class Base {

    /**
     * Table Data
     *
     * @var    object
     * @access protected
     *
     **/
    protected $injectedData = null;

    /**
     * Table Item name
     *
     * @var    string
     * @access protected
     *
     **/
    protected $itemName = 'Row';

    /**
     * Table fields
     *
     * @var    array
     * @access protected
     *
     **/
    protected $fields = array();

    /**
     * Show column headers?
     *
     * @var    bool
     * @access protected
     *
     **/
    protected $showHeaders = true;

    /**
     * Use colors?
     *
     * @var    bool
     * @access protected
     *
     **/
    protected $useColors = false;

    /**
     * Center content?
     *
     * @var    bool
     * @access protected
     *
     **/
    protected $centerContent = true;

    /**
     * Table Border Color
     *
     * @var    string
     * @access protected
     *
     **/
    protected $tableColor = 'reset';

    /**
     * Header Color
     *
     * @var    string
     * @access protected
     *
     **/
    protected $headerColor = 'reset';

    /**
     * Colors, will be populated after instantiation
     *
     * @var    array
     * @access protected
     *
     **/
    protected $colors = array();

    /**
     * Border Characters
     *
     * @var    array
     * @access protected
     *
     **/
    protected $chars = array(
        'top'          => '═',
        'top-mid'      => '╤',
        'top-left'     => '╔',
        'top-right'    => '╗',
        'bottom'       => '═',
        'bottom-mid'   => '╧',
        'bottom-left'  => '╚',
        'bottom-right' => '╝',
        'left'         => '║',
        'left-mid'     => '╟',
        'mid'          => '─',
        'mid-mid'      => '┼',
        'right'        => '║',
        'right-mid'    => '╢',
        'middle'       => '│ ',
    );


    /**
     * Constructor
     *
     * @access public
     * @param  string $itemName
     * @param  bool   $useColors
     */
    public function __construct($itemName = 'Row', $useColors = true, $centerContent = false) {
        $this->setItemName($itemName);
        $this->setUseColors($useColors);
        $this->setCenterContent($centerContent);
        $this->defineColors();
    }


    /**
     * setUseColors
     *
     * @access public
     * @param  bool  $bool
     * @return void
     */
    public function setUseColors($bool) {
        $this->useColors = (bool) $bool;
    }


    /**
     * setCenterContent
     *
     * @access public
     * @param  bool  $bool
     * @return void
     */
    public function setCenterContent($bool) {
        $this->centerContent = (bool) $bool;
    }


    /**
     * getUseColors
     *
     * @access public
     * @return bool
     */
    public function getUseColors() {
        return $this->useColors;
    }


    /**
     * getCenterContent
     *
     * @access public
     * @return bool
     */
    public function getCenterContent() {
        return $this->centerContent;
    }


    /**
     * setTableColor
     *
     * @access public
     * @param  string  $color
     * @return void
     */
    public function setTableColor($color) {
        $this->tableColor = $color;
    }


    /**
     * getTableColor
     *
     * @access public
     * @return string
     */
    public function getTableColor() {
        return $this->tableColor;
    }


    /**
     * setChars
     *
     * @access public
     * @param  array  $chars
     * @return void
     */
    public function setChars($chars) {
        $this->chars = $chars;
    }


    /**
     * setHeaderColor
     *
     * @access public
     * @param  string  $color
     * @return void
     */
    public function setHeaderColor($color) {
        $this->headerColor = $color;
    }


    /**
     * getHeaderColor
     *
     * @access public
     * @return string
     */
    public function getHeaderColor() {
        return $this->headerColor;
    }


    /**
     * setItemName
     *
     * @access public
     * @param  string  $name
     * @return void
     */
    public function setItemName($name) {
        $this->itemName = $name;
    }


    /**
     * getItemName
     *
     * @access public
     * @return string
     */
    public function getItemName() {
        return $this->itemName;
    }


    /**
     * injectData
     *
     * @access public
     * @param  array  $data
     * @return void
     */
    public function injectData($data) {
        $this->injectedData = $data;
    }


    /**
     * setShowHeaders
     *
     * @access public
     * @param  bool  $bool
     * @return void
     */
    public function setShowHeaders($bool) {
        $this->showHeaders = $bool;
    }


    /**
     * getShowHeaders
     *
     * @access public
     * @return bool
     */
    public function getShowHeaders() {
        return $this->showHeaders;
    }


    /**
     * getPluralItemName
     *
     * @access protected
     * @return string
     */
    protected function getPluralItemName() {
        if (count($this->injectedData) == 1) {
            return $this->getItemName();
        } else {
            $lastChar = strtolower(substr($this->getItemName(), strlen($this->getItemName()) -1, 1));
            if ($lastChar == 's') {
                return $this->getItemName() . 'es';
            } else if ($lastChar == 'y') {
                return substr($this->getItemName(), 0, strlen($this->getItemName()) - 1) . 'ies';
            } else {
                return $this->getItemName().'s';
            }
        }
    }


    /**
     * addField
     *
     * @access public
     * @param  string      $fieldName
     * @param  string      $fieldKey
     * @param  bool|object $manipulator
     * @param  string      $color
     * @return void
     */
    public function addField($fieldName, $fieldKey, $manipulator = false, $color = 'reset') {
        $this->fields[$fieldKey] = array(
            'name'        => $fieldName,
            'key'         => $fieldKey,
            'manipulator' => $manipulator,
            'color'       => $color,
        );
    }


    /**
     * get
     *
     * @access public
     * @return string
     */
    public function get() {
        $rowCount      = 0;
        $columnLengths = array();
        $headerData    = array();
        $cellData      = array();

        // Headers
        if ($this->getShowHeaders()) {
            foreach ($this->fields as $field) {
                $headerData[$field['key']] = trim($field['name']);

                // Column Lengths
                if (!isset($columnLengths[$field['key']])) {
                    $columnLengths[$field['key']] = 0;
                }
                $columnLengths[$field['key']] = max($columnLengths[$field['key']], strlen(trim($field['name'])));
            }
        }

        // Data
		if ($this->injectedData === null) {
			return 'There is no injected data for the table!' . PHP_EOL;
		}
		if (! count($this->injectedData)) {
			return 'There are no '.$this->getPluralItemName() . PHP_EOL;
		}
        foreach ($this->injectedData as $row) {
            // Row
			if(is_scalar($row)) {
                $cellData[$rowCount] = $row;
            } else {
                $cellData[$rowCount] = array();
                foreach ($this->fields as $field) {
                    $key   = $field['key'];
                    $cellObj = new \PHPTable\Cell($field, $row, $key);
                    $cellData[$rowCount][$key] = $cellObj;

                    // Column Lengths
                    if (!isset($columnLengths[$key])) {
                        $columnLengths[$key] = 0;
                    }
                    $columnLengths[$key] = max($columnLengths[$key], $cellObj->get_width());
                }
            }
            $rowCount++;
        }

        $response = '';

        // Get the screen width
        $screenWidth = $this->getScreenWidth();

        // Idea here is we're column the accumulated length of the data
        // Then adding the quantity of column lengths to accommodate for the extra characters
        //     for when vertical pipes are placed between each column
        $dataWidth = mb_strlen($this->getTableTop($columnLengths)) + count($columnLengths);

        $spacing = '';

        // Only try and center when content is less than available space
        if ($this->getCenterContent() && (($dataWidth/2) < $screenWidth)) {
            $spacing = str_repeat(' ', ($screenWidth-($dataWidth/2))/2);
        }

        // Now draw the table!
        $response .= $spacing . $this->getTableTop($columnLengths);
        if ($this->getShowHeaders()) {
            $response .= $spacing . $this->getFormattedRow($headerData, $columnLengths, true);
            $response .= $spacing . $this->getTableSeparator($columnLengths);
        }

        foreach ($cellData as $row) {
            $response .= $spacing . $this->getFormattedRow($row, $columnLengths);
        }

        $response .= $spacing . $this->getTableBottom($columnLengths);

        return $response;
    }

    // Get the screen width (Windows vs. others)
    protected function getScreenWidth() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $screenWidthCommand = 'mode con | findstr Columns';
            $screenWidth = trim(exec($screenWidthCommand));
            $screenWidth = preg_match('/\d+/', $screenWidth, $matches);;
        } else {
            $screenWidth = trim(exec("tput cols"));
        }
        return $screenWidth;
    }


    /**
     * getFormattedRow
     *
     * @access protected
     * @param  array   $rowData
     * @param  array   $columnLengths
     * @param  bool    $header
     * @return string
     */
    protected function getFormattedRow($rowData, $columnLengths, $header = false) {
        $response = '';

        $splitLines = [];
        $maxLines = 1;
        foreach ($rowData as $key => $line) {
            $splitLines[$key] = explode("\n", $line);
            $maxLines = max($maxLines, count($splitLines[$key]));
        }

        for ($i = 0; $i < $maxLines; $i++) {
            $response .= $this->getChar('left');

            foreach ($splitLines as $key => $lines) {
                if ($header) {
                    $color = $this->getHeaderColor();
                } else {
                    $color = $this->fields[$key]['color'];
                }

                $line = isset($lines[$i]) ? $lines[$i] : '';

                $c = chr(27);
                $lineLength = mb_strwidth(preg_replace("/({$c}\[(.*?)m)/", '', $line)) + 1;
                $line = ' ' . ($this->getUseColors() ? $this->getColorFromName($color) : '') . $line;
                $response .= $line;

                for ($x = $lineLength; $x < ($columnLengths[$key] + 2); $x++) {
                    $response .= ' ';
                }
                $response .= $this->getChar('middle');
            }
            $response = substr($response, 0, strlen($response) - 3) . $this->getChar('right') . PHP_EOL;
        }

        return $response;
    }


    /**
     * getTableTop
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableTop($columnLengths) {	return ''; }


    /**
     * getTableBottom
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableBottom($columnLengths) {	return ''; }


    /**
     * getTableSeparator
     *
     * @access protected
     * @param  array   $columnLengths
     * @return string
     */
    protected function getTableSeparator($columnLengths) {	return ''; }


    /**
     * getChar
     *
     * @access protected
     * @param  string  $type
     * @param  int     $length
     * @return string
     */
    protected function getChar($type, $length = 1) {	return ''; }


    /**
     * defineColors
     *
     * @access protected
     * @return void
     */
    protected function defineColors()
    {
        $this->colors = array(
            'blue'    => chr(27).'[1;34m',
            'red'     => chr(27).'[1;31m',
            'green'   => chr(27).'[1;32m',
            'yellow'  => chr(27).'[1;33m',
            'black'   => chr(27).'[1;30m',
            'magenta' => chr(27).'[1;35m',
            'cyan'    => chr(27).'[1;36m',
            'white'   => chr(27).'[1;37m',
            'grey'    => chr(27).'[0;37m',
            'reset'   => chr(27).'[0m',
        );
    }


    /**
     * getColorFromName
     *
     * @access protected
     * @param  string  $colorName
     * @return string
     */
    protected function getColorFromName($colorName) {	return ''; }


    /**
     * display
     *
     * @access public
     * @return void
     */
    public function display() {
        print $this->get();
    }

}