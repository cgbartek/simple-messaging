<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Channels extends Model
{
    //use SoftDeletes;
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'channels';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_name',
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


}
