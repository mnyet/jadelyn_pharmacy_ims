<?php

namespace App\Models\Auth;

use App\Models\BaseModel;


class AuthModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function verifyLogin($args)
    {
        $builder = $this->db->table('jadelyn_pharmacy_users');
        
        $builder->where('username', $args['username']);
        $builder->where('active', 1);
        $query = $builder->get();

        if ($query->getNumRows() == 1) {
            $user = $query->getRow();

            if (password_verify($args['password'], $user->password)) {
                return $user;
            }
        }

        return false;
    }
}