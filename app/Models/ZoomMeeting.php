<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoomMeeting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
      'meeting_id',
      'topic',
        'start_time',
        'end_time',
      'agenda',
      'duration',
      'password',
        'response',
        'extras',
        'start_url',
        'join_url',
        'response',
        'extras'
    ];

    protected $casts = [
        'response'=>'array',
        'extras' => 'array'
    ];
}
