<?php

namespace App\Models\Management;

use App\Models\BaseModel;

use Hermawan\DataTables\DataTable;

/*
    This model will be generic for some dropdown menus such as generic names and product types.
    It will also handle the management of those dropdown items, such as adding, editing, and deleting generic names and product types.
*/
class ManagementModel extends BaseModel
{
    protected $tables = [];
    protected $tableName = '';

    public function __construct()
    {
        parent::__construct(); // Always call the parent constructor
        
        $this->tables = [
            \ManagementTypes::GENERIC_NAME => 'jadelyn_pharmacy_generic_name',
            \ManagementTypes::PRODUCT_TYPE => 'jadelyn_pharmacy_product_types',
            \ManagementTypes::USERS         => 'jadelyn_pharmacy_users',
            \ManagementTypes::ROLES    => 'jadelyn_pharmacy_user_roles',
            \ManagementTypes::BRANDS    => 'jadelyn_pharmacy_brand_name',
        ];
    }

    public function getManagementList($params)
    {
        $params['tableName'] = $this->tables[$params['managementType']] ?? null;

        if (!$params['tableName']) {
            return json_encode([
                'success' => false,
                'message' => 'Invalid management type.'
            ]);
        }

        if (in_array($params['managementType'], [\ManagementTypes::GENERIC_NAME, \ManagementTypes::PRODUCT_TYPE, \ManagementTypes::BRANDS]))
        {
            $response = $this->_getManagementListGeneric($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::USERS])) 
        {
            $response = $this->_getManagementListUsers($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::ROLES])) 
        {
            $response = $this->_getManagementListUserRoles($params);
        } else {
            return json_encode([
                'success' => false,
                'message' => 'Management type not supported for listing.'
            ]);
        }
    
        return DataTable::of($response)->toJson();
    }

    private function _getManagementListGeneric($params) {
        $builder = $this->db->table($params['tableName']);
        $builder->select('id, name');
        $builder->where('active', 1);

        $orderColumnIndex = $params['order'][0]['name'] ?? 'id';
        $orderDirection = $params['order'][0]['dir'] ?? 'asc';

        $builder->orderBy($orderColumnIndex, $orderDirection);

        if (!empty($params['searchValue'])) {
            $builder->like('name', $params['searchValue']);
        }

        return $builder;
    }

    private function _getManagementListUsers($params) {
        $builder = $this->db->table($params['tableName'] . ' a');
        $builder->select('a.id, a.username AS name, a.role_id, b.role_name');
        $builder->join('jadelyn_pharmacy_user_roles b', 'a.role_id = b.id', 'inner');
        $builder->where('a.active', 1);

        $orderColumnIndex = $params['order'][0]['name'] ?? 'a.id';
        $orderDirection = $params['order'][0]['dir'] ?? 'asc';
        $builder->orderBy($orderColumnIndex, $orderDirection);

        if (!empty($params['searchValue'])) {
            $builder->like('a.username', $params['searchValue']);
        }

        return $builder;
    }

    private function _getManagementListUserRoles($params) {
        $builder = $this->db->table($params['tableName']);
        $builder->select('id, role_name AS name');
        $builder->where('active', 1);

        $orderColumnIndex = $params['order'][0]['name'] ?? 'a.id';
        $orderDirection = $params['order'][0]['dir'] ?? 'asc';
        $builder->orderBy($orderColumnIndex, $orderDirection);

        if (!empty($params['searchValue'])) {
            $builder->like('role_name', $params['searchValue']);
        }

        return $builder;
    }

    /* END OF DATATABLE FUNCTIONS */

    public function deleteEntry($params)
    {
        $entryId = $params['id'] ?? null;
        $managementType = $params['managementType'] ?? null;

        if (!$entryId || !$managementType) {
            return [
                'success' => false,
                'message' => 'Entry ID and management type are required'
            ];
        }

        $data = [
            'active' => 0,
        ];

        $tableName = $this->tables[$managementType] ?? null;

        if (!$tableName) {
            return [
                'success' => false,
                'message' => 'Invalid management type.'
            ];
        }

        $this->db->transStart();

        $builder = $this->builder($tableName);
        $builder->where('id', $entryId);
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
                'message' => 'Entry deleted and logged successfully'
            ];
        }
    }

    /* END OF DELETE ENTRY */

    public function addEntry($params) {
        if (empty($params['managementType'])) {
            return [
                'success' => false,
                'message' => 'Management type are required.'
            ];
        }

        $params['tableName'] = $this->tables[$params['managementType']] ?? null;

        if (!$params['tableName']) {
            return [
                'success' => false,
                'message' => 'Invalid management type.'
            ];
        }

        $this->db->transStart();
        
        if (in_array($params['managementType'], [\ManagementTypes::GENERIC_NAME, \ManagementTypes::PRODUCT_TYPE, \ManagementTypes::BRANDS]))
        { // Insertion for generic management types (Usually has name in their field.)
            $this->_addEntryGeneric($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::USERS])) 
        { // Insertion for Users
            $userCheck = $this->db->table($params['tableName'])
                ->groupStart()
                    ->where('username', $params['username'])
                    ->orWhere('email', $params['email'])
                ->groupEnd()
                ->get();

            if ($userCheck->getNumRows() > 0) {
                $existingUser = $userCheck->getRow();
                $conflict = ($existingUser->username === $params['username']) ? 'Username' : 'Email';

                $this->db->transRollback(); 
                return [
                    'success' => false,
                    'message' => "The $conflict is already taken. Please use a different one."
                ];
            }
            $this->_addEntryUsers($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::ROLES])) 
        { // Insertion for User Roles
            $roleCheck = $this->db->table($params['tableName'])
                ->where('role_code', $params['role_code'])
                ->get();

            if ($roleCheck->getNumRows() > 0) {
                $this->db->transRollback(); 
                return [
                    'success' => false,
                    'message' => "The role code '" . $params['role_code'] . "' already exists."
                ];
            }
            $this->_addEntryUserRoles($params);
        } else {
            return [
                'success' => false,
                'message' => 'Management type not supported for listing.'
            ];
        }

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

    private function _addEntryGeneric($params) {
        $builder = $this->builder($params['tableName']);

        $builder->set('name', $params['name']);
        $builder->insert();
    }

    private function _addEntryUsers($params) {
        $builder = $this->db->table($params['tableName']);
        $data = [
            'username'           => $params['username'],
            'email'              => $params['email'],
            'password'           => password_hash($params['password'], PASSWORD_DEFAULT),
            'role_id'            => $params['roleId'],
            'created_by_user_id' => $params['userId'],
        ];
        $builder->insert($data);
    }

    private function _addEntryUserRoles($params) {
        $builder = $this->db->table($params['tableName']);
        $data = [
            'role_name'     => $params['name'],
            'role_code'     => $params['role_code'],
            'description'   => $params['description'] ?? ''
        ];
        $builder->insert($data);
    }

    /* END OF ADD ENTRY */

    public function editEntry($params) {
        if (empty($params['managementType'])) {
            return [
                'success' => false,
                'message' => 'Management type is required.'
            ];
        }

        $params['tableName'] = $this->tables[$params['managementType']] ?? null;
        $params['entryId'] = $params['id'] ?? null;

        if (!$params['tableName'] || !$params['entryId']) {
            return [
                'success' => false, 
                'message' => 'Missing table or entry ID. Cannot update.'
            ];
        }
        
        $this->db->transStart();

        if (in_array($params['managementType'], [\ManagementTypes::GENERIC_NAME, \ManagementTypes::PRODUCT_TYPE, \ManagementTypes::BRANDS]))
        {
            $this->_editEntryGeneric($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::USERS])) 
        {
            $userCheck = $this->db->table($params['tableName'])
                ->where('id !=', $params['id'])
                ->groupStart()
                    ->where('username', $params['username'])
                    ->orWhere('email', $params['email'])
                ->groupEnd()
                ->get();

            if ($userCheck->getNumRows() > 0) {
                $existingUser = $userCheck->getRow();
                $conflict = ($existingUser->username === $params['username']) ? 'Username' : 'Email';

                $this->db->transRollback(); 
                return [
                    'success' => false,
                    'message' => "The $conflict is already taken. Please use a different one."
                ];
            }
            $this->_editEntryUsers($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::ROLES])) 
        {
            $roleCheck = $this->db->table($params['tableName'])
                ->where('id !=', $params['id'])
                ->where('role_code', $params['role_code'])
                ->get();

            if ($roleCheck->getNumRows() > 0) {
                $this->db->transRollback(); 
                return [
                    'success' => false,
                    'message' => "The role code '" . $params['role_code'] . "' already exists."
                ];
            }
            $this->_editEntryUserRoles($params);
        } else {
            return json_encode([
                'success' => false,
                'message' => 'Management type not supported for listing.'
            ]);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'success' => false, 
                'message' => 'Something happened while updating the record.'
            ];
        } else {
            return [
                'success' => true,
                'message' => 'Record updated successfully!'
            ];
        }
    }

    private function _editEntryGeneric($params) {
        $builder = $this->db->table($params['tableName']);

        $builder->set('name', $params['name']);
        $builder->where('id', $params['entryId']); 
        $builder->update();
    }

    private function _editEntryUsers($params) {
        $builder = $this->db->table($params['tableName']);

        $data = [
            'username' => $params['username'],
            'email'    => $params['email'],
            'role_id'  => $params['roleId'],
        ];

        if (isset($params['changePasswordFlag']) && $params['changePasswordFlag'] == true) {
            if (!empty($params['password'])) {
                $data['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
            }
        }

        $builder->where('id', $params['id']);
        $builder->update($data);
    }

    private function _editEntryUserRoles($params) {
        $builder = $this->db->table($params['tableName']);

        $data = [
            'role_name'   => $params['name'],
            'role_code'   => $params['role_code'],
            'description' => $params['description'] ?? ''
        ];

        $builder->where('id', $params['id']);
        return $builder->update($data);
    }


    /* END OF EDIT ENTRY */

    public function getManagementDetails($params) {
        if (!$params['id']) {
            return [
                'success' => false, 
                'message' => 'Entry ID is required.'
            ];
        }

        $params['tableName'] = $this->tables[$params['managementType']] ?? null;

        if (!$params['tableName']) {
            return [
                'success' => false,
                'message' => 'Invalid management type.'
            ];
        }

        if (in_array($params['managementType'], [\ManagementTypes::GENERIC_NAME, \ManagementTypes::PRODUCT_TYPE, \ManagementTypes::BRANDS]))
        {
            $response = $this->_getManagementDetailsGeneric($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::USERS])) 
        {
            $response = $this->_getManagementDetailsUsers($params);
        } else if (in_array($params['managementType'], [\ManagementTypes::ROLES])) 
        {
            $response = $this->_getManagementDetailsUserRoles($params);
        } else {
            return json_encode([
                'success' => false,
                'message' => 'Management type not supported for listing.'
            ]);
        }

        $result = $response->getRow();

        if (!$result) {
            return [
                'success' => false, 
                'message' => 'Entry not found or is inactive.'
            ];
        } else {
            return [
                'success' => true,
                'data' => $result
            ];
        }
    }

    private function _getManagementDetailsGeneric($params) {
        $builder = $this->db->table($params['tableName']);
        $builder->select('id, name');
        $builder->where('id', $params['id']);
        $builder->where('active', 1);
        return $builder->get();
    }

    private function _getManagementDetailsUsers($params) {
        $builder = $this->db->table($params['tableName']);
        $builder->select('id, username, email, role_id');
        $builder->where('id', $params['id']);
        $builder->where('active', 1);
        return $builder->get();
    }

    private function _getManagementDetailsUserRoles($params) {
        $builder = $this->db->table($params['tableName']);
        $builder->select('id, role_name AS name, role_code, description AS role_description');
        $builder->where('id', $params['id']);
        $builder->where('active', 1);
        return $builder->get();
    }

    /* END OF GET MANAGEMENT DETAILS */
}