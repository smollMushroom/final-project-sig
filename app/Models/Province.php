<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'total_visitors', 'labor_wages_avg', 'total_SD', 'total_SMP', 'total_SMA', 'total_SMK', 'coordinates'];
}
