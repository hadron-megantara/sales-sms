<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Session;
use App\Menu;
use App\Permission;
use App\Role;
use App\UserHasRole;

use Closure;

class Auth2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if(!Session::has('user') || session('user') == null){
            View::share('user', null);

            return redirect('login');
        } else{
            View::share('user', session('user'));

            if(isset($_COOKIE[env('TOKEN_COOKIE', 'phpsess')])){
                View::share('menu', $this->generateMenu($request->segment(1), $request->path()));
            }
        }

        return $next($request);
    }

    public function generateMenu($segment, $path){
        $menuList = $this->loadMenuData($_COOKIE[env('TOKEN_COOKIE', 'phpsess')]);

        $menu = '';
        foreach($menuList as $menuList){
            $menu .= $this->buildMenu($menuList, $segment, $path, true);
        }

        return $menu;
    }

    public function buildMenu($menu, $segment, $path, $isParent = false){
        $menuHtml = '';

        if($isParent){
            if($menu->link == '/'.$segment || ($menu->link == '/') && $segment == null){
                $active = 'active';
                $isOpen = 'open';
            } else{
                $active = '';
                $isOpen = '';
            }
            $menuHtml = '<li class="'.$active.'">';

            if(count($menu->sub) > 0){
                $menuHtml .= '<a href="javascript:;">';
            } else{
                $menuHtml .= '<a href="'.$menu->link.'"';
            }

            $menuHtml .= '<i class="fa fa-'.$menu->icon.'"></i>';
            $menuHtml .= '<span class="title"> '.$menu->name.'</span>';

            if(count($menu->sub) > 0){
                $menuHtml .= '<span class="arrow '.$isOpen.'"></span>';
            }

            $menuHtml .= '</a>';
        }

        if(count($menu->sub) > 0){
            ($menu->link == '/'.$segment) ? $style = "display:block" : $style = "";
            $menuHtml .= '<ul class="sub-menu" style="'.$style.'">';
            foreach($menu->sub as $sub){
                if(count($sub->sub) > 0){
                    $menuHtml .= $this->buildMenu($menu, $segment, $segment);
                }

                ($menu->link.$sub->link == '/'.$path) ? $isActive = "active" : $isActive = "";
                $menuHtml .= '<li class="'.$isActive.'"><a href="'.$menu->link.$sub->link.'">'.$sub->name.'</a></li>';
            }
            $menuHtml .= '</ul>';
        }

        if($isParent){
            $menuHtml .= '</li>';
        }

        return $menuHtml;
    }

    public function loadMenuData($cookie){
        $menu = Cache::remember('menu', 60, function () {
            $token = $_COOKIE[env('TOKEN_COOKIE', 'phpsess')];

            $client = new Client;
            $response = $client->request('GET', env('API_URL', 'http://sales-system.local/api/').env('API_VERSION', 'v1').'/config/menu',[
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
            ]);

            $response = json_decode($response->getBody()->getContents());

            if($response->success){
                return $response->data->detail;
            }
        });

        return $menu;
    }
}
