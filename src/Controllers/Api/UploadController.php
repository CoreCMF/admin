<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Upload;

class UploadController extends Controller
{
    /** @var configRepository */

    private $uploadModel;

    public function __construct(Upload $UploadRepo)
    {
        $this->uploadModel = $UploadRepo;
    }
    public function index()
    {

    }
    /**
    * [postImageUpload 图片上传].
    * @return [type] [description]
    */
   public function postImage()
   {
       $imageData = Input::all();
       $response = $this->uploadModel->imageUpload($imageData);

       return response()->json($response, 200);
   }

}
