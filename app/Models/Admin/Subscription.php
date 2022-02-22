<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $connection = 'solopertedev';

    protected $table = 'tipologia_abbonamento_new';
}
