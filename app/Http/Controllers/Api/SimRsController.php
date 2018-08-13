<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
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
        $simRs = MsSimRs::find($id);

        return response()->json([
            'success' => true,
            'data' => ['detail' => $simRs],
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function store(Request $request){
        $this->validateProcess($request);

        Carbon::setLocale('Asia/Jakarta');

        $status = '';
        if($request->has('rsStatus')){
            $status = $request->rsStatus;
        }

        $simRs = new MsSimRs;
        $simRs->sd_id = $request->sdId;
        $simRs->rs_no = $request->rsNo;
        $simRs->rs_name = $request->rsName;
        $simRs->rs_desc = $request->rsDesc;
        $simRs->rs_sim_id = $request->rsSimId;
        $simRs->rs_area = $request->rsArea;
        $simRs->rs_warehouse_id = $request->rsWarehouseId;
        $simRs->rs_pin = $request->rsPin;
        $simRs->rs_company_id = $request->rsCompanyId;
        $simRs->created_user = $request->pic;
        $simRs->created_at = Carbon::now()->toDateTimeString();
        $simRs->updated_user = $request->pic;
        $simRs->updated_at = Carbon::now()->toDateTimeString();
        $simRs->rs_status = $status;
        $simRs->save();

        return response()->json([
            'success' => true,
            'data' => ['detail' => $simRs],
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    protected function validator(array $data)
    {
        $messages = [
            'required' => 'Field :attribute tidak boleh kosong',
        ];

        return Validator::make($data, [
            'sdId' => 'required',
            'rsNo' => 'required',
            'rsName' => 'required',
            'rsDesc' => 'required',
            'rsSimId' => 'required',
            'rsArea' => 'required',
            'rsWarehouseId' => 'required',
            'rsPin' => 'required',
            'rsCompanyId' => 'required',
            'pic' => 'required'
        ], $messages);
    }

    public function validateProcess($request){
        $validator = $this->validator($request->all());

        if ($validator->fails())
        {
            $this->resValidateError($validator);
        }
    }

    public function resValidateError($validator){
        foreach($validator->messages()->getmessages() as $message){
            $errorMessage[] = $message[0];
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'error' => ['code' => 422, 'message' => $errorMessage],
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function update(Request $request, $id){
        Carbon::setLocale('Asia/Jakarta');

        $simRs = MsSimRs::where('id', $id);

        if($request->has('sdId')){
            $simRs->sd_id = $request->sdId;
        }

        if($request->has('rsNo')){
            $simRs->rs_no = $request->rsNo;
        }

        if($request->has('rsName')){
            $simRs->rs_name = $request->rsName;
        }

        if($request->has('rsDesc')){
            $simRs->rs_desc = $request->rsDesc;
        }

        if($request->has('rsSimId')){
            $simRs->rs_sim_id = $request->rsSimId;
        }

        if($request->has('rsArea')){
            $simRs->rs_area = $request->rsArea;
        }

        if($request->has('rsWarehouseId')){
            $simRs->rs_warehouse_id = $request->rsWarehouseId;
        }

        if($request->has('rsPin')){
            $simRs->rs_pin = $request->rsPin;
        }

        if($request->has('rsCompanyId')){
            $simRs->rs_company_id = $request->rsCompanyId;
        }

        $simRs->updated_user = $request->pic;
        $simRs->updated_at = Carbon::now()->toDateTimeString();

        if($request->has('rsStatus')){
            $simRs->rs_status = $request->rsStatus;
        }

        $simRs->save();

        return response()->json([
            'success' => true,
            'data' => ['detail' => $simRs],
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function destroy($id){
        $simRs = MsSimRs::find($id);

        $simRs->delete();

        $res = [
            'detail' => null,
            'message' => 'success deleting data'
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }
}
