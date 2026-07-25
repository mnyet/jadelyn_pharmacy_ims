<?php

namespace App\Models;

class CommonModel extends BaseModel
{
    public function getProductListItems()
    {
        $builder = $this->db->table('jadelyn_pharmacy_generic_name');
        $builder->select('id, name');
        $builder->where('active', 1);
        $query = $builder->get();

        $genericName =  $query->getResult();
        
        $builder = $this->db->table('jadelyn_pharmacy_brand_name');
        $builder->select('id, name');
        $builder->where('active', 1);
        $query = $builder->get();

        $brandName =  $query->getResult();

        $builder = $this->db->table('jadelyn_pharmacy_product_types');
        $builder->select('id, name');
        $builder->where('active', 1);
        $query = $builder->get();

        $productType =  $query->getResult();

        return [
            'genericName' => $genericName,
            'brandName' => $brandName,
            'productType' => $productType,
        ];
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