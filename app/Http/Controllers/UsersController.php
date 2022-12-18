<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public $user;
    public function __construct() {
      $this->middleware(function ($request, $next) {
          $this->user = Auth::user();
          return $next($request);
      });
    }


    /**
     * Get user info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserInfo(Request $request) : Response
    {
        if (!Auth::check()) {
          $output['result'] = 'User not logged in.';
          $response = new Response($result, Response::HTTP_OK);
          return $response;
        }

        $uid = Auth::user()->id;

        Users::find($uid)->update(['lat'=>$request['lat'], 'lng'=>$request['lng']]);

        $output['uid'] = $uid;
        $output['username'] = Auth::user()->name;

        $response = new Response($output, Response::HTTP_OK);
        return $response;
    }

    /**
     * Get locations of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersInfo() : Response
    {
        $users = new Users();
        $userData = $users->select('id','name','email','lat','lng');
        $rows = $userData->get()->toArray();

        $response = new Response($rows, Response::HTTP_OK);
        return $response;
    }


    /**
     * Remove user location info.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeUserLocInfo(Request $request) : Response
    {
      $uid = Auth::user()->id;

      Users::find($uid)->update(['lat'=>null, 'lng'=>null]);

      $output['result'] = 'OK';

      $response = new Response($output, Response::HTTP_OK);
      return $response;
    }
}
