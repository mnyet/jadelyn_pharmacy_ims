<?php

namespace App\Models\Sales;

use App\Models\BaseModel;

use Hermawan\DataTables\DataTable;

class SalesModel extends BaseModel
{
    public function processTransaction($params)
    {
        // Validation before processing.
        if ($params['cartData'] == null || empty($params['cartData'])) {
            return [
                'success' => false,
                'message' => 'Cart is empty. Please add items to the cart before processing the transaction.',
            ];
        }

        // Compute total & item count from cart first as initial validation.
        $total = 0;
        $itemCount = 0;
        foreach ($params['cartData'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $itemCount += $item['quantity'];
        }

        # Check if the total and item count match the expected values from the client.
        if (abs($params['totalPrice'] - $total) > 0.01 || $params['totalQuantity'] != $itemCount) {
            return [
                'success' => false,
                'message' => 'Cart data mismatch. Please refresh the page and try again.',
                'data' => [
                    'expectedTotal' => $total,
                    'expectedItemCount' => $itemCount,
                    'receivedTotal' => $params['totalPrice'],
                    'receivedItemCount' => $params['totalQuantity']
                ]
            ];
        }

        try {
            $this->db->transStart();

            $transaction_code = $this->generateTransactionCode();

            // Insert transaction row
            $this->db->table('jadelyn_pharmacy_transaction_list')->insert([
                'transaction_code' => $transaction_code,
                'total'            => $total,
                'item_count'       => $itemCount,
                'created_by'       => $params['userId'],
            ]);

            $transaction_id = $this->db->insertID();
            $insert_cart_data = [];

            // Obtain product IDs from the cart for validation for later processing.
            $productIds = array_column($params['cartData'], 'product_id');

            $products = $this->db->table('jadelyn_pharmacy_product_list')
                ->select('id, quantity')
                ->whereIn('id', $productIds)
                ->get()
                ->getResultArray();

            $productsById = [];
            foreach ($products as $p) {
                $productsById[$p['id']] = $p;
            }

            # Process each item in the cart
            foreach ($params['cartData'] as $item) {
                if (!isset($productsById[$item['product_id']])) {
                    throw new \Exception("Product not found (ID: {$item['product_id']}).");
                }

                $product = $productsById[$item['product_id']];

                if ($product['quantity'] < $item['quantity']) {
                    throw new \Exception("Insufficient stock for \"{$product['name']}\". Available: {$product['quantity']}, Requested: {$item['quantity']}.");
                }

                $insert_cart_data[] = [
                    'transaction_list_id' => $transaction_id,
                    'product_list_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            # Check if there are valid cart items to insert.
            if (empty($insert_cart_data)) {
                throw new \Exception('No valid cart items to insert.');
            }

            # Insert the cart items into the transaction logs.
            $this->db->table('jadelyn_pharmacy_transaction_list_logs')->insertBatch($insert_cart_data);

            # Deduct the purchased quantities from the product inventory.
            $cases = [];
            $ids = [];

            foreach($params['cartData'] as $item) {
                $id = (int)$item['product_id'];
                $quantity = (int)$item['quantity'];
                $cases[] = "WHEN {$id} THEN quantity - {$quantity}";
                $ids[] = $id;
            }

            $deductCase = 'CASE id ' . implode(' ', $cases) . ' END';
            $deductIdList = implode(',', $ids);

            $this->db->query("
                UPDATE jadelyn_pharmacy_product_list
                SET quantity = {$deductCase}
                WHERE id IN ({$deductIdList})
            ");
            # Deduct the purchased quantities from the product inventory.

            if ($this->db->transStatus() === false) {
                throw new \Exception('Database error: Transaction failed.');
            }
            
            $this->db->transComplete();
            
            return [
                'success' => true,
                'message' => 'Transaction processed successfully.'
            ];

        } catch (\Throwable $th) {
            $this->db->transRollback();
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }

    /**
     * Generate race-safe daily transaction code.
     * Uses atomic ON DUPLICATE KEY UPDATE + LAST_INSERT_ID trick.
     */
    private function generateTransactionCode()
    {
        $today = date('Y-m-d');

        // Atomic increment — no race condition
        $this->db->query("
            INSERT INTO jadelyn_pharmacy_transaction_counter (counter_date, last_number)
            VALUES (?, LAST_INSERT_ID(1))
            ON DUPLICATE KEY UPDATE last_number = LAST_INSERT_ID(last_number + 1)
        ", [$today]);

        // Grab the incremented value from the same connection
        $row = $this->db->query("SELECT LAST_INSERT_ID() AS number")->getRow();

        return 'TXN-' . date('Ymd') . '-' . str_pad($row->number, 4, '0', STR_PAD_LEFT);
    }

    public function getTransactionList($params)
    {
        $builder = $this->builder('jadelyn_pharmacy_transaction_list');

        $builder->select("
            id AS transaction_id,
            transaction_code,
            total AS transaction_total,
            item_count AS transaction_item_count,
            DATE_FORMAT(created_at, '%h:%i:%s %p') AS transaction_time
        ");
        $builder->where('active', 1);
        $builder->orderBy('created_at', 'DESC');
        $builder->limit(3);

        return DataTable::of($builder)->toJson(true);
    }
}