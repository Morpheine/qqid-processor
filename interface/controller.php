<?php
    /**
     * User: Valhalla
     * Date: 10/26/14
     * Time: 1:21 PM
     */
    error_reporting(-1);
    ini_set('display_errors', 'On');
    function __autoload($class_name)
    {
        include "classes/" . $class_name . '.php';
    }
    $type = "";
    if(isset($_GET['type'])) {
        $reportType = $_GET['type'];
        $report = new Report($reportType);
        $result = array("data" => $report->getReportData());
        echo json_encode($result);
//        echo $result;
    }
?>
