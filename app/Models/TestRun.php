<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestRun extends Model
{
    protected $table = 'test_run';
    
    protected $fillable = [
        'username',
        'phpunit_exit',
        'project_code',
        'report_url',

        'token_used',

        'status',
        'error_message',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
