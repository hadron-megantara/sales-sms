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

        if($request->has('limit')){
            $limit = $request->limit;
        }

        if($request->has('$limitStart')){
            $limitStart = $request->$limitStart;
        }

        if($request->has('orderBy')){
            $orderBy = $request->orderBy;
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

    protected function validatorStore(array $data)
    {
        $messages = [
            'required' => 'Field :attribute tidak boleh kosong',
        ];

        return Validator::make($data, [
            'name' => 'required|max:191',
            'paymentTerm' => 'required',
            'arLimit' => 'required',
            'priceProfile' => 'required',
            'districtId' => 'required',
            'companyId' => 'required',
        ], $messages);
    }

    public function store(Request $request){
        $validator = $this->validatorStore($request->all());

        if ($validator->fails())
        {
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

        $outlet = new MsCustomer;
        $outlet->customer_name = $request->name;

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

        $outlet->customer_payment_term = $request->paymentTerm;
        $outlet->customer_ar_limit = $request->arLimit;
        $outlet->customer_price_profile_id = $request->priceProfile;
        $outlet->customer_kecamatan_id = $request->districtId;
        $outlet->customer_company_id = $request->companyId;

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

    protected function validatorUpdate(array $data)
    {
        $messages = [
            'required' => 'Field :attribute tidak boleh kosong',
        ];

        return Validator::make($data, [
            'name' => 'required|max:191',
            'paymentTerm' => 'required',
            'arLimit' => 'required',
            'priceProfile' => 'required',
            'districtId' => 'required',
            'companyId' => 'required',
        ], $messages);
    }

    public function update(Request $request, $id){
        $outlet = MsCustomer::where('id', $id)->first();

        if(!$request->has('isClosed') || ($request->has('isClosed') && $request->isClosed == 0)){
            $validator = $this->validatorUpdate($request->all());

            if ($validator->fails())
            {
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
        } else{
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
