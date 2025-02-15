<?php
namespace IowaPainel;

class EnvioEmailApiController
{

    public function enviar($from, $subject, $body, $to)
    {

        $curl = curl_init();

        $body = [
            'from' => $from,
            'subject' => $subject,
            'to' => $to,
            'body' => $body,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.smtplw.com.br/v1/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => array(
                'x-auth-token: 56179dbe3f2aeceb0e48cd92024124dc',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        curl_close($curl);

        switch ($curlInfo['http_code']):
            case '201';
                return 'ok';
            default:
                return 'erro';
        endswitch;

//        $data = json_decode($response);
//
//        if($data->data->attributes->status === 'Enfileirado'):
//            return 'ok';
//        else:
//            return 'erro';
//        endif;

    }

}
