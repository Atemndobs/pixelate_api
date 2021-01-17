<?php


namespace App\Services\Exports;


use App\Models\User;
use Illuminate\Support\Collection;

class UserExport implements \Maatwebsite\Excel\Concerns\ToCollection
{

    /**
     * @inheritDoc
     */
    public function collection(Collection $collection)
    {
        return User::all();
    }
}
