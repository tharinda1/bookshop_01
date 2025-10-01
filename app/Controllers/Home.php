<?php

namespace App\Controllers;
use App\Models\FeaturedBookModel;
use App\Models\CartModel;
use Config\Services;

class Home extends BaseController
{
    public function __construct()
    {
        // Load the form helper in the constructor
        helper('form');
    }
    public function index(): string
    {
        $featured_book_model = new FeaturedBookModel();
        $cart_model = new CartModel();
        $request = Services::request();

        $user_id = auth()->id();
        $guest_cart_id = $request->getCookie('guest_cart_id');

        $cart_items = [];
        if ($user_id) {
            $cart_items = $cart_model->getCartItemsForUser($user_id);
        } elseif ($guest_cart_id) {
            $cart_items = $cart_model->getCartItemsForGuest($guest_cart_id);
        }

        $cart_books = [];
        foreach ($cart_items as $item) {
            $cart_books[$item['book_id']] = $item['quantity'];
        }

        $data = [
            'page_title' => 'Welcome to Bookshop',
            'page_heading' => 'Let\'s Read',
            'featured_books' => $featured_book_model->getFeaturedBooks(),
            'cart_books' => $cart_books,
        ];
        return view('homeview',$data);
    }
}
