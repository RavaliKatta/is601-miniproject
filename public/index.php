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
    public static function generateTable($records) {
        $table = self::getHTMLHeader();
        $count = 0;
        foreach ($records as $record) {
            $array = $record->returnArray();
            if($count == 0) {
                $fields = array_keys($array);
                $table = self::getString($fields, $table);
            }
            $values = array_values($array);
            $table = self::getString($values, $table);
            $count++;
        }
        $table.='</table></body></html>';
        return $table;
    }
    public static function getString($array, $table){
        $table.='<tr>';
        foreach($array as $value){
            $table .= $value;
        }
        $table.= '</tr>';
        return $table;
    }
    public static function getHTMLHeader(){
        $table = '<!DOCTYPE html><html lang="en"><head><link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script></head><body><table class="table table-bordered table-striped">';
        return $table;
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

