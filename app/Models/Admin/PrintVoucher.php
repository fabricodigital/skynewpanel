<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class PrintVoucher extends Model
{
    protected $connection = 'solopertedev';

    protected $table = 'stampavoucher';
}
