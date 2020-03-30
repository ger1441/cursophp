<?php
namespace App\Traits;

trait HasDefaultImage
{
    public function getImage($altText){
        if(!$this->logo){
            return "https://ui-avatars.com/api/?name=$altText&size=170";
        }

        return 'uploads/'.$this->logo;
    }
}
