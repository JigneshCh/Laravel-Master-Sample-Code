<?php
    namespace App;
    use App\BaseModel;
    class Categories extends BaseModel
    {
        //declare Tabel Name
        protected $table = "categories";
    
            //declare Fillable Variable
            protected $fillable = [
                'name','slug','icon','display_order' 
            ];
            
            public function articles(){
                return $this->hasmany("App\Articles", "category_id", "id");
            }
            
    }
