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
    ];
}
