<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Users extends Model
{
    //use SoftDeletes;
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'lat',
        'lng'
    ];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Indicates if the ID is auto-incrementing
     *
     * @var bool
     */
    public $incrementing = true;


    public function getUsers() {
      $users = DB::table('users')->select('id','name')->get()->toArray();
      return $users;
    }
}
