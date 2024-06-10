<?php

namespace App\Models\Analyzers;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maglumi_4000_Plus extends Model
{
    use HasFactory;
// ------------------------------- For converting Mednet order to analyzer order -------------------------------
    const DOUBLE_SEPARATOR = '||';
    const TRIPLE_SEPARATOR = '|||';

    // host sending the order information response
    const ANALYZER_HEADER_MIN = 'H|\^&'; // is sent to the device from host computer
    const ORDER_INFO_RESPONSE_PATIENT_START = 'P|1||'; // coming from host plus ORDER_NUMBER requested
    const ORDER_INFO_RESPONSE_ORDER_START = 'O|1|'; // coming from host plus ORDER_NUMBER requested
    const MESSAGE_TERMINATOR = 'L|1|N';

    // example:
    // H|\^&|
    //P|1||5498014085||John^Doe|||M
    //O|1|5498014085||^^^Test1\^^^Test2\^^^Test3
    //L|1|N

// ---------------------------------------------------------------------------------------------------------------
// ------------------------------- Request for order information -------------------------------
    const ANALYZER_HEADER = 'H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|'; // plus YYYYMMDD // coming from device to host computer

    // host receiving the order request
    const ORDER_INFO_REQUEST_START = 'Q|1|^'; // plus ORDER_NUMBER scanned from the barcode on the sample
    const ORDER_INFO_REQUEST_END = '||ALL||||||||O';
//    const MESSAGE_TERMINATOR = 'L|1|N';

    // example:
    // H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|20240509
    //Q|1|^5498014085||ALL||||||||O
    //L|1|N

// ---------------------------------------------------------------------------------------------------------------
// ------------------------------- Sending tests results -------------------------------
    // analyzer sending tests results
//    const ANALYZER_HEADER = 'H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|'; // plus YYYYMMDD // coming from device to host computer
    const TEST_RESULT_SENT_FROM_ANALYZER_PATIENT = 'P|1'; // coming from Maglumi 4000 Plus
    const TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_START = 'O|1|'; // coming from Maglumi 4000 Plus plus ORDER_NUMBER scanned from the barcode on the sample
    const TEST_RESULT_SENT_FROM_ANALYZER_START = 'R|1|^^^'; // coming from Maglumi 4000 Plus plus TEST_NAME
//    const MESSAGE_TERMINATOR = 'L|1|N';

    // RESULTS SEGMENTS
    // R|1|^^^25-OH VD II|85.8|nmol/L|30 to 9999|N||||||20240509160126
    // R|1|^^^Ferritin|27.1|ng/ml|7 to 425|N||||||20240509160518

    // example with 2 tests:

    // H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|20240509
    //P|1
    //O|1|5498014085||^^^25-OH VD II
    //R|1|^^^25-OH VD II|85.8|nmol/L|30 to 9999|N||||||20240509160126
    //L|1|N

    // H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|20240509
    //P|1
    //O|1|5498014085||^^^Ferritin
    //R|1|^^^Ferritin|27.1|ng/ml|7 to 425|N||||||20240509160518
    //L|1|N

// ---------------------------------------------------------------------------------------------------------------

//    public function sendOrderInfoRequest(string $orderNumber): string
//    {
//        $request = self::ANALYZER_HEADER . date('Ymd') . PHP_EOL;
//        $request .= self::ORDER_INFO_REQUEST_START . $orderNumber . self::ORDER_INFO_REQUEST_END . PHP_EOL;
//        $request .= self::MESSAGE_TERMINATOR;
//        return $request;
//    }
//
//    public function sendOrderInfoResponse(string $orderNumber, array $tests, string $patientSex = 'U', string $patientName = 'nenustatyta^nenustatyta'): string
//    {
//        $response = self::ANALYZER_HEADER_MIN . PHP_EOL;
//        $response .= self::ORDER_INFO_RESPONSE_PATIENT_START . $orderNumber . self::DOUBLE_SEPARATOR . $patientName . self::TRIPLE_SEPARATOR . $patientSex . PHP_EOL;
//        $response .= self::ORDER_INFO_RESPONSE_ORDER_START . $orderNumber . self::DOUBLE_SEPARATOR;
//        foreach ($tests as $key => $value) {
//            $response .= $value;
//            if ($key < count($tests) - 1) {
//                $response .= '\^^^';
//            }
//        }
//        $response .= PHP_EOL;
//        $response .= self::MESSAGE_TERMINATOR;
//        return $response;
//    }



    public function convertMednetOrderToAnalyzerOrder(array $mednetOrder): string|array
    {
        $orderNumber = $mednetOrder['barcode'];
        $basicAnalyzer = new BasicAnalyzer();
        $analyzer_tests = $basicAnalyzer->prepareAnalyzerTests($mednetOrder['test_id']);

        $patientSex = 'U';
        $patientName = 'Undefined^Undefined';

        $response = self::ANALYZER_HEADER_MIN . PHP_EOL;
        $response .= self::ORDER_INFO_RESPONSE_PATIENT_START . $orderNumber . self::DOUBLE_SEPARATOR . $patientName . self::TRIPLE_SEPARATOR . $patientSex . PHP_EOL;
        $response .= self::ORDER_INFO_RESPONSE_ORDER_START . $orderNumber . self::DOUBLE_SEPARATOR;
        $response .= '^^^';
        foreach ($analyzer_tests as $key => $value) {
            $response .= $value;
            if ($key < sizeof($analyzer_tests) - 1) {
                $response .= '\^^^';
            }
        }
        $response .= PHP_EOL;
        $response .= self::MESSAGE_TERMINATOR;

        return $response;
    }

}
