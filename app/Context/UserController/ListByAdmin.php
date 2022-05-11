<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

namespace App\Context\UserController;

use App\Context\HasPaginate;
use App\Context\PagedList;
use App\Context\Reader;
use App\Models\User;
use Illuminate\Http\Request;

class ListByAdmin implements Reader
{
    use HasPaginate;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = User::orderBy('name', 'asc');
    }

    public function read()
    {
        $paginate = $this->paginate();
        $data = [];

        foreach ($paginate->items() as $item) {
            $item->email;
            $data[] = $item;
        }
        return PagedList::fromPaginator($data, $paginate);
    }
}
