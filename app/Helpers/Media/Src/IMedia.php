<?php

namespace App\Helpers\Media\Src;

interface IMedia{
    const ONEMEDIA = 1;
    const MANYMEDIA = 2;
    /**
     * Set Main Directory Path After Uploads Directory
     */
    public function setMainDirectoryPath() : string;
    public function files($relation);
    public function setGroups();
    public function initizeMedia(\Illuminate\Http\UploadedFile  $file, $group);
    public function saveMedia(\Illuminate\Http\UploadedFile  $file, $group);
    public function getMediaFiles();
    public function removeMedia($media);
    public function removeAllFiles() : bool;
    public function removeAllGroupFiles($group) : bool;
    public function getFirstMediaFile($group);

}
