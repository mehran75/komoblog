<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Model\Label;
use App\Model\PostLabel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class labels extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response([
            'status' => 'Success',
            'data' => Label::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\LabelRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabelRequest $request)
    {
        $request = $request->validated();


        if (Auth::user()->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'Unauthorized user'
            ], 401);
        }


        $label = new Label;
        $label->name = $request['name'];
        $label->created_by_id = Auth::id();

        try {
            $label->saveOrFail();

            return Response([
                'status' => 'Success',
                'data' => $label,
            ]);

        } catch (QueryException $e) {
            return Response([
                'status' => 'Failed',
                'message' => 'failed to create your requested label',
                'debug' => $e
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Response(Label::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\LabelRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(LabelRequest $request, $id)
    {
        $request = $request->validated();


        if (Auth::user()->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'Unauthorized user'
            ], 401);
        }


        $label = Label::findOrFail($id);
        $label->name = $request['name'];

        try {
            if ($label->update()) {
                return Response([
                    'status' => 'Success',
                    'data' => $label,
                ]);
            } else {
                return Response([
                    'status' => 'Failed',
                    'message' => 'failed to update the label!'
                ], 500);
            }

        } catch (QueryException $e) {
            return Response([
                'status' => 'Failed',
                'message' => 'failed to create your requested label',
                'debug' => $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (Auth::user()->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'Unauthorized user'
            ], 401);
        }

        try {

            $count = PostLabel::where('label_id', $id)->count();;

            if ($count != 0) {
                return Response(['status' => 'Failed',
                    'message' => "Label can not be removed! there are posts attached to it"]);
            }

            $label = Label::findOrFail($id);
            if ($label->delete()) {
                return Response([
                    'status' => 'Success'
                ]);
            } else {
                return Response([
                    'status' => 'Failed',
                    'message' => "couldn't delete the Label"
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Label not found',
                'debug' => $e]);
        }
    }
}
