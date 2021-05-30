<?php
class PiController extends Controller
{
    protected function ReturnJson($Data) {
        header('Content-Type: application/json');
        echo json_encode($Data);
    }
    protected function GetJsonInput() {
        $inputJSON = file_get_contents('php://input');
        $client_input = json_decode($inputJSON, TRUE);
        return $client_input;
    }

    protected function rutime($ru, $rus, $index) {
        // based on https://stackoverflow.com/questions/535020
        return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
         -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
    }
}