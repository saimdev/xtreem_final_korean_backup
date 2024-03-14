<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Onboarding;
use App\Http\Controllers\Integration;
use App\Http\Controllers\Transactions;
use App\Http\Controllers\Loader;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth.user'])->group(function () {
    Route::get('/', [Loader::class, 'dashboard']);
    Route::get('/res/gameList', [Loader::class, 'load_games']);
    Route::get('/game-launch/{vendorName}/{gameId}', [Integration::class, 'game_launch']);
    Route::get('/res/charge', [Transactions::class, 'charge_request']);
    Route::get('/res/exchange', [Transactions::class, 'exchange_request']);
    Route::get('/getGame/{vendorName}', [Integration::class, 'game_list']);
    Route::get('/chargereq', [Transactions::class, 'charge_balance']);
    Route::get('/exchangereq', [Transactions::class, 'exchange_balance']);
    // Route::get('/res/bettinglist', [Transactions::class, 'get_transactions_all']);
    Route::get('/res/bettinglist', [Transactions::class, 'get_user_transactions']);
    // Route::get('/res/bettinglist', [Transactions::class, 'transactions_new']);
});

Route::get('signin',[Onboarding::class, 'login']);

Route::get('/login',function(){return view('welcome');});

Route::get('logout',[Onboarding::class, 'logout']);

Route::get('/balance', function(Request $request){
    $username = $request->query('username');
    $user = User::where('email', $username)->first();

    if ($user) {
        return response()->json(['balance' => floatval($user->balance)]);
    } else {
        return response()->json(['error' => 'User not found'], 404);
    }
});


Route::post('/changeBalance', function (Request $request)
{
    $data = $request->json()->all();

    $username = $data['username'] ?? null;
    $amount = floatval($data['amount']) ?? 0;
    $targetBalance = floatval($data['transaction']['target']['balance']) ?? null;
    $targetUsername = $data['transaction']['target']['username'] ?? null;
    $transactionId = $data['transaction']['id']?? null;
    // dd($data['transaction']['id']);
    if ($username === $targetUsername && $targetBalance !== null) {
        $existingTransaction = DB::table('transactions')->where('tid', $transactionId)->first();
        if (!$existingTransaction) {
            if(isset($data['transaction']['details']['game']['type']) && isset($data['transaction']['details']['game']['vendor']) && isset($data['transaction']['details']['game']['title'])){
                $transaction = new Transaction([
                    'userno' => 1,
                    'userid' => $data['transaction']['target']['id'],
                    'tid' => $data['transaction']['id'],
                    'type' => $data['transaction']['type'],
                    'amount' => $data['amount'],
                    'before' => $data['transaction']['target']['balance'],
                    'status' => $data['transaction']['type'],
                    'gameid' => $data['transaction']['details']['game']['id'],
                    'gametype' => $data['transaction']['details']['game']['type'],
                    'gameround' => $data['transaction']['details']['game']['round'],
                    'gametitle' => $data['transaction']['details']['game']['title'],
                    'gamevendor' => $data['transaction']['details']['game']['vendor'],
                    'detail' => 0, 
                    'detailUpdate' => 0,
                    'created_at' => Carbon::parse($data['transaction']['processed_at']),
                    'updated_at' => substr($data['transaction']['processed_at'], 0, -9),
                    'deleted_at' => substr($data['transaction']['processed_at'], 0, -9),
                    'processed_at' => Carbon::parse($data['transaction']['processed_at']),
                ]);
                $transaction->save();
            }

            else{
                $transaction = new Transaction([
                    'userno' => 1,
                    'userid' => $data['transaction']['target']['id'],
                    'tid' => $data['transaction']['id'],
                    'type' => $data['transaction']['type'],
                    'amount' => $data['amount'],
                    'before' => $data['transaction']['target']['balance'],
                    'status' => $data['transaction']['type'],
                    'gameid' => $data['transaction']['details']['game']['id'],
                    'gametype' => 'n.a',
                    'gameround' => $data['transaction']['details']['game']['round'],
                    'gametitle' => 'n.a',
                    'gamevendor' => 'n.a',
                    'detail' => 0, 
                    'detailUpdate' => 0,
                    'created_at' => Carbon::parse($data['transaction']['processed_at']),
                    'updated_at' => substr($data['transaction']['processed_at'], 0, -9),
                    'deleted_at' => substr($data['transaction']['processed_at'], 0, -9),
                    'processed_at' => Carbon::parse($data['transaction']['processed_at']),
                ]);
                $transaction->save();
            }
        }
        else{
            if (isset($data['external']) && isset($data['external']['detail'])) {
                DB::update(
                    'UPDATE transactions 
                    SET detail = ?, detailUpdate = ? 
                    WHERE tid = ? AND detailUpdate <> ?',
                    [$data['external']['detail'], 1, $data['id'], 1]
                );
            }
        }
        $user = DB::table('users')->where('email', $username)->first();
        if ($user) {
            $currentBalance = $user->balance;
            $updatedBalance = $currentBalance + $amount;
            DB::table('users')->where('email', $username)->update(['balance' => $updatedBalance]);
            return response()->json(['balance' => $updatedBalance], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
    return response()->json(['error' => 'Invalid request data'], 400);
});

// Route::post('signup',[Onboarding::class, 'register']);
// Route::get('/register',function(){
//     return view('register');
// });










