<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 06/06/17
 * Time: 23:16
 */

namespace Umbrellac\CoreBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrellac\CoreBundle\Component\Core\BaseService;
use Umbrellac\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileUploader
 */
class UmbrellaFileUploader extends BaseService
{

    /**
     * @var string
     */
    private $assetPath;

    /* Call by Bundle configurator */

    public function loadConfig(array $config)
    {
        $this->assetPath = '/' . trim($config['asset_path'], '/') . '/';
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->assetPath;
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->webDir() . $this->assetPath;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $this->createAssetDirectory();

        $filename = md5(uniqid('', true));
        if (!empty($file->guessClientExtension())) {
            $filename .= '.' . $file->guessClientExtension();
        }
        $file->move($this->getAbsolutePath(), $filename);

        return $this->getPath() . $filename;
    }

    /**
     * Create Umbrella file from UploadedFile
     * If upload set to true => process upload else upload will be processed on postPersist
     *
     * @param UploadedFile $file
     * @param bool $upload
     * @return UmbrellaFile
     */
    public function createUmbrellaFile(UploadedFile $file, $upload = false)
    {
        $umbrellaFile = new UmbrellaFile();
        $umbrellaFile->name = $file->getClientOriginalName();
        $umbrellaFile->md5 = md5_file($file->getRealPath());
        $umbrellaFile->mimeType = $file->getMimeType();
        $umbrellaFile->size = $file->getSize();

        if ($upload) {
            $umbrellaFile->path = $this->upload($file);
        } else {
            $umbrellaFile->file = $file;
        }
        return $umbrellaFile;
    }

    /**
     * Create asset direcory
     */
    public function createAssetDirectory()
    {
        if (!is_dir($this->getAbsolutePath())) {
            @mkdir($this->getAbsolutePath(), 0777, true);
        }
    }
}