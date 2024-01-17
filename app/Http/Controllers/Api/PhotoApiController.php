<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoRequest;

class PhotoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::latest("id")->paginate(10);
        return response()->json($photos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePhotoRequest $request)
    {
        // return $request;
        if ($request->file("photos")) {
            foreach ($request->file("photos") as $key => $photo) {
                $file_name = $photo->store("public");

                Photo::create([
                    "product_id" => $request->product_id,
                    "name" => $file_name,
                ]); 
            }
        }
        return response()->json(["message" => "Photos are uploaded."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::find($id);
        if (is_null($photo)) {
            return response()->json(["message" => "Photo is not found."], 404);
        }
        $photo->delete();
        return response()->json([],204);
    }
}
