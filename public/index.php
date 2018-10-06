<?php
/**
 * Created by PhpStorm.
 * User: ravali
 * Date: 10/2/18
 * Time: 8:45 PM
 */

main::start("datatable.csv");
class main{
    static public function start($filename){
        $records = csv::getRecords($filename);
        $table = html::generateTable($records);
    }
}
class html {
    public static function generateTable($records) {
        $count = 0;
        foreach ($records as $record) {
            if($count == 0) {
                $array = $record->returnArray();
                $fields = array_keys($array);
                $values = array_values($array);
                print_r($fields);
                print_r($values);
            } else {
                $array = $record->returnArray();
                $values = array_values($array);
                print_r($values);
            }
            $count++;
        }
    }
}
class csv{
    static public function getRecords($filename) {
        $file = fopen($filename,"r");
        $fieldNames = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldNames = $record;
            } else {
                $records[] = recordFactory::create($fieldNames, $record);
            }
            $count++;
        }

        fclose($file);
        return $records;
    }
}
class record {
    public function __construct(Array $fieldNames = null, $values = null )
    {
        $record = array_combine($fieldNames, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        $array = (array) $this;
        return $array;
    }
    public function createProperty($name = 'Company', $value = 'Infosys') {
        $this->{$name} = $value;
    }
}

class recordFactory {
    public static function create(Array $fieldNames = null, Array $values = null) {
        $record = new record($fieldNames, $values);
        return $record;
    }
}
