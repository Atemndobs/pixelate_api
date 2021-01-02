<?php


namespace App\Services;


use Illuminate\Support\Facades\File;

class SettingsService
{
    public function getModels()
    {
       if (!realpath (  '/app/model' ) ) {
           $files= \File::allFiles('/Users/b.atemkeng/sites/pixelate/app/Models');
       }else{
           $files = File::allFiles("/app/model");
       }

        $fileNames = [];
        if (!empty($files)){
            foreach ($files as $file){
                if ($file->getType() === 'file') {
                    $fullName = $file->getBasename();
                    if ($fullName === 'BaseModel.php'){
                        continue;
                    }
                    $name = str_replace('.php', '', $fullName);
                    $name = strtolower($name).'s';
                }
                $fileNames[] = $name;
            }
        }
     //   unset($fileNames[0]);
        return $fileNames;
    }
}
