<?php
namespace cms\Products\Database\seeds;
use Illuminate\Database\Seeder;
use cms\Products\Models\Product;
use Illuminate\Support\Str;

class Productseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	 $productname=$this->faker->unique()->words($nb=2,$asText=true);
        $slug=Str::slug($productname);

        Product::create([
            'product_name' =>$productname ,
            'product_slug' => $slug,
            'product_code' => $this->faker->numberBetween(50,50000),
            'product_tags' => 'Vegtables,Kitchen,Home', // password
            'selling_price' => $this->faker->numberBetween(50,1000),
            'short_descp'=>$this->faker->text(200),
            'long_descp'=>$this->faker->text(500),
            'product_thambnail'=>'shopimages/upload/products/Vegtables/p'.$this->faker->unique()->numberBetween(1,30).'.jpg',
            
            'category_id'=>8,
            
        ]);
    }
}
