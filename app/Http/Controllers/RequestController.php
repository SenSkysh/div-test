<?php

namespace App\Http\Controllers;

use App\Mail\RequestResolved;
use App\Models\Request;
use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Http\Requests\IndexRequestRequest;

use Illuminate\Http\Request as HttpRequest;
use App\Http\Resources\Request as RequestResource;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Request::class);
    }
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestRequest $request)
    {
        $validated = $request->validated();

        $newRequest = new Request($validated);

        $newRequest->saveOrFail();
        return new RequestResource($newRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequestRequest $updateRequest, Request $request)
    {
        $validated = $updateRequest->validated();

        $request->status = 'Resolved';
        $request->comment = $validated['comment'];
        $request->saveOrFail();

        // Mail::to($request->email)->send(new RequestResolved($request));
        Mail::mailer('log')->to($request->email)->send(new RequestResolved($request));

        return new RequestResource($request);
    }

}
