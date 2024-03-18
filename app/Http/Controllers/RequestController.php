<?php

namespace App\Http\Controllers;

use App\Modules\Base\DTO\PaginateParamsDTO;
use App\Modules\Request\DTO\RequestIndexDTO;
use App\Modules\Request\DTO\RequestStoreDTO;
use App\Modules\Request\DTO\RequestResolveDTO;
use App\Modules\Request\Models\Request;
use App\Modules\Request\Requests\RequestStoreRequest;
use App\Modules\Request\Requests\RequestUpdateRequest;
use App\Modules\Request\Requests\RequestIndexRequest;

use App\Modules\Request\Services\RequestService;
use App\Modules\Request\Resources\RequestResource;

class RequestController extends Controller
{

    public function __construct(
        private RequestService $requestService
    )
    {
        $this->authorizeResource(Request::class, Request::class);
    }
    /**
     * @OA\Get(
     *      path="/api/requests",
     *      operationId="getRequestsList",
     *      summary="Get list of requests",
     *      description="Returns list of requests",
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          required=false,
     *          description="Статус заявки",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="from",
     *          in="query",
     *          required=false,
     *          description="Начальная дата фильтрации",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="to",
     *          in="query",
     *          required=false,
     *          description="Конечная дата фильтрации",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(ref="#/components/schemas/RequestResource"))
     *              ),
     *          )
     *      )
     *     )
     */
    public function index(RequestIndexRequest $request)
    {
        $validated = $request->validated();
        $dto = new RequestIndexDTO($validated);
        $paginateParams = PaginateParamsDTO::fromRequest($request);

        $requests = $this->requestService->getWithFilter($dto, $paginateParams);

        return  RequestResource::collection($requests);
    }

    /**
     * @OA\Post(
     *      path="/api/requests",
     *      operationId="storeRequest",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RequestStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *            @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RequestResource")
     *             ),
     *          )
     *       )
     * )
     */
    public function store(RequestStoreRequest $request)
    {
        $validated = $request->validated();
        $dto = new RequestStoreDTO($validated);

        $newRequest = $this->requestService->storeRequest($dto);

        return (new RequestResource($newRequest))->response()->setStatusCode(201);
    }

    /**
     * @OA\Put(
     *      path="/api/requests/{id}",
     *      operationId="updateRequest",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RequestUpdateRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *            @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/RequestResource")
     *             ),
     *          )
     *       )
     * )
     */
    public function update(RequestUpdateRequest $updateRequest, int $id)
    {
        $comment = $updateRequest->validated('comment');
        $dto = new RequestResolveDTO(
            id: $id,
            comment: $comment,
        );

        $request = $this->requestService->resolveRequest($dto);

        return new RequestResource($request);
    }

}
