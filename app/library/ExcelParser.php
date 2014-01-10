<?php

/**
 * class ExcelParser
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class ExcelParser {
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
     * Once the files data has been loaded, it will be passed along to the $processor
     * specified by the caller for processing.
     *
     * @param $filename The filename attempting to be loaded
     * @param $processor The processor used to process the loaded data
     * @return array Array of data from the processor
     *
     * @throws InvalidArgumentException
     */
    public function parse($filename, $processor) {
        //if the processor is not a callable function, bail
        if (is_callable($processor)) {
            $this->processor = $processor;
        } else {
            throw new InvalidArgumentException("Processor is not an acceptable callback function");
        }

        $records = array();
        $keys = array();
        $processingKeys = true;

        if (!file_exists($this->filename)) {
            return $records;
        }

        $reader = new PHPExcel_Reader_Excel2007();
        $excel = $reader->load($filename);
        $sheetData = $excel->getActiveSheet()
                           ->toArray(null, true, true, true);

        foreach ($sheetData as $row) {
            if ($processingKeys) {
                $keys = $row;
                $processingKeys = false;
            } else {
                $records[] = $this->getRecord($keys, $row);
            }
        }

        //we are now done reading all of the data.. call the processor to process...
        return call_user_func($processor, $records);
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
