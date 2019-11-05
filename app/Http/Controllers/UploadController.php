<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;

class UploadController extends Controller
{
    public function store(Request $request){

    }
    public function uploadForm(){
        return view('upload.form');
    }
    public function uploadSubmit(UploadRequest $request){
        
        $file = $request->file('file');
        
    }
}
