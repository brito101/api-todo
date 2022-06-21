<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $array = ['error' => ''];
        $array['list'] = Todo::simplePaginate(2);
        return $array;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $array = ['error' => ''];
        $rules = [
            'title' => 'required|min:3'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');
        $todo = new Todo();
        $todo->title = $title;
        $todo->save();

        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $array = ['error' => ''];
        $todo = Todo::find($id);
        if ($todo) {
            $array['todo'] = $todo;
        } else {
            $array['error'] = "Task {$id} not found";
        }

        return $array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $array = ['error' => ''];
        $rules = [
            'title' => 'min:3',
            'done' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');
        $done = $request->input('done');
        $todo = Todo::find($id);
        if ($todo) {
            if ($title) {
                $todo->title = $title;
            }
            if ($done !== null) {
                $todo->done = $done;
            }
            $todo->update();
            $array['todo'] = $todo;
        } else {
            $array['error'] = "Task {$id} not found";
        }

        return $array;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $array = ['error' => ''];

        $todo = Todo::find($id);
        if ($todo) {
            $todo->delete();
            $array['response'] = "Task {$id} destroyed";
        } else {
            $array['error'] = "Task {$id} not found";
        }

        return $array;
    }
}
