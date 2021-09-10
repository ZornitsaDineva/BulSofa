<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    use HasFactory;

    protected $primaryKey="recharge_request_id";
    protected $table='recharge_requests';
    
}
