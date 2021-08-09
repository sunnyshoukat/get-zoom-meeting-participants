<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\Empty_;

class ZoomController extends Controller
{

    public function index()
    {
        $data['title'] = 'Get Participants';
        return view('welcome', $data);
    }

    public function getParticipantes(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        $from_date = date('Y-m-d', strtotime($request->from_date));
        $to_date = date('Y-m-d', strtotime($request->to_date));
        $response = $this->zoomApiCurl($request->token, $from_date, $to_date);

        if ($from_date > $to_date) {
            $data['title'] = 'Get Participant';
            $data['token'] = $request->token;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['error'] = 'From Date must be greater than To Date';
            return view('welcome', $data);
        }
        $participantsList = array();
        $sheetData = [];
        if ($response) {
            foreach ($response['meetings'] as $key => $value) {
                $participants = $this->getParticipantesList($value['id'], $request->token);
                if (isset($participants)) {
                    $participants = collect($participants);
                    $participants = $participants->unique('id')->all();
                    $filterArray = [];
                    foreach ($participants as $key => $participant) {

                        $newarray = [];
                        $newarray['id'] = $value['id'];
                        $newarray['host'] = $value['host'];
                        $newarray['topic'] = $value['topic'];
                        $newarray['start_time'] = date('d-m-Y h:s A', strtotime($value['start_time']));
                        $newarray['name'] = $participant['name'];
                        $newarray['user_email'] = $participant['user_email'] != '' ? $participant['user_email'] : '-';
                        $no_in_array = $this->filterItem($filterArray, $participant);
                        if ($no_in_array) {
                            array_push($sheetData, $newarray);
                            array_push($filterArray, $newarray);
                        }
                    }

                    $filterArray = array_values($filterArray);
                    foreach ($filterArray as $key => $item) {
                        $item = array_values($item);
                        array_push($participantsList, $item);
                    }
                }
            }
        }
        // dd($sheetData);
        Session::put('participants', $sheetData);

        $data['title'] = 'Get Participant';
        $data['token'] = $request->token;
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['response'] = $participantsList;

        return view('welcome', $data);
    }

    private function filterItem($items, $row)
    {
        $row = (array)$row;
        $flag = true;

        if (!empty($row['name'])) {

            foreach ($items as $item) {
                if ((strtolower(trim($item['name']))  == strtolower(trim($row['name']))  &&  (strtolower(trim($item['user_email'])) == strtolower(trim($row['user_email']))) && $item['id'] == $item['id'])) {
                    $flag = false;
                }
            }
        }
        return $flag;
    }


    public function zoomApiCurl($jwt_key, $from, $to)
    {
        // set_time_limit(0);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://mighty-bastion-54961.herokuapp.com/meetingsByDate/$jwt_key/past/$from/$to/500?mail=$jwt_key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (!$response) {
            return json_decode($err, true);
        }

        return json_decode($response, true);
    }

    public function getParticipantesList($id, $token)
    {
        // set_time_limit(0);
        $url = "https://api.zoom.us/v2/past_meetings/$id/participants";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
            ),
        ));


        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if (!$response) {
            return json_decode($err, true);
        }
        $response = json_decode($response, true);
        $nextResponse = [];
        if (isset($response['page_count']) && $response['page_count'] > 1) {
            if (isset($response['next_page_token']) && $response['next_page_token'] != '') {
                $nextToken = $response['next_page_token'];
                for ($i = 1; $i < $response['page_count']; $i++) {
                    $nextResponse = $this->getParticipantesListNext($id, $token, $nextToken);
                    if (isset($nextResponse['next_page_token']) && $nextResponse['next_page_token'] != '') {
                        $nextToken = $nextResponse['next_page_token'];
                    }
                    foreach ($nextResponse['participants'] as $key => $nextRow) {
                        array_push($response['participants'], $nextRow);
                    }
                }
            }
        }

        if ($response && isset($response['participants'])) {
            return $response['participants'];
        }
        return [];
    }

    public function getParticipantesListNext($id, $token, $nest_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us//v2/past_meetings/$id/participants?next_page_token=$nest_token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
            ),
        ));


        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if (!$response) {
            return json_decode($err, true);
        }
        $response = json_decode($response, true);

        if ($response) {
            return $response;
        }


        return [];
    }
}
