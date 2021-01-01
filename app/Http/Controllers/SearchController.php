<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Repositories\Contracts\DesignRepositoryInterface;
use App\Repositories\Eloquent\DesignRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    /**
     * @var DesignRepositoryInterface
     */
    protected DesignRepositoryInterface $designRepository ;

    /**
     * DesignController constructor.
     * @param DesignRepositoryInterface $designRepository
     */
    public function __construct(DesignRepositoryInterface $designRepository)
    {
        $this->designRepository = $designRepository;
    }

    public function index()
    {
        $designs = Design::search(request('term'))->get();
   //     $designs = $this->designRepository->all()->search(request('term'))->get();
        return view('search.index', compact('designs'));
    }
}
