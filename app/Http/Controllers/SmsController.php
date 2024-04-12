<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Netgsm\Sms\SmsSend;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function Reservation(Request $request)
    {
        $request->validate([
            'reserve_name' => 'required|string|max:100',
            'reserve_phone' => 'required|string|max:12',
            'reserve_adult' => 'required|integer|min:1',
            'reserve_kid' => 'required|integer|min:0',
        ]);

        $name = $request->reserve_name;
        $phone = $request->reserve_phone;
        $adult = $request->reserve_adult;
        $kid = $request->reserve_kid;

        $reserveTemplate = str_replace(
            ['{name}', '{phone}', '{adult}', '{kid}'],
            [$name, $phone, $adult, $kid],
            '{name} isimli ve {phone} numaralı müşteri, {adult} yetişkin ve {kid} çocuk için rezervasyon talep etmektedir.'
        );

        $smsSent = $this->sendSMS($reserveTemplate);

        if ($smsSent) {
            return response()->json(['message' => 'Size En Kısa Süre İçerisinde Dönüş Yapılacaktır.'], 201);
        } else {
            return response()->json(['message' => 'Rezervasyon Talep Edilirken Bir Hata Oluştu.'], 500);
        }
    }

    public function sendSMS($reserveTemplate)
    {
        $sms = new SmsSend;

        $data = array(
            'msgheader' => env('NETGSM_HEADER'), // env dosyanızda gerekli netgsm key bilgilerinizi ayarlamanız gerekmektedir.
            'gsm' => 'Sms gönderilecek telefon numarası',
            'message' => $reserveTemplate,
            'filter' => '0',
        );

        $smsStatus = $sms->smsgonder1_1($data);

        if ($smsStatus['code'] === '00') {
            \Illuminate\Support\Facades\Log::info('SMS sent successfully to ' . 'Sms gönderilecek telefon numarası');
            return true;
        } else {
            \Illuminate\Support\Facades\Log::info('Failed to send SMS to ' . 'Sms gönderilecek telefon numarası' . ': ' . $smsStatus['aciklama']);
            return false;
        }
    }
}
