<?php

namespace App\Http\Controllers;


use App\Models\ZoomMeeting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class MeetingController extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->token = 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IjJidm5iOWdnVENLY29nZTlGUzZVelEiLCJleHAiOjE2NDg2Mzg1NjMsImlhdCI6MTY0ODAzMzc2M30.6Kl8RuapDM-lJR-4w9dSj-r0CzOykAoeUK0Zq6G1raA';
    }

    public function get()
    {
        $data = ZoomMeeting::latest()->get();
        return $data;
//        $url = 'https://api.zoom.us/v2/users/me/meetings';
//
//        $response = $response = Http::withHeaders([
//            'Authorization' => $this->token,
//            'Content-Type' => 'application/json'
//        ])->get($url);
//        dd($response->body());

        $response = json_decode($response->getBody()->getContents());
    }


    public function create(Request $request)
    {
        $url = 'https://api.zoom.us/v2/users/me/meetings';
        $data = $request->data;

        $response = $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Content-Type' => 'application/json'
        ])->post($url, $data);

        $response = json_decode($response->getBody()->getContents());

        if ($response) {
            $meeting = ZoomMeeting::create([
                'meeting_id' => $response->id,
                'topic' => $response->topic,
                'agenda' => $response->agenda,
                'start_url' => $response->start_url,
                'join_url' => $response->join_url,
                'password' => $response->password,
                'response' => $response,
                'start_time' => $data['start_time'],
                'end_time' => Carbon::parse($data['start_time'])->addMinutes($data['duration']),
                'duration' => $data['duration'],
            ]);

            return response()->json(['message' => 'Meeting created successfully', 'data' => $meeting]);
        }
    }

    function generate_signature (Request $request){

        $api_key='2bvnb9ggTCKcoge9FS6UzQ';
        $api_secret='B2AffFD1Jy1XPADoRhfu9K5bmMv9HeAcbRq6';
        $meeting_number= $request->meeting_number;
        $role = $request->role;
        //Set the timezone to UTC
        date_default_timezone_set("UTC");
        $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
        $data = base64_encode($api_key . $meeting_number . $time . $role);
        $hash = hash_hmac('sha256', $data, $api_secret, true);
        $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
        //return signature, url safe base64 encoded
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }
}
