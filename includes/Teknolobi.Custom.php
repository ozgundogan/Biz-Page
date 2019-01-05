<?php

Class CustomOperations extends Teknolobi
{
    public $parent;

    public function Test()
    {
        echo "Showing FW version: " . $this->parent->Version;
        exit();
    }

    public function Refresh($parent)
    {
        $this->parent = $parent;
    }

    function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function FindSmilarImages($imagehex, $minsmilar = 16)
    {
        if (!$this->parent->Connection) {
            $this->parent->data_connect();
        }
        //var_dump(debug_backtrace());
        $sql = $this->parent->data_get("SELECT *, BIT_COUNT( CAST(CONV(hashhex, 16, 10) AS UNSIGNED) ^ CAST(CONV('" . $imagehex . "', 16, 10) AS UNSIGNED) ) as benzer from images where BIT_COUNT( CAST(CONV(hashhex, 16, 10) AS UNSIGNED) ^ CAST(CONV('" . $imagehex . "', 16, 10) AS UNSIGNED) ) <=" . $minsmilar);
        $rows = array();
        while ($row = $this->parent->data_fetch_array($sql)) {
            $rows[] = $row;
        }
        return $rows;
    }
} ?>