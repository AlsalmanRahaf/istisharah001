<?php
namespace App\Helpers\Media\Src;


use App\Helpers\Media\Models\Media;
use App\Models\MediaType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

trait MediaInitialization {



    protected ?string $uploadMainPath = null;
    protected ?string $urlMainPath = null;
    protected array $groups = [];

    public function __construct()
    {
        $mediaGroup = $this->setGroups();
        foreach ($mediaGroup->getAllGroups() as $group){
            $this->groups[$group->getName()] = ["path" => trim($group->getSavingPath(), DS), "type" => $group->getType()];
        }
        $this->uploadMainPath = "uploads" . DS . trim($this->setMainDirectoryPath(),  config("global.ds"));
        $this->urlMainPath = trim(str_replace("\\", "/", $this->uploadMainPath), "/");
        unset($mediaGroup);
    }

    public function setGroups() : MediaGroups{
        return (new MediaGroups())
            ->setGroup("single", "main",DS);
    }


    public function upload($file, $directoryPath){
        $imageName = time() . rand(0,100000000000) * 35 . "." . $file->getClientOriginalExtension();
        $file->move($directoryPath, $imageName);
        return $imageName;
    }


    public function files($relation = 2){
        if($relation == 1)
            return $this->morphOne(Media::class, 'mediaable', 'media_type', 'type_id');
        else
            return $this->morphMany(Media::class, 'mediaable', 'media_type', 'type_id');
    }

    public function getMediaFiles($group = "main"){
        return $this->files($this->groups[$group]["type"])->where("group", $group)->get();
    }

    public function getFirstMediaFile($group = "main"){
        try {
            if(!isset($this->groups[$group]))
                throw new \Exception("the group is not found or the initialize it is incomplete.");

            if($this->groups[$group]["type"] == 1)
                return $this->files($this->groups[$group]["type"])->where("group", $group)->first();
            else
                return $this->files($this->groups[$group]["type"])->where("group", $group)->get();

        }catch (\Exception $e){
            die($e->getMessage());
        }
    }




    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  UploadedFile  $file
     */
    public function initizeMedia(UploadedFile $file, $group){
        try {
            if(!isset($this->groups[$group]))
                throw new \Exception("the group is not found or the initialize it is incomplete.s");
            $groupPath = trim($this->groups[$group]["path"], config("global.ds"));

            $uploadPath = $this->uploadMainPath . config("global.ds") .  $this->id . ($groupPath ? config("global.ds") . $groupPath : '') ;
            $media = new Media();
            $filename = $this->upload($file, $uploadPath);
            $media->filename = $filename;
            $media->group = $group;
            $media->path = $this->urlMainPath. '/' . $this->id . ($groupPath ? '/' . $groupPath : '');
            return $media;
        }catch (\Exception $e){
            die($e->getMessage());
        }

    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  UploadedFile  $path
     */
    public function saveMedia(UploadedFile $file, $group = "main"){
        $media = $this->initizeMedia($file, $group);
        return $this->files($this->groups[$group]["type"])->save($media);
    }

    public function removeMedia($media){
        if($media instanceof \Illuminate\Database\Eloquent\Collection){
            foreach ($media as $file){
                File::delete($file->path . config("global.ds") . $file->filename);
                $file->delete();
            }
        }else{
            $file = $media;
            File::delete($file->path . config("global.ds") . $file->filename);
            $file->delete();
        }
    }

    public function removeAllFiles() : bool{
        if($this->files){
            if($this->files instanceof \Illuminate\Database\Eloquent\Collection){
                foreach ($this->files as $file)
                    $file->delete();
            }else
                $this->files->delete();

            return $this->removeDir($this->uploadMainPath . config("global.ds") . $this->id);
        }
        return false;
    }

    public function removeAllGroupFiles($group) : bool{
        $files = $this->files($this->groups[$group]["type"])->where("group", $group)->get();
        if($files->isNotEmpty()){
            if($files instanceof \Illuminate\Database\Eloquent\Collection){
                foreach ($files as $file)
                    $file->delete();
            }else
                $this->files->delete();

            return $this->removeDir($this->uploadMainPath . config("global.ds") . $this->id . DS . $this->groups[$group]["path"]);
        }
        return false;
    }

    protected function removeDir($path){
        if(is_dir($path)){
            $files = glob($path . "*" , GLOB_MARK);
            foreach($files as $file){
                $this->removeDir($file);
            }
            if(is_dir($path))
                rmdir($path);
        }elseif(is_file($path)){
            unlink($path);
        }
        return true;
    }




}
