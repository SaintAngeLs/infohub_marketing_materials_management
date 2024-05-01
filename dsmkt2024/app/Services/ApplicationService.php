<?php

namespace App\Services;

use App\Contracts\IApplication;
use App\Models\AccessRequest; // Assuming AccessRequest is your model

class ApplicationService implements IApplication
{
    public function getAllApplications()
    {
        return AccessRequest::all();
    }

    public function getApplicationById($id)
    {
        return AccessRequest::find($id);
    }

    public function createApplication(array $data)
    {
        return AccessRequest::create($data);
    }

    public function updateApplication($id, array $data)
    {
        $application = AccessRequest::find($id);
        if ($application) {
            $application->update($data);
            return $application;
        }
        return null;
    }

    public function deleteApplication($id)
    {
        $application = AccessRequest::find($id);
        if ($application) {
            return $application->delete();
        }
        return false;
    }
}
