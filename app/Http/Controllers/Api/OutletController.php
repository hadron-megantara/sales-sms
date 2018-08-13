<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Carbon\Carbon;
use App\MsCustomer;
use App\MsCustomerPhoto;

class OutletController extends Controller
{
    public function index(Request $request){
        $limit = env('PAGINATION_LIMIT', 10);
        $limitStart = env('PAGINATION_START', 0);
        $orderBy = 0;
        $lat = 0;
        $long = 0;
        $status = 1;

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

        if($request->has('status')){
            $status = $request->status;
        }

        $outlet = DB::select('call 	sp_sales_outlet_get(?, ?, ?)',[$limit, $limitStart, $status]);

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

    public function show(Request $request, $id){
        $outlet = DB::select('call 	sp_sales_outlet_detail_get(?)',[$id]);

        $ktp = array();
        $photo = array();

        if($outlet){
            foreach ($outlet as $key => $value) {
                if($value->photo_type == 1){
                    $ktp = ['id' => $value->photo_id, 'path' => $value->photo_path];
                } else if($value->photo_type == 2){
                    $photo[] = ['id' => $value->photo_id, 'path' => $value->photo_path];
                }

                if($key == 0){
                    $res = $value;
                    unset($res->photo_id);
                    unset($res->photo_path);
                    unset($res->photo_type);
                }
            }

            $res->ktp = $ktp;
            $res->photo = $photo;

            return response()->json([
                'success' => true,
                'data' => ['detail' => $res],
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
                'long' => 'required',
                'ktp' => 'required|file',
                'photo' => 'required|file'
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
            $outlet = $res['outlet'];
            $outlet->ktp = url('/').'/app/images/customer/'.$outlet->uuid.'/ktp/'.$res['outletKtp']->path;

            foreach($res['outletPhoto'] as $photo){
                $photoArray[] = url('/').'/app/images/customer/'.$outlet->uuid.'/outlet/'.$photo->path;
            }

            $outlet->photo = $photoArray;

            return response()->json([
                'success' => true,
                'data' => ['detail' => $outlet],
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
        Carbon::setLocale('Asia/Jakarta');

        $uuid = $this->attributes['uuid'] = Uuid::uuid4()->toString();

        $outlet = new MsCustomer;
        $outlet->customer_name = $request->name;
        $outlet->customer_payment_term = $request->paymentTerm;
        $outlet->customer_ar_limit = $request->arLimit;
        $outlet->customer_price_profile_id = $request->priceProfile;
        $outlet->customer_kecamatan_id = $request->districtId;
        $outlet->customer_company_id = $request->companyId;
        $outlet->customer_latitude = $request->lat;
        $outlet->customer_longitude = $request->long;
        $outlet->uuid = $uuid;
        $outlet->status = 1;

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

        if($request->has('digiposId')){
            $outlet->customer_id_digipos = $request->digiposId;
        }

        if($request->has('createdBy')){
            $outlet->created_user = $request->pic;
        } else{
            $outlet->created_user = 0;
        }

        if($request->has('updatedBy')){
            $outlet->updated_user = $request->pic;
        } else{
            $outlet->updated_user = 0;
        }

        $outlet->save();

        $currDate = Carbon::now()->toDateTimeString();
        $fileName = pathinfo($request->ktp->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->ktp->getClientOriginalExtension();
        $uploadedFile = $request->file('ktp');
        $uploadedFile = $uploadedFile->storeAs('images/customer/'.$uuid.'/ktp', $fileName);

        $outletPhoto = new MsCustomerPhoto;
        $outletPhoto->_ms_customers = $outlet->id;
        $outletPhoto->path = $fileName;
        $outletPhoto->type = 1;
        $outletPhoto->created_by = $request->pic;
        $outletPhoto->created_at = $currDate;
        $outletPhoto->updated_by = $request->pic;
        $outletPhoto->updated_at = $currDate;
        $outletPhoto->save();

        $outletKtp = $outletPhoto;

        for($i=1;$i<=count($request->outlet);$i++){
            $fileName = pathinfo($request->outlet[$i]->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->outlet[$i]->getClientOriginalExtension();
            $uploadedFile = $request->file('outlet');
            $uploadedFile = $uploadedFile[$i]->storeAs('images/customer/'.$uuid.'/outlet', $fileName);

            $outletPhoto = new MsCustomerPhoto;
            $outletPhoto->_ms_customers = $outlet->id;
            $outletPhoto->path = $fileName;
            $outletPhoto->type = 2;
            $outletPhoto->created_by = $request->pic;
            $outletPhoto->created_at = $currDate;
            $outletPhoto->updated_by = $request->pic;
            $outletPhoto->updated_at = $currDate;
            $outletPhoto->save();

            $outletPhotoArray[] = $outletPhoto;
        }

        $res = ['outlet' => $outlet, 'outletKtp' => $outletKtp, 'outletPhoto' => $outletPhotoArray];

        return $res;
    }

    public function update(Request $request, $id){
        $outlet = MsCustomer::where('id', $id)->first();

        if($outlet){
            if($this->updateToDB($request, $id)){
                return response()->json([
                    'success' => true,
                    'data' => ['message' => 'Berhasil Mengubah Data Outlet'],
                    'error' => null,
                    'version' => env('API_VERSION', 'v1')
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => ['code' => 404, 'message' => 'Data tidak ditemukan. Silahkan coba kembali.'],
                'version' => env('API_VERSION', 'v1')
            ]);
        }
    }

    public function updateToDB($request, $id){
        Carbon::setLocale('Asia/Jakarta');

        $uuid = $this->attributes['uuid'] = Uuid::uuid4()->toString();

        $outlet = MsCustomer::where('id', $id)->first();

        if($request->has('name')){
            $outlet->customer_name = $request->name;
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

        if($request->has('lat')){
            $outlet->customer_latitude = $request->lat;
        }

        if($request->has('long')){
            $outlet->customer_longitude = $request->long;
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
            $outlet->created_user = $request->pic;
        } else{
            $outlet->created_user = 0;
        }

        if($request->has('updatedBy')){
            $outlet->updated_user = $request->pic;
        } else{
            $outlet->updated_user = 0;
        }

        $outlet->save();

        return $outlet;
    }

    public function upload(Request $request, $id){
        if($request->has('outletIdDeleted')){
            $deletedId = explode(',', $request->outletIdDeleted);

            foreach($deletedId as $key => $value){
                $deletePhoto = MsCustomerPhoto::where('id', $value)->first();

                Storage::delete('images/customer/'.$outlet->uuid.'/outlet/'.$deletePhoto->path);

                $deletePhoto->delete();
            }
        }

        if($request->hasFile('ktp') || ($request->hasFile('outlet') && $request->has('outletId')) || $request->hasFile('outletNew')){
            $currDate = Carbon::now()->toDateTimeString();

            if($request->hasFile('ktp')){
                $fileName = pathinfo($request->ktp->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->ktp->getClientOriginalExtension();
                $uploadedFile = $request->file('ktp');
                $uploadedFile = $uploadedFile->storeAs('images/customer/'.$outlet->uuid.'/ktp', $fileName);

                $outletPhoto = MsCustomerPhoto::where('_ms_customers', $id)->where('type', 1)->first();

                Storage::delete('images/customer/'.$outlet->uuid.'/ktp/'.$outletPhoto->path);

                $outletPhoto->path = $fileName;
                $outletPhoto->updated_by = $request->pic;
                $outletPhoto->updated_at = $currDate;
                $outletPhoto->save();
            }

            if($request->hasFile('outlet') && $request->has('outletId')){
                $outletId = explode(',', $request->outletId);
                foreach($outletId as $key => $value){
                    $fileName = pathinfo($request->outlet[$key+1]->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->outlet[$key+1]->getClientOriginalExtension();
                    $uploadedFile = $request->file('outlet');
                    $uploadedFile = $uploadedFile[$key+1]->storeAs('images/customer/'.$outlet->uuid.'/outlet', $fileName);

                    $outletPhoto = MsCustomerPhoto::where('id', $value)->first();

                    Storage::delete('images/customer/'.$outlet->uuid.'/outlet/'.$outletPhoto->path);

                    $outletPhoto->path = $fileName;
                    $outletPhoto->updated_by = $request->pic;
                    $outletPhoto->updated_at = $currDate;
                    $outletPhoto->save();
                }
            }

            if($request->hasFile('outletNew')){
                for($i=1;$i<=count($request->outletNew);$i++){
                    $fileName = pathinfo($request->outletNew[$i]->getClientOriginalName(), PATHINFO_FILENAME).'-'.$currDate.'.'.$request->outletNew[$i]->getClientOriginalExtension();
                    $uploadedFile = $request->file('outletNew');
                    $uploadedFile = $uploadedFile[$i]->storeAs('images/customer/'.$outlet->uuid.'/outlet', $fileName);

                    $outletPhoto = new MsCustomerPhoto;
                    $outletPhoto->_ms_customers = $id;
                    $outletPhoto->path = $fileName;
                    $outletPhoto->type = 2;
                    $outletPhoto->created_by = $request->pic;
                    $outletPhoto->created_at = $currDate;
                    $outletPhoto->updated_by = $request->pic;
                    $outletPhoto->updated_at = $currDate;
                    $outletPhoto->save();
                }
            }
        }
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
