<?php

namespace App\customResponse;

use Illuminate\Support\Facades\Log;

class customResponse
{
    public static function getLanguage($lang){
        try {
            if(!$lang){
                $language=env('APP_TRANSLATION');
            }
            return $language;
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }


    }
    public static function responseValidation($message, $lang)
    {
        try {
            $language=self::getLanguage($lang);

            $validationMessage = trans('messages.' . $message, [], $language);
           
            return $validationMessage;
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }

    public static function responseMessage($message, $lang)
    {
        try {
            $language=self::getLanguage($lang);
            $response =trans('messages.' . $message, [], $language);

            return response()->json(['Mensaje' => $response], 200);
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }

    public static function responseData($data)
    {
        try {
            return response()->json($data);
        } catch (\Throwable $th) {
            Log::info('Error:' . $th->getMessage());
        }
    }
}
