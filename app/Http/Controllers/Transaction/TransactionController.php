<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Validators\TransactionValidator;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionValidator $transactionValidator,
        private readonly TransactionService $transactionService
    ) {
    }

    public function create(Request $request)
    {
        try {
            $this->transactionValidator->create($request->all());

            $transaction = $this->transactionService->create($request->user, $request);

            return apiResponse("Ok.", 200, $transaction);
        } catch (\Exception $e) {
            throw ($e);
        }
    }
}
