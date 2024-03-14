<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class Transactions extends Controller
{

    function charge_balance(Request $req)
    {
        $client = new Client();
        $amount = intval($req->charge);
        $username = Auth::user()->email;
        try {
            
            $url = "https://api.honorlink.org/api/user/add-balance";
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'username' => $username,
                    'amount' => $amount,
                ],
            ]);
            $balance = $response->getBody();
            $newBalance = json_decode($balance)->balance;
            $user = User::where('email', Auth::user()->email)->first();
            $user->balance = $newBalance;
            $user->save();
            if ($balance){
                return redirect()->back()->with('message', 'Successfully added');
            }
            else{
                return redirect()->back()->with('error', 'Error');
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function charge_request()
    {
        $user = $this->get_user();
        return view('chargerequest', ['userDetails' => $user]);
    }

    function exchange_request()
    {
        $user = $this->get_user();
        return view('exchangerequest', ['userDetails' => $user]);
    }

    function get_user()
    {
        $client = new Client();
        $username = Auth::user()->email;
        try {
            
            $url = "https://api.honorlink.org/api/user";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'username' => $username,
                ],
            ]);
            $userDetails = $response->getBody();
            $balance = json_decode($userDetails)->balance;
            $user = User::where('email', Auth::user()->email)->first();
            $user->balance = $balance;
            $user->save();
            return $userDetails;
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function exchange_balance(Request $req)
    {
        $client = new Client();
        $amount = intval($req->charge);
        $username = Auth::user()->email;
        try {
            $url = "https://api.honorlink.org/api/user/sub-balance";
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'username' => $username,
                    'amount' => $amount,
                ],
            ]);
            $balance = $response->getBody();
            $newBalance = json_decode($balance)->balance;
            $user = User::where('email', Auth::user()->email)->first();
            $user->balance = $newBalance;
            $user->save();
            if ($balance){
                return redirect()->back()->with('message', 'Successfully added');
            }
            else{
                return redirect()->back()->with('error', 'Error');
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function get_transactions_all()
    {
        $totalTransactions = Transaction::count();
        $transactions = Transaction::orderBy('no', 'desc')
            ->paginate(10);

        return view('bettinglist', compact('transactions', 'totalTransactions'));
    }

    public function transactions_new()
    {
        $client = new Client();
        $start = now()->subDays(14)->format('Y-m-d H:i:s');
        $end = now()->addHours(1)->format('Y-m-d H:i:s');
        $perPage = 1000;
        $page = 1;
        $oldTransactions = Transaction::where('created_at', '<', now()->subDays(14))->get();
        foreach ($oldTransactions as $oldTransaction) {
            $oldTransaction->forceDelete();
        }
        try {
            do {
                $url = "https://api.honorlink.org/api/transactions";
                $response = $client->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer TgeQK2POExchRm2FoWNHeTHjS6LlseeTDwwxjcsp',
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'query' => [
                        'start' => $start,
                        'end' => $end,
                        'page' => $page,
                        'perPage' => $perPage,
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                if (isset($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $transactionData) {
                        if ($transactionData['type'] === 'bet' || $transactionData['type'] === 'win') {
                            $existingTransaction = DB::table('transactions')->where('tid', $transactionData['id'])->first();
                            if (!$existingTransaction) {
                                if(isset($transactionData['details']['game']['type']) && isset($transactionData['details']['game']['vendor']) && isset($transactionData['details']['game']['title'])){
                                    $transaction = new Transaction([
                                        'userno' => 1,
                                        'userid' => $transactionData['user']['id'],
                                        'tid' => $transactionData['id'],
                                        'type' => $transactionData['type'],
                                        'amount' => $transactionData['amount'],
                                        'before' => $transactionData['before'],
                                        'status' => $transactionData['status'],
                                        'gameid' => $transactionData['details']['game']['id'],
                                        'gametype' => $transactionData['details']['game']['type'],
                                        'gameround' => $transactionData['details']['game']['round'],
                                        'gametitle' => $transactionData['details']['game']['title'],
                                        'gamevendor' => $transactionData['details']['game']['vendor'],
                                        'detail' => 0, 
                                        'detailUpdate' => 0,
                                        'created_at' => Carbon::parse($transactionData['created_at']),
                                        'updated_at' => substr($transactionData['created_at'], 0, -9),
                                        'deleted_at' => substr($transactionData['created_at'], 0, -9),
                                        'processed_at' => Carbon::parse($transactionData['processed_at']),
                                    ]);
                                    $transaction->save();
                                }

                                else{
                                    $transaction = new Transaction([
                                        'userno' => 1,
                                        'userid' => $transactionData['user']['id'],
                                        'tid' => $transactionData['id'],
                                        'type' => $transactionData['type'],
                                        'amount' => $transactionData['amount'],
                                        'before' => $transactionData['before'],
                                        'status' => $transactionData['status'],
                                        'gameid' => $transactionData['details']['game']['id'],
                                        'gametype' => 'n.a',
                                        'gameround' => $transactionData['details']['game']['round'],
                                        'gametitle' => 'n.a',
                                        'gamevendor' => 'n.a',
                                        'detail' => 0, 
                                        'detailUpdate' => 0,
                                        'created_at' => Carbon::parse($transactionData['created_at']),
                                        'updated_at' => substr($transactionData['created_at'], 0, -9),
                                        'deleted_at' => substr($transactionData['created_at'], 0, -9),
                                        'processed_at' => Carbon::parse($transactionData['processed_at']),
                                    ]);
                                    $transaction->save();
                                }
                            }
                            else{
                                if (isset($transactionData['external']) && isset($transactionData['external']['detail'])) {
                                    DB::update(
                                        'UPDATE transactions 
                                        SET detail = ?, detailUpdate = ? 
                                        WHERE tid = ? AND detailUpdate <> ?',
                                        [$transactionData['external']['detail'], 1, $transactionData['id'], 1]
                                    );
                                }
                            }  
                        }
                    }
                    echo ("Page ".$page." transaction Complete");
                    $page++;
                }
                sleep(40);
            } while ($page <= 5);

            return response()->json(['message' => 'Transactions stored successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    function get_user_transactions()
    {
        $username = Auth::user()->email;
        $userDetails = $this->get_user();
        $userId = json_decode($userDetails)->id;
        // echo $userId;
        $totalTransactions = Transaction::where('userid', $userId)->count();
        // echo $totalTransactions;
        $transactions = Transaction::where('userid', $userId)
            ->orderBy('no', 'desc')
            ->paginate(12);
        // echo $transactions;
        return view('bettinglist', compact('transactions', 'userDetails', 'totalTransactions'));
    }




}
