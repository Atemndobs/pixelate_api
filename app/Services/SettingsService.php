<?php


namespace App\Services;

use App\Models\Post;
use App\Services\Exports\PostExport;
use App\Services\Exports\UserExport;
use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Maatwebsite\Excel\Facades\Excel;

class SettingsService
{
    public function getModels()
    {

        $files =  \Storage::disk('models')->files();

        $fileNames = [];
        if (!empty($files)){
            foreach ($files as $file){
                if ($file === 'BaseModel.php'){
                    continue;
                }
                $name = str_replace('.php', '', $file);
                $name = strtolower($name).'s';
                $fileNames[] = $name;
            }
        }
     //   unset($fileNames[0]);
        return $fileNames;
    }

    public function resetLikeTypes($reset, $types)
    {
        if (is_array($types)){
            $types = implode(',', $types);
        }

        if ($reset === true) {
            \Artisan::call("reset:table love_reaction_types");
        }

        \Artisan::call("reaction {$types}");

       return  $this->getTypes();
    }

    public function getTypes()
    {
        return ReactionType::all()->map(function ($type) {
            $name = $type->name;
            return $name;
        });

    }

    public function resetDb()
    {
        $types = [
            "Like",
            "Laugh",
            "Happy",
            "Surprise",
            "Smile"
        ];
        $types = implode(',', $types);

       // \Artisan::call('migrate:fresh');
       // \Artisan::call("reaction {$types}");
      // \Artisan::call('love:register-reacters --model="App\Models\User"');

        return 'reset';
    }

    public function export(string $type, string $model)
    {
        $formats = ['csv','excel','pdf','word'];
        if (!in_array($type, $formats)){
            return response("{$type} is not a valid file format", 419);
        }

        if ($model === 'post') {
           return Excel::download(new PostExport, "{$model}s.{$type}");
        }elseif ($model = 'user'){
            return Excel::download(new UserExport, "{$model}s.{$type}");
        }
    }

/*    public function exportCsv()
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=posts.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $posts = Post::all();
        $pages = $posts;

        //example header
        $postHeader = [
            "id",
            "author",
           // "user",
            "caption",
            "imageUrl",
            "location",
            "love_reactant_id",
            "created_at",
            "updated_at",
        ];

        $callback = function() use ($pages, $postHeader)
        {
            $file = fopen('php://output', 'w');

            // Header Row
            fputcsv($file, $postHeader);

            // Body
            foreach($pages as $page) {
                fputcsv($file,[
                    $page->id,
                    $page->user->name,
                    $page->caption,
                    $page->imageUrl,
                    $page->location,
                    $page->love_reactant_id,
                    $page->created_at,
                    $page->updated_at
                    ]);
            }
            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);

    }*/


}
