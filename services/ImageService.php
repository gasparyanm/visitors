<?php
if ( !isset( $_SERVER['HTTP_REFERER']) ) die ("Direct access not permitted");

class ImageService {
    private ?string $imagePath = null;
    public const NO_IMAGE = 'No image found';

    private function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $path): ImageService
    {
        $this->imagePath = $path;

        return $this;
    }

    private function addHeaders(string $name)
    {
        $contentType = mime_content_type($name);

        header("Content-Type: " . $contentType);
        header("Content-Length: " . filesize($name));
    }

    public function showImage()
    {
        if (!file_exists($this->imagePath)) {
            echo self::NO_IMAGE;

            return false;
        }

        $file = fopen($this->imagePath, 'rb');

        $this->addHeaders($this->imagePath);

        fpassthru($file);

        fclose($file);
    }
}
?>