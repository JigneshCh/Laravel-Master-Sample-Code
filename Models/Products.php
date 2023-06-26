<?php
    namespace App;
    use App\BaseModel;
    class Products extends BaseModel
    {
        //declare Tabel Name
        protected $table = "products";
    
            //declare Fillable Variable
            protected $fillable = [
                'category_id','name','slug','desc','price','price_currency' 
            ];
        
            public function categories(){
                return $this->hasone("App\Categories", "category_id", "id");
            }
             
    }
