<?php

namespace App;

use Encore\Admin\Form\Field;
use GuzzleHttp\Client;
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

    protected $imageServer = false;

    public function __construct()
    {

        if (app('request')->root() == config('admin.image_host')) {
            $this->imageServer = true;
        }

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

    public function prepare(UploadedFile $file = null, $watermark = false)
    {
        if (is_null($file)) {
            return '';
        }

        if(!$this->imageServer) {
            return $this->uploadToRemote($file, $watermark);
        }

        $this->directory = $this->directory ?: $this->defaultStorePath();

        $this->name =  $file->getClientOriginalName();

        $this->original = $file->getRealPath();

        $originalPicPath =  $this->uploadAndDeleteOriginal($file, $watermark);

        return $originalPicPath;
    }

    protected function uploadToRemote(UploadedFile $file, $watermark = false)
    {
        $client = new Client([
            'base_uri' => config('admin.image_host'),
            'timeout' => 20,
        ]);

        $response = $client->request('POST', '/api/upload/image',
            ['multipart' => [
                ['name' => 'file', 'contents' => fopen($file->getRealPath(), 'r'), 'filename' => $file->getClientOriginalName(), ],
                ['name' => 'watermark', 'contents' => $watermark,],
            ]]);
        if ($response->getStatusCode() == 200) {
            return (string)$response->getBody();
        }
        return '';
    }

    /**
     * @param $file
     * @param $watermark
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file, $watermark = false)
    {
        $this->renameIfExists($file);

        $target = $this->directory.'/'.$this->name;

        if ($watermark) {
            $watermarkObject = Watermark::where('status', 1)->orderBy('id')->first();
            if ($watermarkObject) {
                $watermarkResource = $this->storage->get($watermarkObject->path);
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
