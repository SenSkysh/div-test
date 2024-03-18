<?php

namespace App\Modules\Request\Services;


use App\Modules\Base\DTO\PaginateParamsDTO;
use App\Modules\Request\DTO\RequestDTO;
use App\Modules\Request\DTO\RequestIndexDTO;
use App\Modules\Request\DTO\RequestStoreDTO;
use App\Modules\Request\DTO\RequestResolveDTO;
use App\Modules\Request\Mail\RequestResolved;
use App\Modules\Request\Repository\RequestRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class RequestService
{
    public function __construct(
        public RequestRepository $requestRepository,
    ) {
    }

    public function getWithFilter(RequestIndexDTO $dto, PaginateParamsDTO $paginateParams): LengthAwarePaginator
    {
        $requests = $this->requestRepository->getWithFilter(
            $dto->status,
            $dto->from,
            $dto->to,
            $paginateParams->sort,
            $paginateParams->dir,
            $paginateParams->count
        );
        return $requests;
    }

    public function storeRequest(RequestStoreDTO $dto): RequestDTO
    {
        $request = $this->requestRepository->store($dto->toArray());
        return $request;
    }

    public function resolveRequest(RequestResolveDTO $dto): RequestDTO
    {
        $updateData = [
            'comment' => $dto->comment,
            'status' => 'Resolved',
        ];

        $request = $this->requestRepository->update($dto->id, $updateData);

        Mail::mailer('log')->to($request->email)->send(new RequestResolved($request));
        return $request;
    }

}