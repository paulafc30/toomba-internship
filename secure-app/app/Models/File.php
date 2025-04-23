<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';
    public $timestamps = true;
    protected $fillable = ['folder_id', 'name', 'path', 'size', 'mime_type'];

    /**
     * Get the folder that the file belongs to.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

}