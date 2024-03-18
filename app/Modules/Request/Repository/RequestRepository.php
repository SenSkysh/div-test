<?php

namespace App\Modules\Request\Repository;

use App\Modules\Request\DTO\RequestDTO;
use App\Modules\Request\Models\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RequestRepository
{   
    public function getWithFilter(?string $status, ?string $from, ?string $to, string $sort, string $dir, string $count): LengthAwarePaginator
    {   

        $builder = Request::query()
        ->when($status, function ($q) use ($status) {
            return $q->where('status', $status);
        })
        ->when($from, function ($q) use ($from) {
            return $q->where('created_at', '>', $from);
        })
        ->when($to, function ($q) use ($to) {
            return $q->where('created_at', '<', $to);
        });

        $paginator = $builder->orderBy($sort, $dir)->paginate($count);

        $paginator->through(function ($request) {
            return RequestDTO::fromModel($request);
         });

        return $paginator;
    }

    public function store(array $attributes) : RequestDTO
    {   
        $request = new Request($attributes);
        $request->saveOrFail();
        $request->refresh();
        return  RequestDTO::fromModel($request);
    }
    
    public function update(int $id, array $attributes) : RequestDTO
    {
        $request = Request::findOrFail($id);
        $request->fill($attributes);
        $request->saveOrFail();
        $request->refresh();
        return RequestDTO::fromModel($request);;
    }
}