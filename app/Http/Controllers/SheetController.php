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
        $data = Session::get('participants');
        // dd($data);
        $sheet = new SheetDB('opxf5tea1bz3z');

        $request = $this->requestSheetdb('https://sheetdb.io/api/v1/opxf5tea1bz3z/duplicates', 'delete');
        $sheet->create($data);
        return redirect('https://docs.google.com/spreadsheets/d/12_n1RWITc_XfX_TqsOnIojHp2RAeQ26riRozhP7EG6k/edit#gid=0');
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
}
