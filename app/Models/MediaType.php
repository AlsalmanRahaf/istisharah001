<?php

namespace App\Models;

use App\Helpers\Media\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaType extends Model
{
    use HasFactory;
    protected $fillable = [
       'media_id', 'media_type'
    ];
    public function media()
    {
        return $this->belongsTo(Media::class,"media_id");
    }
}

