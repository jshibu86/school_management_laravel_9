<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use cms\Products\Models\Product;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $productname=$this->faker->unique()->words($nb=2,$asText=true);
        $slug=Str::slug($productname);
        return [
            'product_name' =>$productname ,
            'product_slug' => $slug,
            'product_code' => $this->faker->numberBetween(50,50000),
            'product_tags' => 'Vegtables,Kitchen,Home', // password
            'selling_price' => $this->faker->numberBetween(50,1000),
            'short_descp'=>$this->faker->text(200),
            'long_descp'=>$this->faker->text(500),
            'product_thambnail'=>'shopimages/upload/products/Vegtables/p'.$this->faker->unique()->numberBetween(1,30).'.jpg',
            
            'category_id'=>8,
        ];
    }
}
