<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountsController extends Controller
{
    public function signup(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required|unique:accounts",
            "password" => "required|min:6"
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $account = Account::create([
            "uid" => chr(mt_rand(97, 122)) . substr(md5(time()), 1),
            "username" => $request->get("username"),
            "password" => bcrypt($request->get("password"))
        ]);

        return response()->json($account, 200);
    }

    public function signin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required"
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $account = Account::where("username", $request->get("username"))->first();
        if (!$account) {
            return response()->json([], 404);
        }

        return response()->json($account, 200);
    }
}
