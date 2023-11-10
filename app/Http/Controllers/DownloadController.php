<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadFile(Request $request){
      $file = $request->file;
      if (file_exists(storage_path('/app/public/'.$file))) {
        return response()->download(storage_path('/app/public/'.$file));
      } else {
        return response([
          'success' => false,
          'message' => 'Not Found',
        ]);
      }
    }
}
