<?php

namespace App\Http\Controllers;

use App\Models\Analyzers\Maglumi_4000_Plus;
use App\Models\RequestFromMednet;
use App\Models\ResponseToMednet;
use Aranyasen\Exceptions\HL7ConnectionException;
use Aranyasen\Exceptions\HL7Exception;
use Aranyasen\HL7;
use Aranyasen\HL7\Connection;
use Illuminate\Http\JsonResponse;

class CommunicationController extends Controller
{
    /**
     * @throws HL7ConnectionException
     * @throws HL7Exception
     */
    public function connect(): JsonResponse
    {
        $message = $this->generateHL7Message();

        $ip = '127.0.0.1';
        $port = 12001;

//        $fp = fsockopen("www.example.com", 80, $errno, $errstr, 30);
//        if (!$fp) {
//            echo "$errstr ($errno)<br />\n";
//        } else {
//            $out = "GET / HTTP/1.1\r\n";
//            $out .= "Host: www.example.com\r\n";
//            $out .= "Connection: Close\r\n\r\n";
//            fwrite($fp, $out);
//            while (!feof($fp)) {
//                echo fgets($fp, 128);
//            }
//            fclose($fp);
//        }
//
//        die("Connection closed");

        $connection = new Connection($ip, $port);
        $response = $connection->send($message);

        return new JsonResponse($response);
    }

    /**
     * @throws HL7Exception
     */
    private function generateHL7Message(): HL7\Message
    {
//        $message_1 = HL7::from('MSH|^~\\&|1|')->createMessage();
//        $message_2 = new HL7\Message('MSH|^~\\&|1|\rPID|||abcd|\r');
//        echo $message_1->toString() . "\n";
//        echo $message_2->toString() . "\n";

        return HL7::from('MSH|^~\\&|1|')->createMessage();
    }

    public function sendOrderInfoRequest($orderNumber): JsonResponse
    {
        $analyzer = new Maglumi_4000_Plus();

        $request['header'] = $analyzer::ANALYZER_HEADER . date('Ymd');
        $request['order_info'] = $analyzer::ORDER_INFO_REQUEST_SENT_FROM_ANALYZER_START . $orderNumber . $analyzer::ORDER_INFO_REQUEST_SENT_FROM_ANALYZER_END;
        $request['trailer'] = $analyzer::MESSAGE_TERMINATOR;

        $response = $this->sendOrderInfo($orderNumber);

        $results = $this->sendTestResults($orderNumber, $response['tests']);

        return new JsonResponse([
            'request' => $request,
            'response' => $response,
            'results' => $results,
        ]);
    }

    public function sendOrderInfo($orderNumber): array
    {
        $host = new Maglumi_4000_Plus();
        $test_1 = '25-OH VD II';
        $tests[] = $test_1;
        $test_2 = 'Ferritin';
        $tests[] = $test_2;

        $response['header'] = $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_HEADER;
        $response['patient'] = $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_START . $orderNumber . $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_SEPARATOR . 'John^Doe' . $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_END;
        $response['order_info'] = $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_ORDER_INFO_START . $orderNumber . $host::ORDER_INFO_RESPONSE_SENT_FROM_HOST_ORDER_INFO_SEPARATOR . '^^^' . $test_1 . '\^^^' . $test_2;
        $response['trailer'] = $host::MESSAGE_TERMINATOR;
        $response['tests'] = $tests;

        return $response;
    }

    private function sendTestResults($orderNumber, $tests): array
    {
        $analyzer = new Maglumi_4000_Plus();

        $response['test_1']['header'] = $analyzer::ANALYZER_HEADER . date('Ymd');
        $response['test_1']['patient'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_PATIENT;
        $response['test_1']['order_info'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_START . $orderNumber . $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_END . '^^^25-OH VD II';
        $response['test_1']['test_results'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_START . $tests[0] . '|85.8|nmol/L|30 to 9999|N||||||' . date('YmdHis');
        $response['test_1']['trailer'] = $analyzer::MESSAGE_TERMINATOR;

        $response['test_2']['header'] = $analyzer::ANALYZER_HEADER . date('Ymd');
        $response['test_2']['patient'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_PATIENT;
        $response['test_2']['order_info'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_START . $orderNumber . $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_END . '^^^Ferritin';
        $response['test_2']['test_results'] = $analyzer::TEST_RESULT_SENT_FROM_ANALYZER_START . $tests[1] . '|27.1|ng/ml|7 to 425|N||||||' . date('YmdHis');
        $response['test_2']['trailer'] = $analyzer::MESSAGE_TERMINATOR;

        return $response;
    }

    public function getRequestFromMednet(): JsonResponse
    {
        $requests = [];
        // get data from request_from_mednet.txt file, data fields are separated by ,
        $data = file_get_contents(storage_path('request_from_mednet.txt'));
        $lines = explode("\n", $data);

        // create foreach loop to fill the $request object with data from the file
        foreach ($lines as $line) {
            $data = explode(',', $line);
            if (count($data) > 1) {
                $request = new RequestFromMednet();
                foreach ($request->getFillable() as $key => $value) {
                    $request->$value = str_replace('"', "", $data[$key + 1]);
                }
                $requests[] = $request;
            }
        }

        // return as JSON
        return new JsonResponse($requests);
    }

    public function sendResponseToMednet(): JsonResponse
    {
        $responses = [];
        // get data from response_to_mednet.txt file, data fields are separated by ,
        $data = file_get_contents(storage_path('response_to_mednet.txt'));
        $lines = explode("\n", $data);

        // create foreach loop to fill the $response object with data from the file
        foreach ($lines as $line) {
            $data = explode(',', $line);
            if (count($data) > 1) {
                $response = new ResponseToMednet();
                foreach ($response->getFillable() as $key => $value) {
                    $response->$value = $data[$key] == '""' ? null : str_replace('"', "", $data[$key]);
                }
                $responses[] = $response;
            }
        }

        // return as JSON

        return new JsonResponse($responses);
    }
}
