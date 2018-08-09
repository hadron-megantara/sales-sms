<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\MsCustomer;

class OutletController extends Controller
{
    public function index(Request $request){
        $limit = env('PAGINATION_LIMIT', 10);
        $limitStart = env('PAGINATION_START', 0);
        $orderBy = 0;
        $lat = 0;
        $long = 0;

        if($request->has('limit')){
            $limit = $request->limit;
        }

        if($request->has('$limitStart')){
            $limitStart = $request->$limitStart;
        }

        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
        }

        if($request->has('lat')){
            $lat = $request->lat;
        }

        if($request->has('long')){
            $long = $request->long;
        }

        $outlet = DB::select('call 	sp_sales_outlet_get(?, ?)',[$limit, $limitStart]);

        $res = [
            'detail' => $outlet,
            'limit' => $limit,
            'limitStart' => $limitStart,
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    protected function validator(array $data, $type = 0)
    {
        $messages = [
            'required' => 'Field :attribute tidak boleh kosong',
        ];

        if($type == 0){
            return Validator::make($data, [
                'name' => 'required|max:191',
                'paymentTerm' => 'required',
                'arLimit' => 'required',
                'priceProfile' => 'required',
                'districtId' => 'required',
                'companyId' => 'required',
                'lat' => 'required',
                'long' => 'required'
            ], $messages);
        } else{
            return Validator::make($data, [
                'name' => 'required|max:191',
                'paymentTerm' => 'required',
                'arLimit' => 'required',
                'priceProfile' => 'required',
                'districtId' => 'required',
                'companyId' => 'required',
            ], $messages);
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

    public function validateProcess($request, $type = 0){
        $validator = $this->validator($request->all(), $type);

        if ($validator->fails())
        {
            $this->resValidateError($validator);
        }
    }

    public function store(Request $request){
        $this->validateProcess($request);

        $res = $this->storeToDB($request);

        if($res){
            return response()->json([
                'success' => true,
                'data' => ['detail' => $res],
                'error' => null,
                'version' => env('API_VERSION', 'v1')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => ['code' => 500, 'message' => 'Terjadi kesalahan sistem'],
                'version' => env('API_VERSION', 'v1')
            ]);
        }
    }

    public function storeToDB($request){
        $outlet = new MsCustomer;
        $outlet->customer_name = $request->name;
        $outlet->customer_payment_term = $request->paymentTerm;
        $outlet->customer_ar_limit = $request->arLimit;
        $outlet->customer_price_profile_id = $request->priceProfile;
        $outlet->customer_kecamatan_id = $request->districtId;
        $outlet->customer_company_id = $request->companyId;
        $outlet->customer_latitude = $request->lat;
        $outlet->customer_longitude = $request->long;

        if($request->has('pic')){
            $outlet->customer_pic = $request->pic;
        }

        if($request->has('address')){
            $outlet->customer_addrase = $request->address;
        }

        if($request->has('phone')){
            $outlet->customer_phone = $request->phone;
        }

        if($request->has('fax')){
            $outlet->customer_fax = $request->fax;
        }

        if($request->has('freq')){
            $outlet->customer_frequensi_kunjungan = $request->freq;
        }

        if($request->has('areaId')){
            $outlet->customer_area_id = $request->areaId;
        }

        if($request->has('status')){
            $outlet->status = $request->status;
        }

        if($request->has('digiposId')){
            $outlet->customer_id_digipos = $request->digiposId;
        }

        if($request->has('createdBy')){
            $outlet->created_user = $request->createdBy;
        } else{
            $outlet->created_user = 0;
        }

        if($request->has('updatedBy')){
            $outlet->updated_user = $request->updatedBy;
        } else{
            $outlet->updated_user = 0;
        }

        $outlet->save();

        return $outlet;
    }

    public function update(Request $request, $id){
        $outlet = MsCustomer::where('id', $id)->first();

        if(!$request->has('isClosed') || ($request->has('isClosed') && $request->isClosed == 0)){
            if($request->has('name')){
                $outlet->customer_name = $request->name;
            }

            if($request->has('pic')){
                $outlet->customer_pic = $request->pic;
            }

            if($request->has('address')){
                $outlet->customer_addrase = $request->address;
            }

            if($request->has('phone')){
                $outlet->customer_phone = $request->phone;
            }

            if($request->has('fax')){
                $outlet->customer_fax = $request->fax;
            }

            if($request->has('paymentTerm')){
                $outlet->customer_payment_term = $request->paymentTerm;
            }

            if($request->has('arLimit')){
                $outlet->customer_ar_limit = $request->arLimit;
            }

            if($request->has('priceProfile')){
                $outlet->customer_price_profile_id = $request->priceProfile;
            }

            if($request->has('districtId')){
                $outlet->customer_kecamatan_id = $request->districtId;
            }

            if($request->has('companyId')){
                $outlet->customer_company_id = $request->companyId;
            }

            if($request->has('areaId')){
                $outlet->customer_area_id = $request->areaId;
            }

            if($request->has('status')){
                $outlet->status = $request->status;
            }

            if($request->has('createdBy')){
                $outlet->created_user = $request->createdBy;
            } else{
                $outlet->created_user = 0;
            }

            if($request->has('updatedBy')){
                $outlet->updated_user = $request->updatedBy;
            } else{
                $outlet->updated_user = 0;
            }
        } else if($request->has('isClosed') && $request->isClosed == 1){
            $outlet->isClosed = 1;
        }

        $outlet->save();

        $res = [
            'detail' => $outlet
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);
    }

    public function destroy($id){
        $outlet = MsCustomer::find($id);

        $outlet->delete();

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
