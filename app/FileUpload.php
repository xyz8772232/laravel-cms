<?php

namespace App;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class File
 *
 * @package \App
 */
class FileUpload
{
    const ACTION_KEEP = 0;
    const ACTION_REMOVE = 1;

    protected $directory = '';

    protected $name = null;

    protected $original;

    protected $storage = '';

    public function __construct()
    {
        $this->initStorage();
    }

    protected function initStorage()
    {
        $this->storage = Storage::disk(config('admin.upload.disk'));
    }

    public function defaultStorePath()
    {
        return config('admin.upload.directory.image');
    }

    public function move($directory, $name = null)
    {
        $this->directory = $directory;

        $this->name = $name;

        return $this;
    }

    public function prepare(UploadedFile $file = null, $addWatermark = false)
    {
        if (is_null($file)) {
            return '';
        }

        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name =  $file->getClientOriginalName();

        $this->original = $file->getRealPath();

        $originalPicPath =  $this->uploadAndDeleteOriginal($file);

        if ($addWatermark) {
            $img = Image::make(config('filesystems.disks.admin.root').'/'.$originalPicPath);
            $watermarkPath = config('filesystems.disks.admin.root').'/'.Watermark::find(1)->path;
            $img->insert($watermarkPath, 'bottom-right', 15, 10);
            $img->save();
        }

        return $originalPicPath;
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $target = $this->directory.'/'.$this->name;

        $this->storage->put($target, file_get_contents($file->getRealPath()));

        $this->destroy();

        return $target;
    }

    public function objectUrl($path)
    {
        return trim(config('admin.upload.host'), '/').'/'.trim($path, '/');
    }
    /**
     * @param $file
     *
     * @return void
     */
    public function renameIfExists(UploadedFile $file)
    {
        if ($this->storage->exists("$this->directory/$this->name")) {
            $this->name = md5(uniqid()).'.'.$file->guessExtension();
        }
    }

    public function destroy()
    {
        $this->storage->delete($this->original);
    }
}
