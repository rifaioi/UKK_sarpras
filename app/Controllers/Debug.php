<?php

namespace App\Controllers;

use App\Models\InspectionChecklistItemModel;

class Debug extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('inspection_checklist_items');
        echo "<h3>Table Columns:</h3><pre>";
        print_r($fields);
        echo "</pre>";

        $model = new InspectionChecklistItemModel();
        $items = $model->findAll();
        echo "<h3>Model Results:</h3><pre>";
        print_r($items);
        echo "</pre>";
    }
}
