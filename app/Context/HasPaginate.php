<?php
/**
 * @author M. Muflih Kholidin <mmuflic@gmail.com>
 * Date: 04.07.2017
 */

namespace App\Context;

use Illuminate\Pagination\Paginator;

trait HasPaginate
{
    protected $request;
    protected $data;

    public function paginate()
    {
        $pageNo = 1;
        $pageSize = 1000;
        if ($this->request && $this->request->has('page')) {
            $pageNo = $this->request->get("page");
        }
        if ($this->request && $this->request->has('size')) {
            $pageSize = $this->request->get("size");
        }
        Paginator::currentPageResolver(function () use($pageNo) {
            return $pageNo;
        });
        return $this->data
            ->paginate($pageSize);
    }
}
