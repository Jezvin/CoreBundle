<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 22:17
 */

namespace Umbrella\CoreBundle\FileUpload;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Core\BaseService;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class FileManager
 * @package Umbrella\CoreBundle\FileUpload
 */
class FileManager extends BaseService
{

    /**
     * @return string
     */
    public function uploadDir()
    {
        return $this->webDir() . 'assets/';
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->uploadDir(), $fileName);
        return $fileName;
    }

    /**
     * @param UploadedFile $file
     * @return UmbrellaFile
     */
    public function createUbFileFromUpload(UploadedFile $file)
    {
        $filename = $this->upload($file);

        $ubFile = new UmbrellaFile();
        $ubFile->name = $file->getClientOriginalName();
        $ubFile->mimeType = $file->getMimeType();
        $ubFile->size = $file->getClientSize();
        $ubFile->md5 = md5_file($this->uploadDir() . $filename);
        $ubFile->fileName = $filename;
        return $ubFile;
    }

}