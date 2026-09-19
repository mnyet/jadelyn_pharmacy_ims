<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

use Hermawan\DataTables\DataTable;

class TransactionModel extends BaseModel
{
    public function getTransactionList($params) {
        $builder = $this->builder('jadelyn_pharmacy_transaction_list a');

        $builder->select("
            a.created_at AS raw_transaction_date,
            a.id AS transaction_id,
            a.transaction_code AS transaction_no,
            DATE_FORMAT(a.created_at, '%M %d, %Y %h:%i %p') AS transaction_date,
            a.total AS transaction_amount,
            COALESCE(b.username, 'System') AS transaction_employee
        ");

        $builder->join('jadelyn_pharmacy_users b', 'a.created_by = b.id', 'left');
        $builder->where('a.active', 1);
        
        if (!empty($params['searchValue'])) {
            $builder->like('a.transaction_code', $params['searchValue']);
        }

        return DataTable::of($builder)->toJson(true);
    }

    public function getTransactionDetails($params) {
        if (empty($params['transactionId'])) {
            return [
                'success' => false,
                'message' => 'Transaction ID is required.'
            ];
        }

        $transactionId = $params['transactionId'];

        $builder = $this->builder('jadelyn_pharmacy_transaction_list a');

        $builder->select("
            transaction_code AS transaction_no,
            DATE_FORMAT(a.created_at, '%M %d, %Y %h:%i %p') AS transaction_date,
            COALESCE(b.username, 'System') AS transaction_employee,
            a.item_count AS transaction_item_count,
            a.total AS transaction_total
        ");

        $builder->join('jadelyn_pharmacy_users b', 'a.created_by = b.id', 'left');
        $builder->where('a.id', $transactionId);
        $builder->where('a.active', 1);

        $query = $builder->get();
        $transactionDetails = $query->getRowArray();

        if (!$transactionDetails) {
            return [
                'success' => false, 
                'message' => 'Transaction cannot be found.'
            ];
        } else {
            return [
                'success' => true,
                'data' => $transactionDetails
            ];
        }
    }

    public function getTransactionItems($params) {
        $transactionId = $params['transactionId'] ?? null;

        if (!$transactionId) {
            return [
                'success' => false,
                'message' => 'Transaction ID is required.'
            ];
        }

        $builder = $this->builder('jadelyn_pharmacy_transaction_list_logs a');

        $builder->select("
            CONCAT(e.name, ' ', f.name, ' (', g.name, ')') AS td_product_name,
            CONCAT('₱', a.price)    AS td_product_price,
            a.quantity AS td_product_quantity,
            CONCAT('₱', (a.price * a.quantity)) AS td_product_subtotal,
            c.lot_number AS td_lot_number
        ");

        $builder->join('jadelyn_pharmacy_transaction_list b',       'a.transaction_list_id = b.id', 'inner');
        $builder->join('jadelyn_pharmacy_product_list c',           'a.product_list_id = c.id', 'inner');
        $builder->join('jadelyn_pharmacy_product_price_list d',     'c.product_price_id = d.id', 'inner');
        $builder->join('jadelyn_pharmacy_brand_name e',             'd.brand_id = e.id', 'inner');
        $builder->join('jadelyn_pharmacy_generic_name f',           'd.generic_name_id = f.id', 'inner');
        $builder->join('jadelyn_pharmacy_product_types g',          'd.product_type_id = g.id', 'inner');

        $builder->where('a.transaction_list_id', $transactionId);

        return DataTable::of($builder)->toJson(true);
    }
}