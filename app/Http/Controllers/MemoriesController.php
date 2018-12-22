<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers;

use App\Memory;
use App\Photo;
use App\Rules\AccountRule;
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
}
