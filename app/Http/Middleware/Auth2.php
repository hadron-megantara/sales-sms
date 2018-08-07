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
        }

        return $next($request);
    }

    public function buildMenu($menu, $isParent = false){
        $menuHtml = '';

        if($isParent){
            $menuHtml = '<li class="">';
            $menuHtml .= '<a href="javascript:;">';
            $menuHtml .= '<i class="fa fa-'.$menu->icon.'"></i>';
            $menuHtml .= '<span class="title">'.$menu->name.'</span>';
        }

        if(count($menu->sub) > 0){
            $subMenu = $this->buildMenu($menu);
        }

        $menuHtml .= '';

        if($isParent){
            $menuHtml .= '</li>';
        }

        return $menuHtml;
    }
}
