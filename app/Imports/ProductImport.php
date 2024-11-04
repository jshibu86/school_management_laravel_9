<?php

namespace App\Imports;

use cms\Products\Models\Product;
use cms\Products\Models\Multitump;
use cms\category\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Image;
use Carbon\Carbon;
Use Exception;

class ProductImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public $cat_id;
    public $count;
    public function model(array $row)
    {

            ++$this->count;
            $pdata=Product::where('product_name',$row["productname"])->first();
            if(is_null($pdata)){
         
            $catdata=Category::where('status',1)->where('id',$this->cat_id)->first();
        $cat_name=$catdata->cat_name;
        
        $data = new Product;
        $no=rand(0,9999999);
        $data->product_code=$no;
        $data->category_id=$this->cat_id;
        $data->product_name=$row['productname'];

        $slug=preg_replace('/[^a-zA-Z0-9_-]/s','',$row['productname']);
        $data->product_slug=strtolower(str_replace(' ', '-', $slug));
        $data->product_qty=$row['qty'];
        $data->product_tags=$row['tags'];
        $data->product_size=$row['size'];
        $data->product_color=$row['color'];
        $data->selling_price=$row['price'];
        $data->unit_qty=$row['unit'];
        $data->short_descp=$row['shortdesc'];
        $data->long_descp=$row['longdesc'];
        $data->created_at=Carbon::now();

        
      
        $uploadPath = '/shopimages/upload/products/'. $cat_name.'/'.$row['image'];


        $data->product_thambnail=$uploadPath;
     
        if($data->save()){


            $pid=$data->id;

            $pimage = explode (",", $row['multiimage']);

            foreach($pimage as $image){

           
                    $uploadPath = '/shopimages/upload/products/'. $cat_name.'/'.$image;
        
        Multitump::insert([

            'product_id' => $pid,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now() 

        ]);

            }
        }
           


            

        
        
            
    }else{

        $catdata=Category::where('status',1)->where('id',$this->cat_id)->first();
        $cat_name=$catdata->cat_name;
       
        $data =Product::find($pdata->id);
       if($data->category_id==$this->cat_id){
        $no=$data->product_code;
         $data->product_code=$no;

       }else{
         $no=rand(0,9999999);
        $data->product_code=$no;
       }
       
        $data->category_id=$this->cat_id;
        $data->product_name=$row['productname'];

        $slug=preg_replace('/[^a-zA-Z0-9_-]/s','',$row['productname']);
        $data->product_slug=strtolower(str_replace(' ', '-', $slug));
        $data->product_qty=$row['qty'];
        $data->product_tags=$row['tags'];
        $data->product_size=$row['size'];
        $data->product_color=$row['color'];
        $data->selling_price=$row['price'];
        $data->unit_qty=$row['unit'];
        $data->short_descp=$row['shortdesc'];
        $data->long_descp=$row['longdesc'];
        $data->created_at=Carbon::now();
        
      
        $uploadPath = '/shopimages/upload/products/'. $cat_name.'/'.$row['image'];


        $data->product_thambnail=$uploadPath;


      
        


        if($data->save()){


            $pid=$data->id;

            $pimage = explode (",", $row['multiimage']);

            foreach($pimage as $image){

          
                    $uploadPath = '/shopimages/upload/products/'. $cat_name.'/'.$image;
        
        Multitump::insert([

            'product_id' => $pid,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now() 

        ]);

            }
        }
    }
        
    }





    public function getcount():int
    {
        return count(array($this->count));
    }
}
