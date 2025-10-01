<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\BooksModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use Config\Services;

class CartController extends BaseController
{
    public function addItem()
    {
        $cartModel = model(CartModel::class);
        $request = Services::request();
        $response = Services::response();

        $json = $this->request->getJSON();
        $bookId = (int)($json->book_id ?? 0);
        $quantity = (int)($json->quantity ?? 1);

        log_message('debug', 'CartController::addItem - bookId: ' . $bookId . ', quantity: ' . $quantity);

        // Assumes CodeIgniter Shield for auth: auth()->id()
        $userId = auth()->id();
        $guestCartId = $request->getCookie('guest_cart_id');

        log_message('debug', 'CartController::addItem - userId: ' . ($userId ?? 'null') . ', guestCartId (before logic): ' . ($guestCartId ?? 'null'));

        if ($userId) {
            // User is logged in. Check for a guest cart to merge.
            if ($guestCartId) {
                $cartModel->mergeGuestCart($guestCartId, $userId);
                // Invalidate the guest cookie after merging
                $response->deleteCookie('guest_cart_id');
                log_message('debug', 'CartController::addItem - Merged guest cart ' . $guestCartId . ' for user ' . $userId);
            }
            $cartModel->addItemForUser($userId, $bookId, $quantity);
            log_message('debug', 'CartController::addItem - Added item for user ' . $userId);
        } else {
            // This is a guest.
            if (!$guestCartId) {
                // No guest cart ID, so create a new secure one.
                $guestCartId = bin2hex(random_bytes(32));
                log_message('debug', 'CartController::addItem - Generated new guestCartId: ' . $guestCartId);
            }
            $cartModel->addItemForGuest($guestCartId, $bookId, $quantity);
            log_message('debug', 'CartController::addItem - Added item for guest ' . $guestCartId);
            
            // Set/update the cookie. The response object must be returned for this to work.
            $response->setCookie('guest_cart_id', $guestCartId, 30 * 24 * 3600, '', '/', '', false, true); // 30 days, path '/', no prefix, not secure, HttpOnly
            log_message('debug', 'CartController::addItem - Set guest_cart_id cookie: ' . $guestCartId);
        }

        return $response->setJSON(['status' => 'success', 'message' => 'Item added to cart.']);
    }

    public function viewCart()
    {
        helper('form'); // Load the form helper

        $cartModel = model(CartModel::class);
        $request = Services::request();

        $userId = auth()->id();
        $guestCartId = $request->getCookie('guest_cart_id');

        log_message('debug', 'CartController::viewCart - userId: ' . ($userId ?? 'null') . ', guestCartId: ' . ($guestCartId ?? 'null'));

        $cartItems = [];
        if ($userId) {
            $cartItems = $cartModel->getCartItemsForUser($userId);
            log_message('debug', 'CartController::viewCart - Retrieved items for user. Count: ' . count($cartItems));
        } elseif ($guestCartId) {
            $cartItems = $cartModel->getCartItemsForGuest($guestCartId);
            log_message('debug', 'CartController::viewCart - Retrieved items for guest. Count: ' . count($cartItems));
        }

        $data['cartItems'] = $cartItems;
        return view('cart_view', $data);
    }

    public function removeItem($cartItemId)
    {
        $cartModel = model(CartModel::class);
        $cartModel->removeItem($cartItemId);

        return redirect()->to('/cart');
    }

    public function updateItem()
    {
        $cartModel = model(CartModel::class);
        $request = Services::request();
        $response = Services::response();

        $json = $this->request->getJSON();
        $cartItemId = (int)($json->cart_item_id ?? 0);
        $quantity = (int)($json->quantity ?? 1);

        log_message('debug', 'CartController::updateItem - cartItemId: ' . $cartItemId . ', quantity: ' . $quantity);

        // Basic validation
        if ($cartItemId <= 0 || $quantity <= 0) {
            return $response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid data provided.']);
        }

        // Here you should also verify that the item belongs to the current user/guest to prevent unauthorized updates.
        // This is a simplified example.
        
        $result = $cartModel->updateItemQuantity($cartItemId, $quantity);

        if ($result) {
            return $response->setJSON(['status' => 'success', 'message' => 'Cart updated.']);
        } else {
            return $response->setStatusCode(500)->setJSON(['status' => 'error', 'message' => 'Failed to update cart.']);
        }
    }

    public function checkout()
    {
        $userId = auth()->id();

        if (!$userId) {
            session()->setTempdata('error', 'Please log in or sign up to proceed with checkout.', 3);
            return redirect()->to('login');
        }

        $cartModel = model(CartModel::class);
        $orderModel = model(OrderModel::class);
        $orderItemModel = model(OrderItemModel::class);

        $cartItems = $cartModel->getCartItemsForUser($userId);

        if (empty($cartItems)) {
            session()->setTempdata('error', 'Your cart is empty.', 3);
            return redirect()->to('/cart');
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        // Create a new order
        $orderId = $orderModel->insert([
            'user_id' => $userId,
            'total_amount' => $totalAmount,
            'status' => 'pending', // Or 'completed' if payment is processed immediately
        ]);

        if (!$orderId) {
            session()->setTempdata('error', 'Failed to create order.', 3);
            return redirect()->to('/cart');
        }

        // Add items to the order
        foreach ($cartItems as $item) {
            $orderItemModel->insert([
                'order_id' => $orderId,
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity'],
                'price_at_purchase' => $item['price'],
            ]);
        }

        // Clear the user's cart
        $cartModel->clearCart($userId);

        session()->setTempdata('success', 'Checkout successful! Your order has been placed.', 3);
        return redirect()->to('/cart'); // Redirect to cart page

    }
}
