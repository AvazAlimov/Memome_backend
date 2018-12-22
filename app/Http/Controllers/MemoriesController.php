<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers;

use App\Memory;
use App\Photo;
use App\Rules\AccountRule;
use App\Rules\MemoryRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemoriesController extends Controller
{
    const FILE_SIZE = "20000";

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "account" => ["required", new AccountRule],
            "title" => "required",
            "content" => "required",
            "pictures.*" => "mimes:jpeg,jpg,png,gif|max:" . self::FILE_SIZE,
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $memory = Memory::create([
            "title" => $request->get('title'),
            "content" => $request->get('content'),
            "account" => $request->get('account')
        ]);
        if ($request->get('date')) {
            $memory->date = $request->get('date');
            $memory->save();
        }
        if ($request->file('pictures')) {
            foreach ($request->file('pictures') as $photo) {
                $path = $photo->storeAs("public/", $photo->hashName());
                $path = str_replace("public/", "", $path);
                Photo::create([
                    "filename" => $path,
                    "memory" => $memory->id
                ]);
            }
        }
        $memory->normalize();
        return response()->json($memory, 200);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "id" => ["required", new MemoryRule],
            "account" => ["required", new AccountRule],
            "title" => "required",
            "content" => "required",
            "pictures.*" => "mimes:jpeg,jpg,png,gif|max:" . self::FILE_SIZE,
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $memory = Memory::find($request->get("id"));
        $memory->title = $request->get("title");
        $memory->content = $request->get("content");
        $memory->save();

        if ($request->get('date')) {
            $memory->date = $request->get('date');
            $memory->save();
        }
        if ($request->file('pictures')) {
            foreach ($request->file('pictures') as $photo) {
                $path = $photo->storeAs("public/", $photo->hashName());
                $path = str_replace("public/", "", $path);
                Photo::create([
                    "filename" => $path,
                    "memory" => $memory->id
                ]);
            }
        }
        $memory->normalize();
        return response()->json($memory, 200);
    }

    public function get(Request $request)
    {
        $memories = Memory::where('account', $request->get('account'))->get();
        foreach ($memories as $memory) {
            $memory->normalize();
        }
        return response()->json($memories, 200);
    }

    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "id" => ["required", new MemoryRule],
            "account" => ["required", new AccountRule]
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }
        Memory::destroy($request->get("id"));
        return response()->json([], 200);
    }
}
