<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'template_id',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
