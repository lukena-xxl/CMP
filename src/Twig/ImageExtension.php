<?php

namespace App\Twig;

use Symfony\Component\Config\Definition\Exception\Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('img_width', [$this, 'getWidth']),
            new TwigFilter('img_height', [$this, 'getHeight']),
            new TwigFilter('img_format', [$this, 'getFormat']),
            new TwigFilter('img_type', [$this, 'getType']),
            new TwigFilter('img_length', [$this, 'getLength']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getImgWidth', [$this, 'getWidth']),
            new TwigFunction('getImgHeight', [$this, 'getHeight']),
            new TwigFunction('getImgFormat', [$this, 'getFormat']),
            new TwigFunction('getImgType', [$this, 'getType']),
            new TwigFunction('getImgLength', [$this, 'getLength']),
            new TwigFunction('fileExists', [$this, 'fileExists']),
        ];
    }

    private function getAbsolutePath($path)
    {
        return $_SERVER['DOCUMENT_ROOT'].$path;
    }

    private function readImage($path)
    {
        $im = new \Imagick();

        try {
            $im->readImage($this->getAbsolutePath($path)."[0]");
        } catch (\ImagickException $e) {
            throw new Exception($e->getMessage());
        }

        return $im;
    }

    public function getWidth($path)
    {
        return $this->readImage($path)->getImageWidth();
    }

    public function getHeight($path)
    {
        return $this->readImage($path)->getImageHeight();
    }

    public function getFormat($path)
    {
        return $this->readImage($path)->getImageFormat();
    }

    public function getType($path)
    {
        return $this->readImage($path)->getImageMimeType();
    }

    public function getLength($path)
    {
        $size = $this->readImage($path)->getImageLength();
        $k = 1000; // коэффициент пересчета
        $precision = 2; // точность

        if ($size > $k) {
            $size = ($size/$k);
            if ($size > $k) {
                $size = ($size/$k);
                if ($size > $k) {
                    $size = ($size/$k);
                    $size = round($size, $precision);
                    return $size . " GB";
                } else {
                    $size = round($size, $precision);
                    return $size . " MB";
                }
            } else {
                $size = round($size, $precision);
                return $size . " KB";
            }
        } else {
            $size = round($size, $precision);
            return $size . " B";
        }
    }

    public function fileExists($path)
    {
        return file_exists($this->getAbsolutePath($path));
    }
}
