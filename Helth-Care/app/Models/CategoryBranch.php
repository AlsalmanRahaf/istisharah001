<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryBranch extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    public function setMainDirectoryPath(): string
    {
        return "category-branch";
    }
}
