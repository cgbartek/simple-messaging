<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Messages;
use App\Models\Users;
use App\Models\Channels;
use Illuminate\Support\Facades\Auth;
use DB;

class MessagesController extends Controller
{
    public $user;
    public function __construct() {
      $this->middleware(function ($request, $next) {
          $this->user = Auth::user();
          return $next($request);
      });
    }


    /**
     * Display a listing of messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id, $channel = 0) : Response
    {
        if (!Auth::check()) {
          $output['result'] = 'User not logged in.';
          $response = new Response($result, Response::HTTP_OK);
          return $response;
        }

        $uid = Auth::user()->id;

        $messages = new Messages();
        if(!$channel) {
          $rows = DB::select('select * from messages where (user_to = ? and user_from = ?)
          or (user_to = ? and user_from = ?)', array($uid, $id, $id, $uid));
        } else {
          $rows = DB::select('select * from messages where channel = ?', array($id));
        }

        $rows = json_decode(json_encode($rows,1),1);

        // get usernames for each id
        $usr = new Users();
        $userNames = json_decode(json_encode($usr->getUsers(),1),1);
        $userNamesArr = array();
        foreach($userNames as $k => $v) {
          $userNamesArr[$v['id']] = $v['name'];
        }

        foreach($rows as $k => $v) {
          if($v['user_to'] == $uid) {
            Messages::find($v['id'])->update(['user_to_ack'=>1]);
          }

          if(isset($userNamesArr[$v['user_to']])) {
            $rows[$k]['user_to_name'] = $userNamesArr[$v['user_to']];
          } else {
            $rows[$k]['user_to_name'] = '';
          }

          if(isset($userNamesArr[$v['user_from']])) {
            $rows[$k]['user_from_name'] = $userNamesArr[$v['user_from']];
          } else {
            $rows[$k]['user_from_name'] = '';
          }

        }

        $response = new Response($rows, Response::HTTP_OK);
        return $response;
    }

    /**
     * Store a newly created message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : Response
    {
        $output = [];
        if($request['msg']) {
          $uid = Auth::user()->id;

          if(!$request['recipients']) {
            // One recipient
            if(!$request['channel']) {
              $messageData = array('message' => $request['msg'], 'user_to' => $request['recipient'], 'user_from' => $uid);
            } else {
              $messageData = array('message' => $request['msg'], 'channel' => $request['recipient'], 'user_from' => $uid);
            }

            Messages::create($messageData);
            $output['result'] = 'Message added.';
          } else {
            // Multiple recipients
            $recipients = $request['recipients'];
            $recipients = str_replace(';',',',$recipients);
            $recipients = str_replace(', ',',',$recipients);
            $recipients = explode(',',$request['recipients']);
            foreach($recipients as $k => $recipient) {
              $recipient = trim($recipient);
              if($recipient[0] == '#') {
                $channelName = str_replace('#','',$recipient);
                // Find/create channel
                $channels = new Channels();
                $channel = $channels->select('cid','channel_name')->where('channel_name',$channelName)->get()->toArray();
                $channel = $channel[0] ?? null;
                if(isset($channel['cid']) && $channel['cid']) { // Found the channel
                  $messageData = array('message' => $request['msg'], 'channel' => $channel['cid'], 'user_from' => $uid);
                  Messages::create($messageData);
                } else {
                  $cid = DB::table('channels')->insertGetId(['channel_name' => $channelName, 'created_by' => $uid]);
                  $messageData = array('message' => $request['msg'], 'channel' => $cid, 'user_from' => $uid);
                  Messages::create($messageData);
                }

              } else {
                $users = new Users();
                $user = $users->select('id','name')->where('name',$recipient)->get()->toArray();
                print_r($user);
                $user = $user[0] ?? null;
                if(isset($user['id']) && $user['id']) { // Found the user
                  $messageData = array('message' => $request['msg'], 'user_to' => $user['id'], 'user_from' => $uid);
                  Messages::create($messageData);
                }

              }

            }
            $output['result'] = 'Messages added.';
          }

        } else {
          $output['result'] = 'Error.';
        }

        $response = new Response($output, Response::HTTP_OK);
        return ($response);
    }

    /**
     * Update the specified message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) : Response
    {
    }

    /**
     * Remove the specified message from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id) : Response
    {
        $messages = Messages::find($id);
        if($messages) {
          $destroy = Messages::destroy($id);
          $output['result'] = 'Message deleted.';
        } else {
          $output['result'] = 'Delete error.';
        }

        $response = new Response($output, Response::HTTP_OK);
        return $response;
    }

    /**
     * Display a listing of messaged users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessagedUsers() : Response
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
          if(isset($userNamesArr[$v])) {
            $users[$v] = $userNamesArr[$v];
          } else {
            //$users[$v] = '';
          }

        }

        $response = new Response($users, Response::HTTP_OK);
        return $response;
    }


    /**
     * Get all users unacknowledged messages
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnack(Request $request, $recipient, $channel = 0) : Response
    {
        $uid = Auth::user()->id;

        $messages = new Messages();
        if(!$channel) {
          $rows = $messages->where('user_to_ack','!=',1)->where('user_to',$uid)->where('user_from',$recipient)->get()->toArray();
        } else {
          $rows = $messages->where('channel',$recipient)->get()->toArray();
        }

        $response = new Response($rows, Response::HTTP_OK);
        return $response;
    }
}
