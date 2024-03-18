<?php
namespace App\Modules\Base\DTO;

use Illuminate\Http\Request as HTTPRequest;

class PaginateParamsDTO extends BaseDTO
{
    public $sort;
    public $dir;
    public $count;

    public static function fromRequest(HTTPRequest $request, $defaultSort = 'id', $defaultDir = 'desc', $defaultCount = 25): self
    {
        $sort = $request->sort ?? $defaultSort;
        $dir = $request->dir ?? $defaultDir;
        $count = $request->count ?? $defaultCount;

        if ($dir === 'null') {
            $dir = $defaultDir;
        }
        if ($sort === 'null') {
            $sort = $defaultSort;
        }
        if ($count === 'null') {
            $count = $defaultCount;
        }


        return new self(sort: $sort, dir: $dir, count: $count);
    }

}