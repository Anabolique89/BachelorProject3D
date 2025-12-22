<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'image_mime',
        'image_size',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
        'is_featured',
        'token_reward_on_purchase',
        'category_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'stock_quantity' => 'integer',
        'token_reward_on_purchase' => 'integer',
        'image_size' => 'integer',
    ];

   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('title') && empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

  
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * created by user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Product updated by user
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * deleted by user
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     *  many order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     *  many cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

   
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

   
    public function getIsOnSaleAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

   
    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0 && $this->status === 'published';
    }

   
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

   
   /**
 * Get full image URL
 */
public function getImageUrlAttribute()
{
    if (!$this->image) {
        return null;
    }

    // If image is already a full URL (external)
    if (str_starts_with($this->image, 'http')) {
        return $this->image;
    }

    // Otherwise, it's a local file - prepend storage URL
    return asset('storage/' . $this->image);
}


    /**
     * published products
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * in-stock 
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0)
                     ->where('status', 'published');
    }

    /**
     *  featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * sale
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')
                     ->whereColumn('sale_price', '<', 'price');
    }

    /**
     * products by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    
}