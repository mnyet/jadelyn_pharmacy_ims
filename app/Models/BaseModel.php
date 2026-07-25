<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    // Any logic you put here will be available in ALL your models
    
    /**
     * Common method to get only "Active" records
     * Useful for your pharmacy to hide discontinued drugs
     */
    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Example: A helper to format currency for the UI
     */
    public function formatCurrency($amount)
    {
        return '₱' . number_format($amount, 2);
    }
}