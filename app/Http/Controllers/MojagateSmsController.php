<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class MojagateSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function authenticate()
    {
        $client = new Client();
        try {
            $response = $client->post(
                'https://api.mojasms.dev/login',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'email' => $this->setEmail(),
                        'password' => $this->setPassword(),
                    ],
                ]
            );

            $responseCode = $response->getStatusCode();
            $responseBody = $response->getBody();
            $responseBody = json_decode((string) $responseBody);

            if ($responseCode === 200) {
                return $responseBody->data->token;
            } else {
                return 0;
            }
        } catch (ClientException $e) {
            $res = $e->getResponse();
            $resBody = $res->getBody();
            $resBody = json_decode((string) $resBody);
            if ($res->getStatusCode() === 400) {
                return response()->json(['msg' => 'Bad request'], 400);
            }
        }
    }

    public function checkBalance()
    {
        $authToken = $this->authenticate();
        if ($authToken === 0) return response()->json(['msg' => 'Unauthorized'], 401);

        $client = new Client();
        $response = $client->get(
            'https://api.mojasms.dev/balance',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $authToken,
                    'Accept' => 'application/json',
                ],
            ]
        );

        $responseCode = $response->getStatusCode();
        $responseBody = $response->getBody();
        $responseBody = json_decode((string) $responseBody);

        if ($responseCode === 200) {
            return response()->json($responseBody);
        }
    }

    public function sendSMS(Request $request)
    {

        $requestDataValidation = [
            'phone' => 'required|starts_with:254',
            'message' => 'required|string'
        ];

        $requestData = array(
            'phone' => $request->get('phone'),
            'message' => $request->get('message')
        );

        $validator = Validator::make($requestData, $requestDataValidation);

        if ($validator->fails()) {
            dd('Bad request');
        }




        //Get the authorization token
        $authToken = $this->authenticate();
        if ($authToken === 0) return response()->json(['msg' => 'Unauthorized'], 400);
        $client = new Client();
        $uuid = Uuid::uuid4()->toString();

        try {
            $response = $client->post(
                'https://api.mojasms.dev/sendsms',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $authToken,
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'from' => $this->setSenderId(),
                        'phone' => $requestData['phone'],
                        'message' => $requestData['message'],
                        'message_id' => $uuid,
                        'webhook_url' => $this->setWebhookUrl(route('sms-webhook')),
                    ],
                ]
            );

            dd($response);
            $responseCode = $response->getStatusCode();
            $responseBody = $response->getBody();
            $responseBody = json_decode((string) $responseBody);

            if($responseCode === 200){
                return response()->json(['msg' => 'Message sent successfully'], 200);
            }

        } catch (ClientException $e) {
            $res = $e->getResponse();
            $resBody = $res->getBody();
            $resBody = json_decode((string) $resBody);
            if ($res->getStatusCode() === 400) {
                return response()->json(['msg' => 'Bad request'], 400);
            }
        }
    }

    public function webHookCallBack(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validMessageData = $request->validate([]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function setEmail()
    {
        $email = config('services.mojagate.email');
        return $email;
    }

    private function setPassword()
    {
        $password = config('services.mojagate.password');
        return $password;
    }

    private function setSenderId()
    {
        $senderId = config('services.mojagate.sender_id');
        return $senderId;
    }

    private function setWebhookUrl($url)
    {
        return $url;
    }
}
