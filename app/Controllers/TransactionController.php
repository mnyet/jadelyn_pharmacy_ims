<?php

namespace App\Controllers;

use App\Models\Transaction\TransactionModel;
use App\Models\CommonModel;

class TransactionController extends BaseController
{
    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->commonModel = new CommonModel();
    }

    public function index(): string {

        return view('Transaction/TransactionListView');
    }

    public function getTransactionList() {
        $params = $this->request->getPost();

        return $this->transactionModel->getTransactionList($params);
    }

    public function getTransactionDetails() {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->transactionModel->getTransactionDetails($params));
    }

    public function getTransactionItems() {
        $params = $this->request->getPost();

        return $this->transactionModel->getTransactionItems($params);
    }
}