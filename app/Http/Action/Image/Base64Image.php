<?php

namespace App\Http\Action\Image;

use App\Exceptions\InvalidMimeTypeException;
use App\Http\Const\DefaultConst;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Base64Image {
    public null|string $name;
    private string $base64String;
    public null|string $mimeType;
    public int $size = 0;
    private array $allowedMimeTypes = [
        'jpeg',
        'png',
        'webp',
    ];

    public function __construct(string $base64Image) {
        $this->name = $this->name();
        $this->parseBase64String($base64Image);
    }
    private function parseBase64String($base64Image){
        $imageArray = explode(',', $base64Image);
        if(count($imageArray) != 2 ){
            throw new Exception(DefaultConst::INVALID_IMAGE);
        }
        $this->mimeType = $this->MimeType($imageArray[0]);

        if (!in_array($this->mimeType, $this->allowedMimeTypes) || empty($this->mimeType)) {
            throw new InvalidMimeTypeException(DefaultConst::INVALID_MIME_TYPE);
        }
        $this->base64String = $imageArray[1];
    }

    public function save(string $path)
    {
        try{
            $stream = fopen(storage_path('app/'.$path.$this->name.'.'.$this->mimeType), 'wb');
            fwrite($stream, base64_decode($this->base64String));
            fclose($stream);
            $this->size = $this->getSize(storage_path('app/'.$path.'/'.$this->name.'.'.$this->mimeType))??0;
            return $path.$this->name.'.'.$this->mimeType;
        }catch (Exception $e){
            throw new Exception('Failed to save image:'.$e->getMessage());
        }
    }
    private function name(){
        return Str::uuid();
    }

    private function MimeType($base64ImageHeader){
        return preg_match('/^data:image\/(.*?);base64/', $base64ImageHeader, $matches) ? $matches[1] : null;
    }
    public function getSize($path){
        return filesize($path);
    }
}
