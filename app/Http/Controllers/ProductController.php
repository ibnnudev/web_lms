<?php

namespace App\Http\Controllers;

use App\Interfaces\CourseCategoryInterface;
use App\Interfaces\CourseInterface;
use App\Interfaces\TransactionInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $course;
    private $courseCategory;
    private $transaction;

    public function __construct(CourseInterface $course, CourseCategoryInterface $courseCategory, TransactionInterface $transaction) {
        $this->course = $course;
        $this->courseCategory = $courseCategory;
        $this->transaction = $transaction;
    }

    public function index()
    {
        return view('product', [
            'products' => $this->course->getAll(),
            'categories' => $this->courseCategory->getAll()
        ]);
    }

    public function show($id)
    {
        $course = $this->course->getById($id);
        $techSpecs = $course->courseTechSpec;
        $benefits = $course->courseBenefit;
        $courseObjectives = $course->courseObjective;
        $authors = $course->author;
        $isBought = $course->isBought;

        return view('detail-product', compact('course', 'techSpecs', 'benefits', 'courseObjectives', 'authors', 'isBought'));
    }

    public function addToCart(Request $request)
    {
        $product = $this->course->getById($request->id);
        
        // add cart session
        $cart = session()->get('cart');
        if(!$cart) {
            $cart = [
                $request->id => [
                    "id" => $request->id,
                    "name" => $product->title,
                    "price" => $product->price,
                    "rent_month" => auth()->user()->role == 2 ? 3 : 1,
                    "main_image" => $product->main_image,
                    "total_price" => $product->price * (auth()->user()->role == 2 ? 3 : 1),
                ]
            ];
            session()->put('cart', $cart);
        } else {
            if(isset($cart[$request->id])) {
                $cart[$request->id]['rent_month'] = auth()->user()->role == 2 ? 3 : 1;
                $cart[$request->id]['total_price'] = $product->price * (auth()->user()->role == 2 ? 3 : 1);
                session()->put('cart', $cart);
            } else {
                $cart[$request->id] = [
                    "id" => $request->id,
                    "name" => $product->title,
                    "price" => $product->price,
                    "rent_month" => auth()->user()->role == 2 ? 3 : 1,
                    "main_image" => $product->main_image,
                    "total_price" => $product->price * (auth()->user()->role == 2 ? 3 : 1),
                ];
                session()->put('cart', $cart);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully'
        ]);
    }

    public function deleteCart(Request $request)
    {
        // delete cart by id
        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product deleted from cart successfully'
        ]);
    }

    public function getAllCart()
    {
        return view('cart', [
            'carts' => session()->get('cart')
        ]);
    }
}
