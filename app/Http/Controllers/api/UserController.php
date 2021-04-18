<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Model\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $user = auth()->user();

        if (!$user || $user->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'user is not authorized'
            ], 403);
        }

        return Response(DB::table('userController')->paginate(10));
    }

    public function show($id)
    {
        $user = auth()->user();

        if (!$user || $user->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'user is not authorized'
            ], 403);
        }

        if (auth()->user()) {
            return Response(['status' => 'Success',
                'data' => User::findOrFail($id)]);
        } else {
            return Response(['status' => 'Failed',
                'message' => 'unauthorized user']);
        }
    }

    public function update(UserRequest $request, $id)
    {
        $user = auth()->user();

        if (!$user || $user->role != 'admin') {
            return Response([
                'status' => 'Failed',
                'message' => 'user is not authorized'
            ], 403);
        }


        $request = $request->validate();

        try {
            $user = User::findOrFail($id);

            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->role = isset($request['role']) ? $request['role'] : 'user';
            $user->password = hash('sh64', $request['name']);

            if ($user->update()) {
                return Response(['status' => 'Success',
                    'message' => $request['name'] . " updated!",
                ]);
            } else {
                return Response(['status' => 'Failed',
                    'message' => "I am not sure why, but we massed up!"], 500);
            }
        } catch (QueryException $e) {
            return Response(['status' => 'Failed',
                'message' => "That's not the correct way of updating a user!",
                'debug' => $e]);
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
//            $count = Post::where('user_id', $id)->count();;
//
//        if ($count != 0) {
//            return Response(['status' => 'Failed',
//                'message' => "Category can not be removed! there are postController attached to it"]);
//        }
//
//        $cat = Category::findOrFail($id);
//        if ($cat->delete()) {
//            return Response([
//                'status' => 'Success'
//            ]);
//        } else {
//            return Response([
//                'status'=>'Failed',
//                'message' => "couldn't delete the category"
//            ]);
//        }
    }

}
