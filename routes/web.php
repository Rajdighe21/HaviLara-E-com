 <?php

    use App\Http\Controllers\admin\ProductController;
    use App\Http\Controllers\admin\ProductSubcategoryController;
    use Illuminate\Support\Str;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\admin\HomeController;
    use App\Http\Controllers\admin\BrandController;
    use App\Http\Controllers\admin\CategoryController;
    use App\Http\Controllers\admin\AdminLoginController;
    use App\Http\Controllers\admin\TempImagesController;
    use App\Http\Controllers\admin\SubCategoryController;
    use App\Http\Controllers\FrontController;
    use App\Http\Controllers\ShopController;

    /*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



    Route::get('/', [FrontController::class, 'index'])->name('front.home');
    Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');



    Route::group(['prefix' => 'admin'], function () {
        Route::group(['middleware' => 'admin.guest'], function () {
            Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
            Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
        });

        Route::group(['middleware' => 'admin.auth'], function () {
            Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
            Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');


            //Categories Routes
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [CategoryController::class, 'destory'])->name('categories.delete');

            //SubCategory Routes
            Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('subcategories.create');
            Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
            Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
            Route::get('/sub-categories/{subCategories}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
            Route::put('/sub-categories/{subCategories}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
            Route::delete('/sub-categories/{subCategories}', [SubCategoryController::class, 'destory'])->name('sub-categories.delete');

            //Brands Routes
            Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
            Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
            Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
            Route::get('/brands/{brands}/edit', [BrandController::class, 'edit'])->name('brands.edit');
            Route::put('/brands/{brands}', [BrandController::class, 'update'])->name('brands.update');
            Route::delete('/brands/{brands}', [BrandController::class, 'destory'])->name('brands.delete');


            //Products Routes
            Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destory'])->name('products.delete');


            //DropDown
            Route::get('products-sub-category', [ProductSubcategoryController::class, 'index'])->name('products-sub-category.index');

            //Image
            Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');




            //Create Slug
            Route::get('/getSlug', function (Request $request) {
                $slug = '';
                if (!empty($request->title)) {
                    $slug = Str::slug($request->title);
                }
                return response()->json([
                    'status' => true,
                    'slug' => $slug,
                ]);
            })->name('getSlug');
        });
    });
