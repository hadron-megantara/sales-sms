<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MsCustomer;
use App\MsSimRs;

class SimRsController extends Controller
{
    public function index(Request $request){
        $limit = env('PAGINATION_LIMIT', 10);
        $limitStart = env('PAGINATION_START', 0);
        $orderBy = 0;

        if($request->has('limit')){
            $limit = $request->limit;
        }

        if($request->has('$limitStart')){
            $limitStart = $request->$limitStart;
        }

        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
        }

        $customerId = 0;
        if($request->has('customerId') && $request->customerId != ''){
            $customerId = $request->customerId;
        }

        $rsId = 0;
        if($request->has('rsId') && $request->rsId != ''){
            $rsId = $request->rsId;
        }

        $simRS = DB::select('call sp_sales_sim_rs_get(?, ?, ?, ?)',[$limit, $limitStart, $customerId, $rsId]);

        if($simRS){
            $res = [
                'detail' => $simRS,
                'limit' => $limit,
                'limitStart' => $limitStart,
            ];

            return response()->json([
                'success' => true,
                'data' => $res,
                'error' => null,
                'version' => env('API_VERSION', 'v1')
            ]);
        } else{
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => ['code' => 404, 'message' => 'Data tidak ditemukan. Silahkan coba kembali.'],
                'version' => env('API_VERSION', 'v1')
            ]);
        }

    }

    public function show(Request $request, $id){
        dd($id);
    }

    public function store(Request $request){
        $status = '';
        if($request->has('status')){
            $status = $request->status;
        }

        $outlet = new MsCustomer;
        $outlet->customer_name = $request->name;
        $outlet->customer_pic = $request->pic;
        $outlet->customer_addrase = $request->address;
        $outlet->customer_phone = $request->phone;
        $outlet->customer_fax = $request->fax;
        $outlet->customer_payment_term = $request->paymentTerm;
        $outlet->customer_ar_limit = $request->arLimit;
        $outlet->customer_price_profile_id = $request->priceProfile;
        $outlet->customer_kecamatan_id = $request->kecamatanId;
        $outlet->customer_company_id = $request->companyId;
        $outlet->customer_area_id = $request->areaId;
        $outlet->status = $status;
        $outlet->save();


    }
}
