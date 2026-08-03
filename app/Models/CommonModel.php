<?php

namespace App\Models;

class CommonModel extends BaseModel
{
    protected $table = 'jadelyn_pharmacy_generic_name'; // Default table

    public function getDashboardData()
    {
        $totalInventory = $this->db->table('jadelyn_pharmacy_product_list')
            ->where('active', 1)
            ->countAllResults();

        $totalProducts = $this->db->table('jadelyn_pharmacy_product_price_list')
            ->where('active', 1)
            ->countAllResults();

        $lowStockItems = $this->db->table('jadelyn_pharmacy_product_list')
            ->where('active', 1)
            ->where('quantity <', 50)
            ->countAllResults();

        $totalBrands = $this->db->table('jadelyn_pharmacy_brand_name')
            ->where('active', 1)
            ->countAllResults();

        $totalGenericProducts = $this->db->table('jadelyn_pharmacy_generic_name')
            ->where('active', 1)
            ->countAllResults();

        return [
            'totalInventory'       => $totalInventory ?? 0,
            'totalProducts'        => $totalProducts ?? 0,
            'lowStockItems'        => $lowStockItems ?? 0,
            'totalBrands'          => $totalBrands ?? 0,
            'totalGenericProducts' => $totalGenericProducts ?? 0
        ];
    }
    
    public function getProductListItems()
    {
        return [
            'genericName' => $this->getActiveGenericNames(),
            'brandName' => $this->getActiveBrandNames(),
            'productType' => $this->getActiveProductTypes(),
            'productCombination' => $this->getProductCombinations(),
        ];
    }
    
    private function getActiveGenericNames()
    {
        return $this->db->table('jadelyn_pharmacy_generic_name')
            ->select('id, name')
            ->where('active', 1)
            ->get()
            ->getResult();
    }
    
    private function getActiveBrandNames()
    {
        return $this->db->table('jadelyn_pharmacy_brand_name')
            ->select('id, name')
            ->where('active', 1)
            ->get()
            ->getResult();
    }
    
    private function getActiveProductTypes()
    {
        return $this->db->table('jadelyn_pharmacy_product_types')
            ->select('id, name')
            ->where('active', 1)
            ->get()
            ->getResult();
    }
    
    private function getProductCombinations()
    {
        return $this->db->table('jadelyn_pharmacy_product_price_list a')
            ->select("CONCAT(b.name, ' - ', c.name, ' (', d.name, ')') AS product_name,
                     a.generic_name_id,
                     a.brand_id,
                     a.product_type_id")
            ->join('jadelyn_pharmacy_generic_name b', 'a.generic_name_id = b.id', 'inner')
            ->join('jadelyn_pharmacy_brand_name c', 'a.brand_id = c.id', 'inner')
            ->join('jadelyn_pharmacy_product_types d', 'a.product_type_id = d.id', 'inner')
            ->where([
                'a.active' => 1,
                'b.active' => 1,
                'c.active' => 1,
                'd.active' => 1
            ])
            ->orderBy('product_name', 'ASC')
            ->get()
            ->getResult();
    }

    public function getUserRoles()
    {
        $builder = $this->db->table('jadelyn_pharmacy_user_roles');
        $builder->select('id, role_name AS name');
        $builder->where('active', 1);
        $query = $builder->get();

        return $query->getResult();
    }
}