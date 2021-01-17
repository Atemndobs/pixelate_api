<?php


namespace App\Services\Exports;


use App\Models\Post;
use Illuminate\Support\Collection;

class PostExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    /**
     * @inheritDoc
     */
    public function collection()
    {
        return Post::all();
    }
}
