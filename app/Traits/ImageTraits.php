<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait ImageTraits
{
    public function UploadImage(Request $request, $inputnm, $path){
        if($request->hasFile($inputnm)){
            $image = $request->{$inputnm};
            $ext = $image->getClientOriginalExtension();
            $imgnm = 'media_'.uniqid().'.'.$ext;
            $image->move(public_path($path), $imgnm);
            return $path.'/'.$imgnm;
        }
    }

    public function UpdateImage(Request $request, $inputnm, $path, $oldpath=null){
        if($request->hasFile($inputnm)){
            if(File::exists(public_path($oldpath))){
                File::delete(public_path($oldpath));
            }
            $image = $request->{$inputnm};
            //$imgnm = rand().'_'.$image->getClientOriginalName();   
            $ext = $image->getClientOriginalExtension();
            $imgnm = 'media_'.uniqid().'.'.$ext;
            $image->move(public_path($path), $imgnm);
            return $path.'/'.$imgnm;
        }
    }

    public function deleteImage(string $path){
        if(File::exists(public_path($path))){
            File::delete(public_path($path));
        }
    }

    public function UploadMultiImage(Request $request, $inputnm, $path){
        if($request->hasFile($inputnm)){
            $imgpaths = [];
            $images = $request->{$inputnm};
            foreach($images as $image){
                $ext = $image->getClientOriginalExtension();
                $imgnm = 'media_'.uniqid().'.'.$ext;
                $image->move(public_path($path), $imgnm);
                $imgpaths[] = $path.'/'.$imgnm;
            }            
            return $imgpaths;
        }
    }

}
