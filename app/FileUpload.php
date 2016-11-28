<?php

namespace App;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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

        $originalPicPath =  $this->uploadAndDeleteOriginal($file, $addWatermark);

        return $originalPicPath;
    }

    /**
     * @param $file
     * @param $addWatermark
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file, $addWatermark = false)
    {
        $this->renameIfExists($file);

        $target = $this->directory.'/'.$this->name;

        if ($addWatermark) {
            $watermark = Watermark::where('status', 1)->orderBy('id')->first();
            if ($watermark) {
                $watermarkResource = $this->storage->get($watermark->path);
                $img = Image::make($file->getRealPath());
                $img->insert($watermarkResource, 'bottom-right', 2, 2);
                $img->save();
            }
        }
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
