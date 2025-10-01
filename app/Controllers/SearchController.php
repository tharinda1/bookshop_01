<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BooksModel; // <-- Make sure to use your new model
use App\Models\CartModel;
use Config\Services;

class SearchController extends BaseController
{
    public function __construct()
    {
        helper('form');
    }
    public function index()
    {
        $query = $this->request->getGet('q');
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

        if ($query) {
            $booksModel = new BooksModel();
            $results = $booksModel->searchBooks($query); // Call the new model method

            $data = [
                'results' => $results,
                'query'   => $query,
                'cart_books' => $cart_books
            ];
            
            return view('search_results', $data); // Create a view named 'search_results.php'
        } else {
            // Handle cases where no query is provided, e.g., redirect or show a message.
            // return redirect()->to(base_url());
            $data = [
                'results' => [],
                'query' => '',
                'cart_books' => $cart_books
            ];
            return view('search_results', $data);
        }
    }
}
