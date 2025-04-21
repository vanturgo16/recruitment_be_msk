<?php

namespace App\Http\Controllers;
// Traits
use App\Traits\ApiRegionalTrait;

class AjaxMappingRegional extends Controller
{
    use ApiRegionalTrait;

    public function selectCity($id)
    {
        return $this->getCity($id);
    }

    public function selectDistrict($id)
    {
        return $this->getDistrict($id);
    }

    public function selectSubDistrict($id)
    {
        return $this->getSubDistrict($id);
    }

}
