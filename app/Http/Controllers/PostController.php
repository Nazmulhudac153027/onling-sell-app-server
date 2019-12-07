<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Image;

class PostController extends Controller
{
    public function index()
    {
        $Posts = Post::latest()->get();
        return PostResource::collection($Posts);
    }
    public function search(Request $request)
    {

        return $request;
        $Posts = Post::latest()->get();
        return PostResource::collection($Posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function imageCustomization($request)
    {
        $url = '';

        $exploded = explode(',', $request);
        $decoded = base64_decode($exploded[1]);
        if (str_contains($exploded[0], 'png')) {
            $ext = 'png';
        } else if (str_contains($exploded[0], 'jpeg')) {
            $ext = 'jpeg';
        }else if (str_contains($exploded[0], 'jpg')) {
            $ext = 'jpg';
        } else {
            $ext = 'jpg';
        }
        $image_name = time() . '.' . $ext;

        $upload_path = public_path() . '/images/';
        $url = $upload_path . $image_name;

        Image::make($request)->resize(200, 200)->save($url);
        Image::make($request)->save($url);
        return $url;
    }

    public function store(Request $request)
    {
        //$this->validation($request);
        $url = $this->imageCustomization($request->image);
        return Post::create($request->except('image') + [
                'image' => $url
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        if (!is_null(post::find($id))) {
            return response()->json([
                'post' => post::find($id)
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $row = post::findOrFail($id);
        if (!is_null($row)) {
            $row->name = $request->name;
            $row->description = $request->description;
            $row->update();
            return response()->json([
                'post' => $row
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row = post::findOrFail($id);
        if (!is_null($row)) {
            post::destroy($id);
        }
    }
}
