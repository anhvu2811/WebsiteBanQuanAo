<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use App\Models\ProductSize;
use App\Models\ProductImage;
use App\Models\Trending;
use App\Models\Setting;
use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search', '');
        $sortPrice = $request->get('sortPrice');
        $sortName = $request->get('sortName');
        
        $productsQuery = Product::where('name', 'like', "%$search%")->with('images');

        if ($sortName) {
            $productsQuery->orderBy('name', $sortName);
        }
        if ($sortPrice) {
            $productsQuery->orderBy('price', $sortPrice);
        }
        $products = $productsQuery->paginate($perPage);

        return view('page.admin.product.index', compact('products'));
    }
    
    public function create()
    {
        $category = Category::all();
        return view('page.admin.product.create', compact('category'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $product = new Product();
        $product->name         = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->material    = $validated['material'] ?? null;
        $product->price       = $validated['price'];
        $product->gender      = $validated['gender'];
        $product->category_id = $validated['category_id'];
        $product->save();

        foreach($request->sizes as $sizeData) {
            $size = Size::firstOrCreate(
                ['name' => $sizeData['size_name']]
            ); 

            ProductSize::create([
                'product_id'     => $product->id,
                'size_id'        => $size->id,
                'stock_quantity' => $sizeData['stock_quantity']
            ]);
        }
        foreach($request->images as $index => $imageData) {
            if (isset($imageData['file'])) { 
                $randomNumber = mt_rand(1000000000000000, 9999999999999999);
                $extension = $imageData['file']->getClientOriginalExtension();
                $fileName = $randomNumber . '.' . $extension;
                $imagePath = $imageData['file']->storeAs('product', $fileName, 'public');

                $isMainImage = ($request->main_image == $index);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $imagePath,
                    'image_type' => 0,
                    'is_main'    => $isMainImage ? 1 : 0,
                ]);
            }
        }
        return redirect()->route('product.index');
    }

    public function edit(string $id)
    {
        $category = Category::all();
        $product = Product::findOrFail($id)->load('category');
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $productSizes = ProductSize::where('product_id', $product->id)->get();
        return view('page.admin.product.update', compact('product', 'category', 'productImages', 'productSizes'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->name         = $request->input('name');
        $product->description  = $request->input('description');
        $product->material     = $request->input('material');
        $product->price        = $request->input('price');
        $product->category_id  = $request->input('category_id');
        $product->save();
        
        $imageProduct = ProductImage::where('product_id', $product->id)->get();
        $listImageIds = $imageProduct->pluck('id')->toArray();
        $index = $request->main_image;
        if (isset($listImageIds[$index])) {
            $imageIdToUpdate = $listImageIds[$index];
            ProductImage::where('product_id', $product->id)->update(['is_main' => 0]);
            ProductImage::where('id', $imageIdToUpdate)->update(['is_main' => 1]);
        }

        foreach($request->sizes as $sizeData) {
            $size = Size::firstOrCreate(
                ['name' => $sizeData['size_name']]
            ); 

            ProductSize::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'size_id' => $size->id
                ],
                [
                    'stock_quantity' => $sizeData['stock_quantity']
                ]
            );
        }
        if ($request->has('images')) {
            foreach ($request->images as $index => $imageData) {
                if (isset($imageData['file'])) { 
                    $randomNumber = mt_rand(1000000000000000, 9999999999999999);
                    $extension = $imageData['file']->getClientOriginalExtension();
                    $fileName = $randomNumber . '.' . $extension;
                    $imagePath = $imageData['file']->storeAs('product', $fileName, 'public');
                    $isMainImage = ($request->main_image == $index); 
                    $productImage = ProductImage::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'image_url'  => $fileName, 
                        ],
                        [
                            'image_url'  => $imagePath,
                            'image_type' => 0,
                            'is_main'    => $isMainImage ? 1 : 0,
                        ]
                    );
                }
            }
        }
    
        return redirect()->route('product.index');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index');
    }


    public function collections(Request $request) 
    {
        return view('page.user.collections');
    }
    
    public function getHotTrendProducts()
    {
        $hotTrendProducts = Trending::with('product')
                                ->orderBy('rank', 'asc') 
                                ->take(7) 
                                ->get();

        $listLatestProducts = Product::orderBy('created_at', 'desc')
                                ->take(7) 
                                ->get();

        $bestSellingProducts = Product::select('tbl_order_item.product_id', DB::raw('count(*) as total'))
                                        ->join('tbl_order_item', 'tbl_order_item.product_id', '=', 'tbl_product.id')
                                        ->join('tbl_order', 'tbl_order.id', '=', 'tbl_order_item.order_id')
                                        ->where('tbl_order.payment_status', 'Completed')
                                        ->groupBy('tbl_order_item.product_id')
                                        ->orderByDesc('total')
                                        ->limit(7)
                                        ->get()
                                        ->pluck('product_id');
        $bestSellingProducts = Product::whereIn('id', $bestSellingProducts)->get();
                
        $bannerMain = Banner::where('type', 'main')->get();
        $bannerSub = Banner::where('type', 'sub')->get();

        return view('page.user.index', compact('hotTrendProducts', 'listLatestProducts', 'bestSellingProducts', 'bannerMain', 'bannerSub'));
    }

    public function getProductDetail($id)
    {
        $product = Product::find($id);
        $getRelatedProducts = Product::where('category_id', '=', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->get();
        return view('page.user.product_detail', compact('product', 'getRelatedProducts'));
    }

    public function deleteProductImage($id)
    {
        try {
            $image = ProductImage::find($id);
            if ($image) {
                if (Storage::exists('public/' . $image->image_url)) {
                    Storage::delete('public/' . $image->image_url);
                }
                $image->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function updateProductImage($id, Request $request)
    {
        try {
            $image = ProductImage::find($id);

            if (!$image) {
                return response()->json(['success' => false, 'message' => 'Image not found']);
            }
            
            if (Storage::exists('public/' . $image->image_url)) {
                Storage::delete('public/' . $image->image_url);
            }

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $randomNumber = mt_rand(1000000000000000, 9999999999999999);
                $extension = $imageFile->getClientOriginalExtension();
                $fileName = $randomNumber . '.' . $extension;

                $imagePath = $imageFile->storeAs('product', $fileName, 'public');
                if (!$imagePath) {
                    return response()->json(['success' => false, 'message' => 'Failed to store new image']);
                }

                $image->image_url = $imagePath;
                $image->save();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function showSizes($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $sizes = [];
        foreach ($product->productSize as $productSize) {
            if ($productSize->size) {
                $sizes[] = [
                    'product_id' => $productSize->product_id,
                    'size_name' => $productSize->size->name,
                    'stock_quantity' => $productSize->stock_quantity,
                ];
            }
        }
        if (empty($sizes)) {
            return response()->json(['message' => 'No sizes found for this product'], 404);
        }
        return response()->json([
            'sizes' => $sizes
        ]);
    }

    public function checkSizeQuanity($productId, $sizeId)
    {
        $productSize = ProductSize::where('product_id', $productId)->where('size_id', $sizeId)->first();
        if(!$productSize) {
            return null;
        }
        return response()->json([
            'quantity' => $productSize->stock_quantity
        ]);
    }

    // API get catogories
    public function getCategories(Request $request)
    {
        $maleCate = Category::join('tbl_product', 'tbl_category.id', '=', 'tbl_product.category_id')
                            ->select('tbl_category.id', 'tbl_category.name', \DB::raw('COUNT(tbl_product.id) as product_count'))
                            ->where('tbl_product.gender', Product::GENDER_MALE)
                            ->groupBy('tbl_category.id', 'tbl_category.name')
                            ->get();

        $famaleCate = Category::join('tbl_product', 'tbl_category.id', '=', 'tbl_product.category_id')
                            ->select('tbl_category.id', 'tbl_category.name', \DB::raw('COUNT(tbl_product.id) as product_count'))
                            ->where('tbl_product.gender', Product::GENDER_FAMALE)
                            ->groupBy('tbl_category.id', 'tbl_category.name')
                            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'maleCate' => $maleCate,
                'famaleCate' => $famaleCate
            ],
        ]);
    }

    // API info setting for header
    public function setting() 
    {
        $setting = Setting::first();
        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    // API get product for collection
    public function getProducts(Request $request)
    {
        $query =  Product::with(['category', 'discount', 'images']);
        $limit = $request->get('limit', 10);
        if($request->has('search')) {
            $keyword = $request->search;
            $query->where('name', 'like', "%{$keyword}%");
        }
        if($request->has('category')) {
            $query->where('category_id', '=', $request->category);
        }
        if($request->has('gender')) {
            $query->where('gender', '=', $request->gender);
        }
        if($request->has('sort')) {
            $sort = $request->get('sort');
            switch($sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }
        }
        $products = $query->paginate($limit);
        
        return response()->json([
            'success' => true,
            'data' => $products,
            'total' => $products->total(),
            'next_page_url' => $products->nextPageUrl()
        ]);
    }

}
