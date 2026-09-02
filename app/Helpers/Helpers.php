<?php
    if (!function_exists('api_call')) {
        function api_call($url, $method, $data)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_HTTPHEADER => [
                    'accept: application/json',
                    'content-type: application/json'
                ],
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => json_encode($data),
            ));

            $response = curl_exec($curl);
            $response = json_decode($response);
            // dd($response);
            curl_close($curl);
            return $response;
        }
    }
