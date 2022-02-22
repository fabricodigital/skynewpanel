<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    protected $connection = 'solopertedev';

    protected $table = 'utentivoucher';
}
