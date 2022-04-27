<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * Summary.
 * add a log in file in public in JSON format
 * 
 * @author amirhosein hasani
 * @param array $data
 * @param string $name name of log file
 * @return nothing
 */
function log_file( array $data, string $name = "log.json") {
    $log = fopen(FCPATH . $name, "w+");
    fwrite($log, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    fclose($log);
}

/**
 * @return array
 */
function get_excel_data( $file ) {
    $reader = new Xlsx();
    $spreadsheet = $reader->load( $file );

    // $d = $spreadsheet->getSheet(0)->toArray();
    return $spreadsheet->getActiveSheet()->toArray();
}