<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Http\Resources\LabelResource;
use App\Repositories\LabelRepository;

class LabelController extends Controller
{

    protected LabelRepository $labelRepository;

    public function __construct(LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return LabelResource::collection($this->labelRepository->indexLabels());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\LabelRequest $request
     * @return LabelResource
     */
    public function store(LabelRequest $request)
    {
        return new LabelResource($this->labelRepository->storeLabel($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return LabelResource
     */
    public function show($id)
    {
        return new LabelResource($this->labelRepository->showLabel($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\LabelRequest $request
     * @param int $id
     * @return LabelResource
     */
    public function update(LabelRequest $request, $id)
    {
        return new LabelResource($this->labelRepository->updateLabel($request->validated(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response([
            'success' => $this->labelRepository->deleteLabel($id)
        ]);
    }
}
