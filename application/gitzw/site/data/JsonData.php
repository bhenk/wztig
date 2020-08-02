<?php
namespace gitzw\site\data;

Abstract class JsonData implements \JsonSerializable {
    
    /**
     * Get the name of the file for reading and storing this data.
     * 
     * @return string filename
     */
    public abstract function getFile() : string;
    
    /**
     * Read data from file. For an empty or non existent file, will return an empty array.
     * 
     * @return array (recursive) array with raw data
     */
    public function load() : array {
        $arr = json_decode(file_get_contents(static::getFile()), TRUE);
        if (is_null($arr)) {
            $arr = array();
        }
        return $arr;
    }
    
    /**
     * Store this data on file. Acquires an exclusive lock on the file while proceeding to the 
     * writing. 
     * 
     * @return int|NULL the number of bytes that were written to the file, or false on failure.
     */
    public function persist() : ?int {
        return file_put_contents(static::getFile(), 
            json_encode($this->jsonSerialize(), JSON_PRETTY_PRINT), LOCK_EX);
    }
    
}