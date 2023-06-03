<?php

namespace App\Helpers\Media\Models;

use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Models\MediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory, MediaDefaultPhotos;

    protected $fillable = ["filename", "path", "media_type", "type_id"];
    protected $appends = ["url"];

    public function mediaable(){
        return $this->morphTo();
    }

    public function getUrlAttribute(){
        return env("APP_URL") . "/" . trim($this->path, "/") . "/" . $this->filename;
    }

    public function mediaType()
    {
        return $this->hasOne(MediaType::class,"media_id");
    }

}
