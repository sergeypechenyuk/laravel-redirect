<?php
namespace PSV\Widgets;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source', 'destination', 'code', 'expired_at',
    ];

    protected $table = "redirect";

    protected $dates = [
        'created_at',
        'updated_at',
        'expired_at',
    ];
}