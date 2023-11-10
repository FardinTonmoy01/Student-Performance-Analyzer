<?php
/**
 * Created by VScode.
 * User: Hasinur Rahman
 * Date: 02/06/2023
 * Time: 06:53 PM
 */

namespace App\Helpers;
use Illuminate\Support\Facades\File;
use Validator;

class FileUploadHelper {

    /*Check Dir Permission*/
    public static function is_dir_set_permission($directory){
        if(is_dir(storage_path($directory))) {
            FileUploadHelper::check_permission($directory);
            return true;
        }
        else
        {
            FileUploadHelper::make_directory($directory);
            return true;
        }

    }

   /*Make Diretory*/
    protected static function make_directory($directory) {
        File::makeDirectory(storage_path($directory), 0777, true, true);
        return true;
    }

    /*Check Permission*/
    protected static function check_permission($directory){
        if(is_writable(storage_path($directory)))
        {
            return true;
        }
        else
        {
            FileUploadHelper::set_permission($directory);
            return true;
        }
    }

    /*Set Permission*/
    protected static function set_permission($directory){
        if(!is_dir(storage_path($directory))){
            File::makeDirectory(storage_path($directory), 0777, true, true);
            return true;
        }
        return false;
    }


    /**
     * Delete the file path
     *
     * @param string $oldFile The file path to be deleted
     * @return void
     */
    public static function deleteFile($path, $oldFile)
    {
        $filePathToDelete = storage_path($path. $oldFile);

        if (file_exists($filePathToDelete)) {
            File::delete($filePathToDelete);
        }
    }


    /*++++++++++ Upload File Upload (Without Base64) ++++++++++*/
    public static function global_file_upload ($request, $field_name, $file_path, $old_file = null) {
        $validator = Validator::make([], []); // Empty data and rules fields
        try {
            // $file = $request->file($field_name);
            $file = $request[$field_name];
            $target_path = 'app/public/';
            $destinationPath = $target_path.$file_path;
            
            if ($file) {               
                $file_name = strtotime("now").'-'.uniqid().'-'.rand(0,999999).'.' . $file->getClientOriginalExtension(); 

                Self::is_dir_set_permission($destinationPath);
                $file->move(storage_path($destinationPath), $file_name);
            } 

            if ($old_file != null) {
                Self::deleteFile($target_path, $old_file);
            }

            return [
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => $file_path.$file_name
            ];
        } catch (\Exception $e) {
            return ([
                'success' => false,
                'message' => 'Not validate File',
                'errors' => $validator->errors()->add($field_name, 'Not validate File')
            ]);
        }
    }
}
