<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'api_token_id', 'endpoint', 'credits_used',
        'request_data', 'response_data', 'ip_address', 'called_at'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'called_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apiToken()
    {
        return $this->belongsTo(ApiToken::class);
    }
}
