<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectoryAvatar;
    private $targetDirectoryIllustrations;

    public function __construct($targetDirectoryAvatar, $targetDirectoryIllustrations)
    {
        $this->targetDirectoryAvatar = $targetDirectoryAvatar;
        $this->targetDirectoryIllustrations = $targetDirectoryIllustrations;
    }

    public function upload(UploadedFile $file, $type)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDirectory($type), $fileName);

        return $fileName;
    }

    public function getTargetDirectory($type)
    {
        if($type == 'avatar')
        {
            return $this->targetDirectoryAvatar;

        }
        else
        {
            return $this->targetDirectoryIllustrations;
        }
    }


}