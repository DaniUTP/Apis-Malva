<?php

namespace App\Http\Controllers;

use App\CustomResponse\CustomResponse;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadImageController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/v1/upload",
 *     summary="Carga de imagen",
 *     security={{"bearerAuth":{}}},
 *     description="Carga de imagen",
 *     tags={"Image Upload"},
 *     @OA\Parameter(
 *         name="lang",
 *         in="query",
 *         description="Language parameter",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="encr",
 *         in="query",
 *         description="Encryption flag",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Image file to upload",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="image",
 *                     description="Image file to upload",
 *                     type="string",
 *                     format="binary"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Image uploaded successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="url", type="string", description="URL of the uploaded image")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="internalError")
 *         )
 *     )
 * )
 */
    public function uploadImage(Request $request)
    {
            $language=$request->query('lang');
            $encript=$request->query('encr');
        try {
            Configuration::instance(env('URL_API_IMAGE'));
            $upload = new UploadApi();
            $originalName=pathinfo($request->file('image')->getClientOriginalName(),PATHINFO_FILENAME);
            $image = $request->file('image')->getRealpath();
            $uploadImage = $upload->upload($image, ['folder' => 'imagenes','public_id'=>$originalName,'format' => 'webp']);
            return CustomResponse::responseData(['url'=>$uploadImage['url']],200,$encript);
        } catch (\Throwable $th) {
            Log::info("Error: " . $th->getMessage());
            return CustomResponse::responseMessage('internalError', 500, $language);
        }
    }
}