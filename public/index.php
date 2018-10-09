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
        echo $table;
    }
}
class html {
    public static function generateTable($records)
    {
        $table = self::getHTMLHeader();
        $table .= self::returnForEach($records, 'records', true) . '</table></body></html>';
        return $table;
    }
    public static function getHTMLHeader(){
        $table = '<!DOCTYPE html><html lang="en"><head><link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script></head><body><table class="table table-bordered table-striped">';
        return $table;
    }
    public static function returnForEach($array, $arrayType, $isHeader){
        $output = '';
        $RowString = true;
        foreach ($array as $value){
            switch ($arrayType) {
                case "records":
                    $value = (array) $value;
                    if($isHeader){
                        $output .= self::returnForEach(array_keys($value), 'record' , $isHeader);
                        $isHeader = false;
                    }
                    $output .= self::returnForEach(array_values($value), 'record', $isHeader);
                    $RowString= false;
                    break;
                case "record":
                    $output .= self::addTable($value, $isHeader);
                    break;
            }
        }
        if($RowString) {
            return self::addRow($output);
        } else {
            return $output;
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
        $dataTable = array_combine($fieldNames, $values);
        $this->record = $dataTable;
    }

    public function returnArray() {
        return $this->record;
    }
}
class recordFactory {
    public static function createRecord(Array $fieldNames = null, $values = null) {
        $dataTable = new record($fieldNames, $values);
        return $dataTable->returnArray();
    }
}

