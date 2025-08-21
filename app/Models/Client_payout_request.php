<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client_payout_request extends Model
{
    use HasFactory;
	
	public function get_user_details()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
