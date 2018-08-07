<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Menu;
use App\Permission;
use App\Role;
use App\UserHasRole;

class ConfigController extends Controller
{

    public function getMenu(Request $request){
        $user = auth('api')->user();

        $menuData = Menu::select('sales_menu.*')->join('sales_permissions', 'sales_menu.id', '=', 'sales_permissions._sales_menu')
                    ->join('sales_roles', 'sales_permissions._sales_roles', '=', 'sales_roles.id')
                    ->join('sales_user_has_roles', 'sales_roles.id', '=', 'sales_user_has_roles._sales_roles')
                    ->where('sales_user_has_roles._users', '=', $user->id)->get();

        $menuData = $menuData->toArray();

        foreach($menuData as $menuDataTemp){
            if($menuDataTemp['level'] == 0){
                $subMenu = $this->buildMenu($menuData, $menuDataTemp['id']);

                $menu[] = [
                    'name' => $menuDataTemp['name'],
                    'link' => $menuDataTemp['link'],
                    'icon' => $menuDataTemp['icon'],
                    'parent' => $menuDataTemp['parent'],
                    'sub' => $subMenu,
                ];
            }
        }

        $res = [
            'detail' => $menu
        ];

        return response()->json([
            'success' => true,
            'data' => $res,
            'error' => null,
            'version' => env('API_VERSION', 'v1')
        ]);

    }

    public function buildMenu($array, $parentId = 0){
        $tempArray = array();
        foreach($array as $element)
        {
            if($element['parent']==$parentId)
            {
                $sub = $this->buildMenu($array,$element['id']);
                $subDetail = [
                    'name' => $element['name'],
                    'link' => $element['link'],
                    'icon' => $element['icon'],
                    'parent' => $element['parent'],
                    'sub' => $sub,
                ];
                $tempArray[] = $subDetail;
            }
        }
        return $tempArray;
    }

}
