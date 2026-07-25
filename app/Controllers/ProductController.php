<?php

namespace App\Controllers;

use App\Models\Products\ProductsModel;
use App\Models\CommonModel;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->productsModel = new ProductsModel();
        $this->commonModel = new CommonModel();
    }

    public function index(): string
    {
        $response = $this->commonModel->getProductListItems();
        $data = [
            'genericNames' => $response['genericName'] ?? [],
            'productTypes' => $response['productType'] ?? [],
            'brandNames' => $response['brandName'] ?? [],
        ];

        return view('Product/ProductListView', $data);
    }

    public function getProductList()
    {
        $params = $this->request->getPost();

        return $this->productsModel->getProductList($params);
    }

    public function deleteProduct() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->deleteProduct($params));
    }

    public function addProduct() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->addProduct($params));
    }

    public function editProduct() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->editProduct($params));
    }
    
    public function getProductDetails()
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->getProductDetails($params));
    }

    /* PRODUCT PRICING */

    public function productPricing()
    {
        $response = $this->commonModel->getProductListItems();
        $data = [
            'genericNames' => $response['genericName'] ?? [],
            'productTypes' => $response['productType'] ?? [],
            'brandNames' => $response['brandName'] ?? [],
        ];

        return view('Product/ProductPricingView', $data);
    }

    public function getProductPriceList()
    {
        $params = $this->request->getPost();

        return $this->productsModel->getProductPriceList($params);
    }

    public function getProductPriceDetails()
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->getProductPriceDetails($params));
    }

    public function addProductPrice() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->addProductPrice($params));
    }

    public function editProductPrice() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->editProductPrice($params));
    }

    public function deleteProductPrice() 
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->productsModel->deleteProductPrice($params));
    }
}