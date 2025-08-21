<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
	
	public function get_challenge_type()
    {
        return $this->hasOne(Challenge_type::class, 'id', 'challenge_id');
    }
}
