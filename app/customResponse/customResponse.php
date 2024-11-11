<?php

namespace App\CustomResponse;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class CustomResponse
{
    public static function getLanguage($lang)
    {
        try {
            if (!$lang) {
                return env('APP_TRANSLATION');
            }
            return $lang;
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }
    public static function responseValidation($message, $lang)
    {
        try {
            $language = self::getLanguage($lang);

            $validationMessage = trans('messages.' . $message, [], $language);

            return $validationMessage;
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }

    public static function responseMessage($message, $status, $lang)
    {
        try {
            $language = self::getLanguage($lang);

            $response = trans('messages.' . $message, [], $language);

            return response()->json(['Mensaje' => $response], $status);
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }

    public static function responseData($data, $status, $encr)
    {
        try {
            if (!$encr || $encr == false) {
                $dataEncript = $data;
            } else {
                $dataEncript = Crypt::encryptString(json_encode($data));
            }
            return response()->json($dataEncript, $status);
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }
    public static function responseSocial($message,$data,$status,$encr){
        try {
            if (!$encr || $encr == false) {
                $dataEncript = $data;
            } else {
                $dataEncript = Crypt::encryptString(json_encode($data));
            }
            return response()->json($dataEncript, $status);
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }
}
