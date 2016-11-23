<?php
/**
 * Created by PhpStorm.
 * User: alkali
 * Date: 08.11.16
 * Time: 17:21
 */

namespace Corcel\Woocommerce;

use Corcel\Post;

class ProductVariation extends Post
{
    /**
     * Type of post.
     *
     * @var string
     */
    protected $postType = 'product_variation';

    /** @private array Data which is only at variation level - no inheritance plus their default values if left blank. */
    protected $variation_level_meta_data = array(
        'downloadable' => 'no',
        'virtual' => 'no',
        'manage_stock' => 'no',
        'sale_price_dates_from' => '',
        'sale_price_dates_to' => '',
        'price' => '',
        'regular_price' => '',
        'sale_price' => '',
        'stock' => 0,
        'stock_status' => 'instock',
        'downloadable_files' => array()
    );

    /** @private array Data which can be at variation level, otherwise fallback to parent if not set. */
    protected $variation_inherited_meta_data = array(
        'tax_class' => '',
        'backorders' => 'no',
        'sku' => '',
        'weight' => '',
        'length' => '',
        'width' => '',
        'height' => ''
    );

    public function getVariationIdAttribute()
    {
        return $this->ID;
    }


    public function getProductIdAttribute()
    {
        return $this->parent_id;
    }

    public function parent()
    {
        return $this->belongsTo('Corcel\Product', 'parent_id', 'ID');
    }

    /**
     * __isset function.
     *
     * @param mixed $key
     * @return bool
     */
    public function __isset($key)
    {
        if (in_array($key, array_keys($this->variation_level_meta_data))) {
            return !is_null($this->meta->{'_' . $key});
        } elseif (in_array($key, array_keys($this->variation_inherited_meta_data))) {
            return !is_null($this->meta->{'_' . $key}) || !is_null($this->parent->meta->{'_' . $key});
        } else {
            return !is_null($this->parent->meta->{'_' . $key});
        }
    }

    /**
     * Get method returns variation meta data if set, otherwise in most cases the data from the parent.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, array_keys($this->variation_level_meta_data))) {

            $value = $this->meta->{'_' . $key};

            if ('' === $value) {
                $value = $this->variation_level_meta_data[$key];
            }

        } elseif (in_array($key, array_keys($this->variation_inherited_meta_data))) {

            $value = !is_null($this->meta->{'_' . $key}) ? $this->meta->{'_' . $key} : $this->parent->meta->{'_' . $key};

            // Handle meta data keys which can be empty at variation level to cause inheritance
            if ('' === $value && in_array($key, array('sku', 'weight', 'length', 'width', 'height'))) {
                $value = $this->parent->meta->{'_' . $key};
            }

            if ('' === $value) {
                $value = $this->variation_inherited_meta_data[$key];
            }

        } elseif ('variation_data' === $key) {
            return $this->variation_data = wc_get_product_variation_attributes($this->variation_id);

        } elseif ('variation_has_stock' === $key) {
            return $this->managing_stock();

        } else {
            $value = !is_null($this->meta->{'_' . $key}) ? $this->meta->{'_' . $key} : $this->parent->$key;
        }

        return $value;
    }

}