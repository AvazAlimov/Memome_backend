<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Middleware;

use App\Account;
use Closure;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $account = Account::where('uid', $request->get('account'))->first();
        if (!$account) {
            return response()->json([], 401);
        }
        $request->merge(['account' => $account->id]);
        return $next($request);
    }
}
