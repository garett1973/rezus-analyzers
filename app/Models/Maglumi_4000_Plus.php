<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maglumi_4000_Plus extends Model
{
    use HasFactory;

    const FIELD_DELIMITER = '|';
    const MESSAGE_TERMINATOR = 'L|1|N';
    const ANALYZER_HEADER = 'H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|'; // coming from Maglumi 4000 Plus plus YYYYMMDD

    // host receiving the order request
    const ORDER_INFO_REQUEST_SENT_FROM_ANALYZER_START = 'Q|1|^'; // coming from Maglumi 4000 Plus plus ORDER_NUMBER scanned from the barcode on the sample
    const ORDER_INFO_REQUEST_SENT_FROM_ANALYZER_END = '||ALL||||||||O';

    // example:
    // H|\^&||PSWD|Maglumi User|||||Lis||P|E1394-97|20240509
    //Q|1|^5498014085||ALL||||||||O
    //L|1|N

    // host sending the order information response
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_HEADER = 'H|\^&'; // coming from host
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_START = 'P|1||'; // coming from host plus ORDER_NUMBER requested
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_SEPARATOR = '||'; // coming from host plus patient name^surname
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_END = '|||'; // coming from host plus patient SEX
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_ORDER_INFO_START = 'O|1|'; // coming from host plus ORDER_NUMBER requested
    const ORDER_INFO_RESPONSE_SENT_FROM_HOST_ORDER_INFO_SEPARATOR = '||'; // coming from host plus TEST_NAME or NAMES separated by \^^^

    // example:
    // H|\^&|
    //P|1||5498014085||John^Doe|||M
    //O|1|5498014085||^^^Test1\^^^Test2\^^^Test3
    //L|1|N

    // analyzer sending tests results
    const TEST_RESULT_SENT_FROM_ANALYZER_PATIENT = 'P|1'; // coming from Maglumi 4000 Plus
    const TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_START = 'O|1|'; // coming from Maglumi 4000 Plus plus ORDER_NUMBER scanned from the barcode on the sample
    const TEST_RESULT_SENT_FROM_ANALYZER_ORDER_INFO_END = '||'; // coming from Maglumi 4000 Plus plus TEST_NAME
    const TEST_RESULT_SENT_FROM_ANALYZER_START = 'R|1|^^^'; // coming from Maglumi 4000 Plus plus TEST_NAME
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
}
