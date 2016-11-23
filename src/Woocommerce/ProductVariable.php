<?php
/**
 * Created by PhpStorm.
 * User: alkali
 * Date: 08.11.16
 * Time: 17:21
 */

namespace Corcel\Woocommerce;
use Corcel\Woocommerce\Product;

class ProductVariable extends Product
{
    public function variations() {
        return $this->hasMany('Corcel\Woocommerce\ProductVariation','parent_id','ID')
    }
}