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

        $fileDirectory  = $this->baseUrl.'/product_images'; 
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
        $productPicture = $request->product_image;
        $fileName = '';
        // $fileBin = null;

        // if($productPicture === null) {

        //     $fileName = 'default.png';

        // }else {
        //     $generateName   = uniqid().'_'.time().date('Ymd').'_IMG';
        //     $base64Image    = $productPicture;
        //     $fileBin        = file_get_contents($base64Image);
        //     $mimeType       = mime_content_type($base64Image);

        //     if('image/png' === $mimeType) {
        //         $fileName = $generateName.'.png';
        //     }else if('image/jpg' === $mimeType) {
        //         $fileName = $generateName.'.jpg';
        //     }else if('image/jpeg' === $mimeType) {
        //         $fileName = $generateName.'.jpeg';
        //     }else{
        //         return response()->json([

        //             'success'   => false,
        //             'errors'    => [],
        //             'message'   => 'Only image is accepted'
    
        //         ],400);
        //     }
        // }

        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);

        Product::create([
           'title'      => $request->title,    
           'description'=> $request->description,     
           'price'      => $request->price,
           'image'      => $fileName
        ]);

        // if($productPicture){
        //     file_put_contents('./product_images',$file_name,$fileBin);
        // }

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

        $fileDirectory  = $this->baseUrl.'/product_images'; 

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
        $productPicture = $request->product_image;
        $fileName = '';
        $fileBin = null;

        // if($productPicture === null) {

        //     $fileName = 'default.png';

        // }else {
        //     $generateName   = uniqid().'_'.time().date('Ymd').'_IMG';
        //     $base64Image    = $productPicture;
        //     $fileBin        = file_get_contents($base64Image);
        //     $mimeType       = mime_content_type($base64Image);

        //     if('image/png' === $mimeType) {
        //         $fileName = $generateName.'.png';
        //     }else if('image/jpg' === $mimeType) {
        //         $fileName = $generateName.'.jpg';
        //     }else if('image/jpeg' === $mimeType) {
        //         $fileName = $generateName.'.jpeg';
        //     }else{
        //         return response()->json([

        //             'success'   => false,
        //             'errors'    => [],
        //             'message'   => 'Only image is accepted'
    
        //         ],400);
        //     }
        // }

        $userToken = $request->token;
        $user      = auth('users')->authenticate($userToken);

        $product->update([
           'title'          => $request->title,    
           'description'    => $request->description,     
           'price'          => $request->price,
           'image_'         => $fileName
        ]);

        // if($productPicture){
        //     file_put_contents('./product_images',$file_name,$fileBin);
        // }

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
