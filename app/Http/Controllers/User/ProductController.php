<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Routing\UrlGenerator;
use App\Product;

class ProductController extends Controller
{
    protected $baseUrl;
    public function __construct(UrlGenerator $urlGenerator){

        $this->middleware('auth:users');
        $this->baseUrl = $urlGenerator->to('/');

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProducts($token)
    {

        $fileDirectory  = $this->baseUrl.'/images/products'; 
        $user           = auth('users')->authenticate($token);

        $products       = Product::orderBy('id','DESC')->get();
        
        return response()->json([
            'success'        =>  true,
            'products'       =>  $products,
            'file_directory' =>  $fileDirectory
        ],200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'token'         => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray()

            ],400);
        }

        //image validation
        $image_name_to_store = '';

        $image = $request->file('image');
        if ($image) {
            //full image name with extension
            $image_name_with_ext = $image->getClientOriginalName();

            //image name without extension
            $image_name_without_ext = pathinfo($image_name_with_ext, PATHINFO_FILENAME);

            //get extension of that image
            $image_ext = $image->getClientOriginalExtension();

            //image name with encription
            $image_name_to_store = $image_name_without_ext . '_' . time() . '_.' . $image_ext;

            //public folder
            $path = public_path('/images/products');
            $image->move($path, $image_name_to_store);
        }


        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);

        Product::create([
           'title'      => $request->title,    
           'description'=> $request->description,     
           'price'      => $request->price,
           'image'      => $image_name_to_store
        ]);


        return response()->json([

            'success'   => true,
            'message'   => 'Product Saved Successfully!'

        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$token)
    {

        $product = Product::find($id);
        if(!$product) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Product!'
            ],400);

        }

        $fileDirectory  = $this->baseUrl.'/images/products'; 

        return response()->json([
            'success'   => true,
            'errors'    => [],
            'message'   => '',
            'product'   => $product,
            'file_directory' =>  $fileDirectory
        ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[

            'token'         => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray()

            ],400);
        }

        $product = Product::find($id);
        if(!$product) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Product!'
            ],400);

        }

        //image validation
        $image_name_to_store = '';

        $image = $request->file('image');
        if ($image) {
            //full image name with extension
            $image_name_with_ext = $image->getClientOriginalName();

            //image name without extension
            $image_name_without_ext = pathinfo($image_name_with_ext, PATHINFO_FILENAME);

            //get extension of that image
            $image_ext = $image->getClientOriginalExtension();

            //image name with encription
            $image_name_to_store = $image_name_without_ext . '_' . time() . '_.' . $image_ext;

            //public folder
            $path = public_path('/images/products');
            $image->move($path, $image_name_to_store);
        }

        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);

        $product->update([
           'title'          => $request->title,    
           'description'    => $request->description,     
           'price'          => $request->price,
           'image'          => $image_name_to_store
        ]);

        return response()->json([

            'success'   => true,
            'message'   => 'Product Updated Successfully!'

        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(!$product) {

            return response()->json([
                'success'   => false,
                'errors'    => [],
                'message'   => 'Not Valid Product!'
            ],400);

        }

        $getFile = $product->image;

        if($product->delete()){

            if (!empty($getFile) && $getFile !== 'default.png') {
                unlink('./product_images/'.$getFile);
            }

            return response()->json([
                'success'   => true,
                'message'   => 'Product Deleted Successfully!'
            ],200);

        }

    }
}
