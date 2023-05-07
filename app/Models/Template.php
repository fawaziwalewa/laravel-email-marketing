<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'subscriber_per_request',
        'request_interval',
        'status',
        'subject',
        'body',
    ];
}
