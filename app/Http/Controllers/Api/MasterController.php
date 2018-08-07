<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\District;
use App\MsArea;
use App\MsCompany;
use App\MsPriceProfile;
use App\MsWarehouse;

class MasterController extends Controller
{
    public function getDistrict(){
        $district = District::all();

        $res = [
            'detail' => $district,
            'total' => count($district)
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function getArea(){
        $area = MsArea::all();

        $res = [
            'detail' => $area,
            'total' => count($area)
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function getCompany(){
        $company = MsCompany::all();

        $res = [
            'detail' => $company,
            'total' => count($company)
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function getPriceProfile(){
        $priceProfile = MsPriceProfile::all();

        $res = [
            'detail' => $priceProfile,
            'total' => count($priceProfile)
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function getWarehouse(){
        $warehouse = MsWarehouse::all();

        $res = [
            'detail' => $warehouse,
            'total' => count($warehouse)
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }
}
