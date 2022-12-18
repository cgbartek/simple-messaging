<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use DB;

class ChannelsController extends Controller
{
    public $user;
    public function __construct() {
      $this->middleware(function ($request, $next) {
          $this->user = Auth::user();
          return $next($request);
      });
    }


    /**
     * Display a listing of channels.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : Response
    {
        if (!Auth::check()) {
          $output['result'] = 'User not logged in.';
          $response = new Response($result, Response::HTTP_OK);
          return $response;
        }

        $uid = Auth::user()->id;

        $rows = DB::select('select channel_members.cid,channel_name,created_by from channel_members left join channels ON channel_members.cid = channels.cid where uid = ?', array($uid));
        $rows = json_decode(json_encode($rows,1),1);

        $response = new Response($rows, Response::HTTP_OK);
        return $response;
    }

    /**
     * Store a newly created channel in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : Response
    {
        $output = [];
        if($request['msg']) {
          $uid = Auth::user()->id;

          $messageData = array('message' => $request['msg'], 'user_to' => $request['recipient'], 'user_from' => $uid);
          Messages::create($messageData);
          $output['result'] = 'Message added.';
        } else {
          $output['result'] = 'Error.';
        }

        $response = new Response($output, Response::HTTP_OK);
        return ($response);
    }

    /**
     * Update the specified channel in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) : Response
    {
    }

    /**
     * Remove the specified channel from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) : Response
    {
    }

    /**
     * Display a listing of channel users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChannelUsers() : Response
    {
        if (!Auth::check()) {
          $output['result'] = 'User not logged in.';
          $response = new Response($result, Response::HTTP_OK);
          return $response;
        }

        $id = Auth::user()->id;

        $messages = new Messages();
        $rows = $messages->select('user_to','user_from')->where('user_to',$id)->orWhere('user_from',$id)->get()->toArray();

        $userIds = array();
        foreach($rows as $v) {
          $userIds[] = $v['user_to'];
          $userIds[] = $v['user_from'];
        }
        $userIds = array_unique($userIds);
        if (($myId = array_search($id, $userIds)) !== false) {
          unset($userIds[$myId]);
        }

        // get usernames for each id
        $usr = new Users();
        $userNames = json_decode(json_encode($usr->getUsers(),1),1);
        $userNamesArr = array();
        foreach($userNames as $k => $v) {
          $userNamesArr[$v['id']] = $v['name'];
        }
        $users = array();
        foreach ($userIds as $k => $v) {
          $users[$v] = $userNamesArr[$v];
        }

        $response = new Response($users, Response::HTTP_OK);
        return $response;
    }
}
