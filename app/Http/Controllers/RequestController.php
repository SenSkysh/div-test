<?php

namespace App\Http\Controllers;

use App\Mail\RequestResolved;
use App\Models\Request;
use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Http\Requests\IndexRequestRequest;

use Illuminate\Http\Request as HttpRequest;
use App\Http\Resources\RequestResource;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Request::class);
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
    public function index(IndexRequestRequest $request)
    {
        $status = $request->validated('status');
        $from = $request->validated('from');
        $to = $request->validated('to');

        $requests = Request::filtered($status, $from, $to)->paginate()->withQueryString();

        return RequestResource::collection($requests);
    }

    /**
     * @OA\Post(
     *      path="/api/requests",
     *      operationId="storeRequest",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreRequestRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RequestResource")
     *       )
     * )
     */
    public function store(StoreRequestRequest $request)
    {
        $validated = $request->validated();

        $newRequest = new Request($validated);

        $newRequest->saveOrFail();
        $newRequest->refresh();
        return new RequestResource($newRequest);
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateRequestRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RequestResource")
     *       )
     * )
     */
    public function update(UpdateRequestRequest $updateRequest, Request $request)
    {
        $validated = $updateRequest->validated();

        $request->status = 'Resolved';
        $request->comment = $validated['comment'];
        $request->saveOrFail();
        $request->refresh();

        // Mail::to($request->email)->send(new RequestResolved($request));
        Mail::mailer('log')->to($request->email)->send(new RequestResolved($request));

        return new RequestResource($request);
    }

}
