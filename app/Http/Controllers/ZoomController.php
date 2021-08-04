<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
        // dd($response['meetings']);

        $participantsList = array();
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
                            array_push($filterArray, $newarray);
                        }
                    }
                    // $filterArray = array_values($filterArray);
                    // dd($filterArray);
                    foreach ($filterArray as $key => $item) {
                        $item = array_values($item);
                        array_push($participantsList, $item);
                    }
                }
            }
        }
        // dd($participantsList);
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
        foreach ($items as $item) {
            if (($item['name']  == $row['name']  || $item['user_email'] == ['user_email'])) {
                return false;
            }
        }
        return true;
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

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us//v2/past_meetings/$id/participants",
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
        if ($response && isset($response['participants'])) {
            return $response['participants'];
        }

        return [];
    }
}
