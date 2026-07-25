<?php

namespace App\Controllers;

use App\Models\Products\ProductsModel;
use App\Models\CommonModel;

class TransactionController extends BaseController
{
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->commonModel = new CommonModel();
    }

    public function index(): string {

        return view('UnauthorizedView');
        // return view('Transaction/TransactionListView');
    }
}