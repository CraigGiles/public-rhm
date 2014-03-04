<?php namespace redhotmayo\distribution;

use InvalidArgumentException;

abstract class Distribution {

    protected $filename;

    public function getFileName() {
        return $this->filename;
    }

    /**
     * Checks to determine if the file exists prior to loading it into memory
     *
     * @param string $filename
     *
     * @throws \InvalidArgumentException
     */
    public function loadFromFile($filename) {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('Invalid file name');
        }

        $this->filename = $filename;
    }
}
