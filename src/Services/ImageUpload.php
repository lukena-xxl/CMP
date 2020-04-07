<?php


namespace App\Services;

use App\Services\Common\SlugCreator;

class ImageUpload
{
    /**
     * @var SlugCreator
     */
    private $slugCreator;

    public function __construct(SlugCreator $slugCreator)
    {
        $this->slugCreator = $slugCreator;
    }

    public function base64ImageUpload(string $base64_image, string $main_dir, string $name_image = null, array $sub_dirs = [])
    {
        if (!empty($base64_image) && !empty($main_dir)) {
            static $num = 0;
            $data = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $base64_image));

            $name = $num . "-" . time() . ".jpg";
            if (!empty($name_image)) {
                $name = $this->slugCreator->createSlug($name_image) . "-" . $name;
            }

            $im = imagecreatefromstring($data);
            $W = imagesx($im);
            $H = imagesy($im);

            if (!empty($sub_dirs)) {
                foreach ($sub_dirs as $subdir) {
                    $out = $main_dir . "/" . $subdir . "/" . $name;

                    if (is_int($subdir)) {
                        $Ws = $subdir;
                        $Hs = ($subdir * $H / $W);
                        $ims = imagecreatetruecolor($Ws, $Hs);
                        imagecopyresampled($ims, $im, 0, 0, 0, 0, $Ws, $Hs, $W, $H);
                    } else {
                        $ims = $im;
                    }

                    imageinterlace($ims, true);
                    imagejpeg($ims, $out);
                    imagedestroy($ims);
                }
            }

            imageinterlace($im, true);
            $out = $main_dir . "/" . $name;
            imagejpeg($im, $out);
            imagedestroy($im);

            $num++;

            return $name;
        } else {
            return false;
        }
    }
}
