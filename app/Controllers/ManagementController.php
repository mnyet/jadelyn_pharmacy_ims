<?php

namespace App\Controllers;

use App\Models\Management\ManagementModel;
use App\Models\CommonModel;

class ManagementController extends BaseController
{
    public function __construct()
    {
        $this->commonModel = new CommonModel();
        $this->managementModel = new ManagementModel();
    }

    public function managementList($managementType) {

        $data = [
            'managementType' => $managementType,
            'userRoles' => $this->commonModel->getUserRoles(),
        ];

        // Users and Roles management should only be accessible by Admins.
        if (in_array($managementType, [\ManagementTypes::USERS, \ManagementTypes::ROLES]) && session()->get('userRoleId') != \UserRoles::ADMIN) {
            return $this->redirectUnauthorized();
        }

        return view('Management/ManagementView', $data);
    }

    public function getManagementList()
    {
        $params = $this->request->getPost();

        return $this->managementModel->getManagementList($params);
    }

    public function deleteEntry()
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->managementModel->deleteEntry($params));
    }

    public function addEntry() 
    {
        $params = $this->request->getPost();
        $params['userId'] = $this->getCurrentUserId();

        return $this->response->setJSON($this->managementModel->addEntry($params));
    }

    public function editEntry()
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->managementModel->editEntry($params));
    }

    public function getManagementDetails()
    {
        $params = $this->request->getPost();

        return $this->response->setJSON($this->managementModel->getManagementDetails($params));
    }
}