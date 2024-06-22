<?php

namespace App\Contracts;

interface IApplication
{
    public function getAllApplications();
    public function getApplicationById($id);
    public function createApplication(array $data);
    public function updateApplication($id, array $data);
    public function deleteApplication($id);
}
