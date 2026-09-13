<?php

namespace App\Controllers;

use App\Models\Sales\SalesModel;
use App\Models\Products\ProductsModel;
use App\Models\CommonModel;

class SalesController extends BaseController
{
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->commonModel = new CommonModel();
        $this->salesModel = new SalesModel();
    }

    public function index()
    {
        return view('Sales/SalesView');
    }

    public function getProductList()
    {
        $params = $this->request->getPost();

        return $this->productsModel->getProductList($params);
    }

    public function processTransaction()
    {
        $params = $this->request->getPost();
        $params['userId'] = session()->get('userId');

        return $this->response->setJSON($this->salesModel->processTransaction($params));
    }

    public function getTransactionList()
    {
        $params = $this->request->getPost();

        return $this->salesModel->getTransactionList($params);
    }
}