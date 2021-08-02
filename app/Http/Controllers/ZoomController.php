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
        // set_time_limit(0);
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
                $participants = $this->getParticipantesList($value['uuid'], $request->token);
                if (isset($participants)) {
                    foreach ($participants as $key => $participant) {
                        // dd($participant);
                        $newarray = [];
                        $newarray['id'] = $value['id'];
                        $newarray['host'] = $value['host'];
                        $newarray['topic'] = $value['topic'];
                        $newarray['start_time'] = date('d-m-Y h:s A', strtotime($value['start_time']));
                        $newarray['user_name'] = $participant['user_name'];
                        $newarray['email'] = isset($participant['email']) ? $participant['email'] : '-';

                        $newarray = array_values($newarray);
                        array_push($participantsList, $newarray);
                    }
                }
            }
        }
        // $newarray = array_values($participantsList);

        // dd(array_values($participantsList));
        $data['title'] = 'Get Participant';
        $data['token'] = $request->token;
        $data['from_date'] = $request->from_date;
        $data['to_date'] = $request->to_date;
        $data['response'] = $participantsList;

        return view('welcome', $data);
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
            CURLOPT_URL => "https://mighty-bastion-54961.herokuapp.com/partReports/$token/$id",
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
        $response = json_decode($response, true);
        return isset($response['participants']) ? $response['participants'] : [];
    }
}
