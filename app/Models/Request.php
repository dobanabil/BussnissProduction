<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;
use Illuminate\Support\Facades\Storage;


class Request extends Model
{
      use Favoritable, Translatable,Reviewable;
      


    protected $appends = ['average_rating'];

    public function getImagesAttribute($value)
    {
        $images =  json_decode($value);

        if(is_Array($images) && count($images)){
            foreach($images as $image){
                $isExists = Storage::exists('public/'.$image);

                if (is_null($image) || empty($image)  || !$isExists) {
                    $avalible_images[] = 'products/default.jpg' ;
                }else{
                    $avalible_images[] = $image ;

                }
            }
            $avalible_images = array_unique($avalible_images);
            
            return json_encode($avalible_images);
        }

        return json_encode(['products/default.jpg']);

    }


    public function getAverageRatingAttribute()
    {
        return (int)$this->reviews()->average('stars');
    }
    
    protected $translatable = ['name', 'description'];
    	public function category() {
		return $this->belongsTo('App\Models\Category','category_id');
	}
    	public function user() {
		return $this->belongsTo('App\Models\User','user_id');
	}
}
