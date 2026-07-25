<?php

namespace App\Models\Products;

use App\Models\BaseModel;

use Hermawan\DataTables\DataTable;

class ProductsModel extends BaseModel
{
    public function getProductList($params)
    {
        $orderColumnName = $params['order'][0]['name'] ?? 'product_id';
        $orderDirection = $params['order'][0]['dir'] ?? 'asc';
        
        $builder = $this->builder('jadelyn_pharmacy_product_list a');

        $builder->select('a.id AS product_id,
                            d.name AS brand_name,
                            b.name AS product_type,
                            c.name AS generic_name,
                            a.lot_number,
                            a.expiry_date,
                            a.purchase_date,
                            a.quantity');
        $builder->join('jadelyn_pharmacy_product_types b', 'a.product_type_id = b.id', 'inner');
        $builder->join('jadelyn_pharmacy_generic_name c', 'a.generic_name_id = c.id', 'inner');
        $builder->join('jadelyn_pharmacy_brand_name d', 'a.brand_id = d.id', 'inner');
        $builder->join('jadelyn_pharmacy_product_price_list e', 'a.brand_id = e.brand_id AND a.generic_name_id = e.generic_name_id', 'inner');
        $builder->orderBy($orderColumnName, $orderDirection);

        if (!empty($params['searchType'])) {
            $searchType = $params['searchType'];
            $searchValue = $params['searchValue'] ?? '';

            switch ($searchType) {
                case \SearchTypes::BRAND_NAME:
                    $builder->like('a.name', $searchValue);
                    break;
                case \SearchTypes::PRODUCT_TYPE:
                    $builder->like('b.name', $searchValue);
                    break;
                case \SearchTypes::GENERIC_NAME:
                    $builder->like('c.name', $searchValue);
                    break;
                case \SearchTypes::LOT_NUMBER:
                    $builder->like('a.lot_number', $searchValue);
                    break;
                default:
                    // Handle invalid search type if necessary
                    break;
            }
        }
        
        $builder->where('a.active', 1); // Only show active products
        $builder->where('b.active', 1); 
        $builder->where('c.active', 1); 
        $builder->where('d.active', 1); 
        $builder->where('e.active', 1); 

        return DataTable::of($builder)->toJson(true);
    }

    public function deleteProduct($params)
    {
        $productId = $params['id'] ?? null;

        if (!$productId) {
            return [
                'success' => false,
                'message' => 'Product ID is required'
            ];
        }

        $data = [
            'active' => 0,
        ];

        $this->db->transStart();

        $builder = $this->builder('jadelyn_pharmacy_product_list');
        $builder->where('id', $productId);
        $builder->update($data);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Database error: Transaction failed.'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'Product deleted and logged successfully'
            ];
        }
    }

    public function addProduct($params) {
        $this->db->transStart();

        // Check if there is already a pricing on the product before entering to the product list.
        $checkBuilder = $this->builder('jadelyn_pharmacy_product_price_list');
        $checkBuilder->select('1');
        $checkBuilder->where('brand_id', $params['brand_id']);
        $checkBuilder->where('generic_name_id', $params['generic_name_id']);
        $checkBuilder->where('active', 1);
        
        $exists = $checkBuilder->get()->getRow();

        if (!$exists) {
            $this->db->transComplete();
            return [
                'success' => false,
                'message' => 'This product doesn\'t have a pricing yet. Please add a price in the price listing page first.'
            ];
        }

        $builder = $this->builder('jadelyn_pharmacy_product_list');

        $builder->insert([
            'brand_id'        => $params['brand_id'],
            'product_type_id' => $params['product_type_id'],
            'generic_name_id' => $params['generic_name_id'],
            'quantity'        => $params['quantity'],
            'lot_number'      => $params['lot_number'],
            'expiry_date'     => $params['expiry_date'],
            'purchase_date'   => $params['purchase_date'],
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Something happened while saving the product.'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'Product saved successfully'
            ];
        }
    }

    public function editProduct($params) 
    {
        $productId = $params['id'] ?? null;

        if (!$productId) {
            return [
                'success' => false, 
                'message' => 'Product ID is missing. Cannot update.'
            ];
        }

        $this->db->transStart();

        $builder = $this->builder('jadelyn_pharmacy_product_list');
        $builder->where('id', $productId);
        $builder->update([
            'brand_id'        => $params['brand_id'],
            'product_type_id' => $params['product_type_id'],
            'generic_name_id' => $params['generic_name_id'],
            'quantity'        => $params['quantity'],
            'lot_number'      => $params['lot_number'],
            'expiry_date'     => $params['expiry_date'],
            'purchase_date'   => $params['purchase_date'],
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Something happened while updating the product.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Product updated successfully!'
        ];
    }

    public function getProductDetails($params)
    {
        $productId = $params['id'] ?? null;

        if (!$productId) {
            return [
                'success' => false, 
                'message' => 'Product ID is required.'
            ];
        }

        $builder = $this->builder('jadelyn_pharmacy_product_list a');
        $builder->select('a.id AS product_id,
                            a.name AS product_name,
                            a.lot_number,
                            a.expiry_date,
                            a.purchase_date,
                            b.id AS product_type_id,
                            b.name AS product_type,
                            c.id AS generic_name_id,
                            c.name AS brand_name,
                            a.quantity,
                            a.brand_id');
        $builder->join('jadelyn_pharmacy_product_types b', 'a.product_type_id = b.id', 'inner');
        $builder->join('jadelyn_pharmacy_generic_name c', 'a.generic_name_id = c.id', 'inner');
        $builder->where('a.id', $productId);
        $builder->where('a.active', 1); // Ensure the product is active

        $query = $builder->get();
        $productDetails = $query->getRow();

        if (!$productDetails) {
            return [
                'success' => false, 
                'message' => 'Product not found or is inactive.'
            ];
        } else {
            return [
                'success' => true,
                'data' => $productDetails
            ];
        }
    }

    /* PRODUCT PRICE LIST */

    public function getProductPriceList($params)
    {
        $orderColumnName = $params['order'][0]['name'] ?? 'product_price_id';
        $orderDirection = $params['order'][0]['dir'] ?? 'asc';
        
        $builder = $this->builder('jadelyn_pharmacy_product_price_list a');

        $builder->select('a.id AS product_price_id,
                            b.name AS brand_name,
                            c.name AS generic_name,
                            a.unit_price,
                            a.selling_price');
        $builder->join('jadelyn_pharmacy_brand_name b', 'a.brand_id = b.id', 'inner');
        $builder->join('jadelyn_pharmacy_generic_name c', 'a.generic_name_id = c.id', 'inner');
        $builder->orderBy($orderColumnName, $orderDirection);

        if (!empty($params['searchType'])) {
            $searchType = $params['searchType'];
            $searchValue = $params['searchValue'] ?? '';

            switch ($searchType) {
                case \SearchTypes::BRAND_NAME:
                    $builder->like('b.name', $searchValue);
                    break;
                case \SearchTypes::PRODUCT_TYPE:
                    $builder->like('b.name', $searchValue);
                    break;
                case \SearchTypes::GENERIC_NAME:
                    $builder->like('c.name', $searchValue);
                    break;
                case \SearchTypes::LOT_NUMBER:
                    $builder->like('a.lot_number', $searchValue);
                    break;
                default:
                    // Handle invalid search type if necessary
                    break;
            }
        }

        // Add active conditions for all tables
        $builder->where('a.active', 1);
        $builder->where('b.active', 1);
        $builder->where('c.active', 1);

        return DataTable::of($builder)->toJson(true);
    }

    public function getProductPriceDetails($params)
    {
        $productPriceId = $params['id'] ?? null;

        if (!$productPriceId) {
            return [
                'success' => false, 
                'message' => 'Product Price ID is required.'
            ];
        }

        $builder = $this->builder('jadelyn_pharmacy_product_price_list a');

        $builder->select('a.id AS product_price_id,
                            b.name AS brand_name,
                            c.name AS generic_name,
                            a.brand_id,
                            a.generic_name_id,
                            a.unit_price,
                            a.selling_price');
        $builder->join('jadelyn_pharmacy_brand_name b', 'a.brand_id = b.id', 'inner');
        $builder->join('jadelyn_pharmacy_generic_name c', 'a.generic_name_id = c.id', 'inner');
        $builder->where('a.id', $productPriceId);
        $builder->where('a.active', 1);
        $builder->where('b.active', 1);
        $builder->where('c.active', 1);

        $query = $builder->get();
        $productPriceDetails = $query->getRow();

        if (!$productPriceDetails) {
            return [
                'success' => false, 
                'message' => 'Product price not found or is inactive.'
            ];
        } else {
            return [
                'success' => true,
                'data' => $productPriceDetails
            ];
        }
    }

    public function addProductPrice($params) {
        $this->db->transStart();

        // Check if the combination already exists
        $checkBuilder = $this->builder('jadelyn_pharmacy_product_price_list');
        $checkBuilder->select('unit_price');
        $checkBuilder->where('brand_id', $params['brand_id']);
        $checkBuilder->where('generic_name_id', $params['generic_name_id']);
        
        $exists = $checkBuilder->get()->getRow();

        if ($exists) {
            $this->db->transComplete();
            return [
                'success' => false,
                'message' => 'This product already has a price of ' . $exists->unit_price . ' pesos. Please use a different combination.'
            ];
        }

        $builder = $this->builder('jadelyn_pharmacy_product_price_list');

        $builder->insert([
            'brand_id'        => $params['brand_id'],
            'generic_name_id' => $params['generic_name_id'],
            'unit_price'      => $params['unit_price'],
            'selling_price'   => $params['selling_price']
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Something happened while saving the product.'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'Product saved successfully'
            ];
        }
    }

    public function editProductPrice($params) 
    {
        $productPriceId = $params['id'] ?? null;

        if (!$productPriceId) {
            return [
                'success' => false, 
                'message' => 'Product Price ID is missing. Cannot update.'
            ];
        }

        $this->db->transStart();

        $builder = $this->builder('jadelyn_pharmacy_product_price_list');
        $builder->where('id', $productPriceId);
        $builder->update([
            'brand_id'        => $params['brand_id'],
            'generic_name_id' => $params['generic_name_id'],
            'unit_price'      => $params['unit_price'],
            'selling_price'   => $params['selling_price']
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Something happened while updating the product price.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Product price updated successfully!'
        ];
    }

    public function deleteProductPrice($params)
    {
        $productPriceId = $params['id'] ?? null;

        if (!$productPriceId) {
            return [
                'success' => false,
                'message' => 'Product Price ID is required'
            ];
        }

        $data = [
            'active' => 0,
        ];

        $this->db->transStart();

        $builder = $this->builder('jadelyn_pharmacy_product_price_list');
        $builder->where('id', $productPriceId);
        $builder->update($data);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Database error: Transaction failed.'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'Product price deleted and logged successfully'
            ];
        }
    }
}