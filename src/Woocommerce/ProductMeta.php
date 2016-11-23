<?php
/**
 * Created by PhpStorm.
 * User: alkali
 * Date: 08.11.16
 * Time: 17:11
 */

namespace Corcel\Woocommerce;

use Corcel\PostMeta;

/**
 * Abstract Product Class
 *
 * The WooCommerce product class handles individual product data.
 *
 * @class       WC_Product
 * @var         WP_Post
 * @version     2.1.0
 * @package     WooCommerce/Abstracts
 * @category    Abstract Class
 * @author      WooThemes
 *
 * @property    string $width Product width
 * @property    string $length Product length
 * @property    string $height Product height
 * @property    string $weight Product weight
 * @property    string $price Product price
 * @property    string $regular_price Product regular price
 * @property    string $sale_price Product sale price
 * @property    string $product_image_gallery String of image IDs in the gallery
 * @property    string $sku Product SKU
 * @property    string $stock Stock amount
 * @property    string $downloadable Shows/define if the product is downloadable
 * @property    string $virtual Shows/define if the product is virtual
 * @property    string $sold_individually Allow one item to be bought in a single order
 * @property    string $tax_status Tax status
 * @property    string $tax_class Tax class
 * @property    string $manage_stock Shows/define if can manage the product stock
 * @property    string $stock_status Stock status
 * @property    string $backorders Whether or not backorders are allowed
 * @property    string $featured Featured product
 * @property    string $visibility Product visibility
 * @property    string $variation_id Variation ID when dealing with variations
 */
class ProductMeta extends PostMeta
{

    /**
     * __get function.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = $this->{'_' . $key};

        // Get values or default if not set
        if (in_array($key, array('downloadable', 'virtual', 'backorders', 'manage_stock', 'featured', 'sold_individually')))
        {
            $value = $value ? $value : 'no';

        }
        elseif (in_array($key, array('product_attributes', 'crosssell_ids', 'upsell_ids')))
        {
            $value = $value ? $value : array();

        }
        elseif ('visibility' === $key)
        {
            $value = $value ? $value : 'hidden';

        }
        elseif ('stock' === $key)
        {
            $value = $value ? $value : 0;

        }
        elseif ('stock_status' === $key)
        {
            $value = $value ? $value : 'instock';

        }
        elseif ('tax_status' === $key)
        {
            $value = $value ? $value : 'taxable';

        } else {
            $value = parent::__get($key);
        }

        return $value;
    }


    /**
     * __isset function.
     *
     * @param mixed $key
     * @return bool
     */
    public function __isset($key)
    {
        return !is_null($this->{'_' . $key});
    }
}