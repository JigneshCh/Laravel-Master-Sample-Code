<?php
namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\View;

class ArticlesController extends Controller
{
    public function __construct() {
        
        $this->context = str_plural('article');
        $this->modal = "App\\".ucfirst($this->context);
        parent::__construct();
        View::share('context',  $this->context);

    } 
    
    function validationrule(){
 
        return [
            
            'title' => [
                'rule' =>'',
                'message' => [
                    '' => trans($this->context.'.validation.title_'),
                ]
            ],
            
            'slug' => [
                'rule' =>'',
                'message' => [
                    '' => trans($this->context.'.validation.slug_'),
                ]
            ],
            
            'desc' => [
                'rule' =>'',
                'message' => [
                    '' => trans($this->context.'.validation.desc_'),
                ]
            ],
            
            'description' => [
                'rule' =>'',
                'message' => [
                    '' => trans($this->context.'.validation.description_'),
                ]
            ],
            
            'status' => [
                'rule' =>'',
                'message' => [
                    '' => trans($this->context.'.validation.status_'),
                ]
            ],
            
            
        ];
 
    }
}
