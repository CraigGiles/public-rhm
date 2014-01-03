<?php

/**
 * class ExcelParser
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class ExcelParser {
    private $filename;

    function __construct($filename) {
        $this->filename = $filename;
    }

    /**
     * Parse the given XLSX file into and load the leads into an array.
     * Each header in the XLS file will be used as a key for the arra, for example:
     *
     * NAME      ADDRESS             CONTACT
     * Craig     1093 Dunbar Street  Sales
     *
     * Will be formatted to:
     * "NAME" => Craig
     * "ADDRESS" => 1093 Dunbar Street
     * "CONTACT" => Sales
     *
     * NOTE: Header information will be automatically converted to upper case, so if
     * the header is "Name" in the XLSX file, it will be converted to "NAME" as the
     * index of the record.
     *
     * @return array
     */
    public function parse() {
    $records = array();
    $keys = array();
    $processingKeys = true;

    if (!file_exists($this->filename)) {
        return $records;
    }

    $reader = new PHPExcel_Reader_Excel2007();
    $excel = $reader->load($this->filename);
    $sheetData = $excel->getActiveSheet()->toArray(null, true, true, true);

    foreach ($sheetData as $row) {
        if ($processingKeys) {
            $keys = $row;
            $processingKeys = false;
        } else {
            $records[] = $this->getRecord($keys, $row);
        }
    }

    return $records;
}

    private function getRecord($keys, $row) {
    $record = array();

    foreach ($keys as $columnLetter => $header) {
        if (empty($header)) {
            continue;
        }

        $record[strtoupper($header)] = strval($row[$columnLetter]);
    }

    return $record;
}
}
