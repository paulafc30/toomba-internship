<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryLink extends Model
{
    
    protected $table = 'temporarylinks';
    
    use HasFactory;

    protected $fillable = [
        'file_id',
        'token',
        'expires_at',
        'name',
        'email',
        'password', 
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}