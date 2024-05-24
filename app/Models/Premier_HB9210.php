<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premier_HB9210 extends Model
{
    use HasFactory;

    const FIELD_DELIMITER = '|';
    const ANALYZER_HEADER = 'H|\^&|||PREMIER^HB9210||||||ASTM RECVR|||P|E 1394-97|'; // plus current datetime in format YYYYMMDDHHMMSS // end is indicated by Carriage Return (ascii 13)
    // example
    // H|\^&|||PREMIER^HB9210||||||ASTM RECVR|||P|E 1394-97|20240509160126
    const PATIENT_RECORD = 'P|'; // plus sequence number from 1 to 7 and then repeats // end is indicated by Carriage Return (ascii 13)
    // example
    // P|1
    const ORDER_RECORD_HEAD = 'O|1||'; // sequence number, only One order per patient // plus ORDER_NUMBER scanned from the barcode on the sample
    const ORDER_RECORD_TAIL = '|^^^PREMIER HBA1C|R|||||||||||||||||||TLA^000|F'; // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_1_HEAD = 'R|1|^^^GHb|'; // plus data value // plus FIELD_DELIMITER // plus Units of data (%, ascii 37)
    const RESULT_RECORD_1_TAIL = '||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_2_HEAD = 'R|2|^^^HbA1c|'; // plus data value // plus FIELD_DELIMITER // plus Units of data (%, ascii 37)
    const RESULT_RECORD_2_TAIL = '||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_3_HEAD = 'R|3|^^^AG|'; // plus data value // plus FIELD_DELIMITER // plus Units of data (mg/dl or mmol/L)
    const RESULT_RECORD_3_TAIL = '||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_4_HEAD = 'R|4|^^^mMA1c|'; // plus data value // plus FIELD_DELIMITER // plus Units of data (mMolHbA1c/MolHb)
    const RESULT_RECORD_4_TAIL = '||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_5_HEAD = 'R|5|^^^Code|'; // plus data value (value of 1 to 12, more than one code can be displayed)
    const RESULT_RECORD_5_TAIL = '|||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const RESULT_RECORD_6_HEAD = 'R|6|^^^Data Points|'; // plus data value (Value of integers. Total of 60 averaged data points)
    const RESULT_RECORD_6_TAIL = '|||||F||||'; // plus current datetime in format YYYYMMDDHHMMSS // plus FIELD_DELIMITER // plus FIELD_DELIMITER // end is indicated by Carriage Return (ascii 13)
    const MESSAGE_TERMINATOR = 'L|1'; // end is indicated by Carriage Return (ascii 13) // only send after all records of all the patients have been sent
}
