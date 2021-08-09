<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SheetDB\SheetDB;

class SheetController extends Controller
{

    public function get()
    {
        $sheetData = Session::get('participants');

        $sheet = new SheetDB('opxf5tea1bz3z');
        // $createAll = $this->requestSheetdb('https://sheetdb.io/api/v1/opxf5tea1bz3z', 'post', $data);
        $data = http_build_query(
            [
                'data' => $sheetData
            ]
        );
        // $result = $this->createRows($data);
        // foreach ($data as $key => $row) {
        $sheet->create($sheetData);
        // }
        // dd($sheet->get());
        // dd($result);
        // if ($createAll) {
        return redirect('https://docs.google.com/spreadsheets/d/12_n1RWITc_XfX_TqsOnIojHp2RAeQ26riRozhP7EG6k/edit#gid=0');
        // }
    }

    public function empty()
    {
        $sheet = new SheetDB('opxf5tea1bz3z');
        $deleteAll = $this->requestSheetdb('https://sheetdb.io/api/v1/opxf5tea1bz3z/all', 'delete');
        return redirect('https://docs.google.com/spreadsheets/d/12_n1RWITc_XfX_TqsOnIojHp2RAeQ26riRozhP7EG6k/edit#gid=0');
        // return back();
    }

    function requestSheetdb($url, $method = 'GET', $data = [])
    {

        $options = array(
            'http' => array(
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'method'  => strtoupper($method),
                'content' => http_build_query([
                    'data' => $data
                ])
            )
        );

        try {
            $raw = @file_get_contents($url, false, stream_context_create($options));
            $result = json_decode($raw, true);
        } catch (Exception $e) {
            return false;
        }

        return $result;
    }

    public function createRows($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sheetdb.io/api/v1/opxf5tea1bz3z',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        return $response;
    }
}
