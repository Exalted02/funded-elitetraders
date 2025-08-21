<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc_documents extends Model
{
    use HasFactory;
	protected $fillable = [
        'client_id',
        'frontal',
        'back',
        'residence',
        'email_status',
        'status',
    ];
	
	public function get_client()
	{
		return $this->belongsTo(User::class, 'client_id');
	}
	
}
