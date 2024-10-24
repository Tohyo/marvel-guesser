<?php

namespace App\ImageGenerator;

class Pixelate
{
    public function generate(string $filename): void
    {
        
        $pixaleConfigs = [
            [
                'amount' => 45,
                'filename' => 1
            ],
            [
                'amount' => 35,
                'filename' => 2
            ],
            [
                'amount' => 25,
                'filename' => 3
            ],
            [
                'amount' => 15,
                'filename' => 4
            ],
            [
                'amount' => 1,
                'filename' => 5
            ]
            
        ];

        foreach ($pixaleConfigs as $config) {
            $this->pixelate($filename, $config);
        }
    }

    private function pixelate(string $filename, array $pixaleConfig): void
    {
        $image = ImageCreateFromJPEG($filename);
        
        $imagex = imagesx($image);
        $imagey = imagesy($image);

        # Create version of the original image:
        $tmpImage = ImageCreateTrueColor($imagex, $imagey);
        imagecopyresized($tmpImage, $image, 0, 0, 0, 0, round($imagex / $pixaleConfig['amount']), round($imagey / $pixaleConfig['amount']), $imagex, $imagey);
        
        # Create 100% version ... blow it back up to it's initial size:
        $pixelated = ImageCreateTrueColor($imagex, $imagey);
        imagecopyresized($pixelated, $tmpImage, 0, 0, 0, 0, $imagex, $imagey, round($imagex / $pixaleConfig['amount']), round($imagey / $pixaleConfig['amount']));
        
        header("Content-Type: image/JPEG");
        
        imageJPEG($pixelated, __DIR__ . "/../../assets/images/perso-".$pixaleConfig['filename'].".jpg", 75);
    }
}
