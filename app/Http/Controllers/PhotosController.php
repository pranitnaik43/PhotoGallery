<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photos;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function upload(Request $request){
        if($request->hasFile("file")){
            foreach($request->file('file') as $image){
                $fileNameWithExt = $image->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                $path = $image->storeAs('/public/images', $fileNameToStore);

                $photo = new Photos;
                $photo->userID=Auth::id();
                $photo->name=$fileNameToStore;
                $photo->extension=$extension;
                $photo->save();
            }
        }
        // if(count($_FILES["file"]["name"])>0) {
        //     for($i=0; $i<count($_FILES["file"]["name"]); $i++) {
        //         $file_name = $_FILES["file"]["name"][$i];
        //         $tmp_name = $_FILES["file"]["tmp_name"][$i];
        //         $file_array = explode(".", $file_name);
        //         $file_extension = end($file_array);
        //         $fileNameToStore = $file_name.'_'.time().'.'.$file_extension;
        //         $location = 'public/images';
        //         if(move_uploaded_file($tmp_name, $location)){
        //             $photo = new Photos;
        //             $photo->userID=Auth::id();;
        //             $photo->name=$file_name;
        //             $photo->extension=$file_extension;
        //             $photo->save();
        //         }
        //     }
        // }
    }

    public function fetch(){
        $userId = Auth::id();
        $photos = Photos::where('userID', $userId)->orderBy('created_at', 'desc')->get();
        $output = '';
        foreach($photos as $photo){
            $output .= '
                <div class="card" id="photo-container">
                    <div class="card-body" id="photo-body">
                        <img src="/storage/images/'.$photo->name.'" id="photo-og">
                    </div>
                    <div class="card-footer"  id="photo-footer">
                        <button class="btn btn-danger delete-button" id="'.$photo->id.'">Delete</button>
                        <a href="/download2/'.$photo->id.'" class="btn btn-success download-button">Download</a>
                    </div>
                </div>
            ';
            // <button class="btn btn-success download-button" id="'.$photo->id.'">Download</button>
        }
        echo $output;
    }

    public function delete(Request $request){
        $img_id = $request->img_id;
        $photo = Photos::where('id', $img_id)->first();
        $file = 'public/images/'.$photo->name;

        $val = Storage::delete($file);
        if($val){ 
            $photo->delete();
        }
        echo $val;
    }

    // public function download(Request $request){
    //     $img_id = $request->img_id;
    //     $photo = Photos::where('id', $img_id)->first();
    //     $filepath = 'public/images/'.$photo->name;

    //     $headers = array(
    //         'Content-Type: application/pdf',
    //       );

    //     // return Response::download($file, 'filename.pdf', $headers);
    //     // return Storage::download('public/images/', $photo->name, $headers);
    //     // return response()->download($filepath, $headers);
    //     $content = Storage::get($filepath);
    //     return response($content)->header('Content-Type', 'image/*');
    // }
    public function download2($id){
        $img_id = $id;
        $photo = Photos::where('id', $img_id)->first();
        $filepath = 'public/images/'.$photo->name;
        // return $img_id;
        
        // $content = Storage::get($filepath);
        // return response($content)->header('Content-Type', 'image/*');
        return Storage::download($filepath, $photo->name);
    }
}
