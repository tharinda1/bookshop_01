<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'guest_cart_id', 'book_id', 'quantity'];

    // We will manage timestamps manually or via DB default, so we set this to false.
    protected $useTimestamps = false;

    /**
     * Adds or updates an item in the cart for a logged-in user.
     */
    public function addItemForUser(int $userId, int $bookId, int $quantity)
    {
        $existingItem = $this->where('user_id', $userId)
                             ->where('book_id', $bookId)
                             ->first();

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->update($existingItem['id'], ['quantity' => $newQuantity]);
            log_message('debug', 'CartModel::addItemForUser - Updated item ID ' . $existingItem['id'] . ' for user ' . $userId . ', book ' . $bookId . ', new quantity ' . $newQuantity);
        } else {
            $this->insert([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity,
            ]);
            log_message('debug', 'CartModel::addItemForUser - Inserted new item for user ' . $userId . ', book ' . $bookId . ', quantity ' . $quantity);
        }
    }

    /**
     * Adds or updates an item in the cart for a guest.
     */
    public function addItemForGuest(string $guestCartId, int $bookId, int $quantity)
    {
        $existingItem = $this->where('guest_cart_id', $guestCartId)
                             ->where('book_id', $bookId)
                             ->first();

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $this->update($existingItem['id'], ['quantity' => $newQuantity]);
            log_message('debug', 'CartModel::addItemForGuest - Updated item ID ' . $existingItem['id'] . ' for guest ' . $guestCartId . ', book ' . $bookId . ', new quantity ' . $newQuantity);
        } else {
            $this->insert([
                'guest_cart_id' => $guestCartId,
                'book_id' => $bookId,
                'quantity' => $quantity,
            ]);
            log_message('debug', 'CartModel::addItemForGuest - Inserted new item for guest ' . $guestCartId . ', book ' . $bookId . ', quantity ' . $quantity);
        }
    }

    /**
     * Merges a guest's cart into a user's cart upon login.
     */
    public function mergeGuestCart(string $guestCartId, int $userId)
    {
        $guestItems = $this->where('guest_cart_id', $guestCartId)->findAll();

        if (empty($guestItems)) {
            log_message('debug', 'CartModel::mergeGuestCart - No guest items to merge for guestCartId: ' . $guestCartId);
            return; // Nothing to merge
        }

        log_message('debug', 'CartModel::mergeGuestCart - Merging ' . count($guestItems) . ' guest items from ' . $guestCartId . ' to user ' . $userId);
        foreach ($guestItems as $item) {
            // This re-uses the logic to handle quantity updates if the user already had the same book in their cart.
            $this->addItemForUser($userId, $item['book_id'], $item['quantity']);
        }

        // After merging, delete the old guest cart items
        $this->where('guest_cart_id', $guestCartId)->delete();
        log_message('debug', 'CartModel::mergeGuestCart - Deleted guest cart items for guestCartId: ' . $guestCartId);
    }

    /**
     * Retrieves cart items for a logged-in user, including book details.
     */
    public function getCartItemsForUser(int $userId)
    {
        $items = $this->select('cart_items.*, books.title, books.price')
                    ->join('books', 'books.id = cart_items.book_id')
                    ->where('user_id', $userId)
                    ->findAll();
        log_message('debug', 'CartModel::getCartItemsForUser - Retrieved ' . count($items) . ' items for user ' . $userId);
        return $items;
    }

    /**
     * Retrieves cart items for a guest, including book details.
     */
    public function getCartItemsForGuest(string $guestCartId)
    {
        $items = $this->select('cart_items.*, books.title, books.price')
                    ->join('books', 'books.id = cart_items.book_id')
                    ->where('guest_cart_id', $guestCartId)
                    ->findAll();
        log_message('debug', 'CartModel::getCartItemsForGuest - Retrieved ' . count($items) . ' items for guest ' . $guestCartId);
        return $items;
    }

    /**
     * Removes an item from the cart.
     */
    public function removeItem(int $cartItemId)
    {
        log_message('debug', 'CartModel::removeItem - Removing item ID ' . $cartItemId);
        return $this->where('id', $cartItemId)->delete();
    }

    /**
     * Clears all cart items for a given user.
     */
    public function clearCart(int $userId)
    {
        log_message('debug', 'CartModel::clearCart - Clearing cart for user ID ' . $userId);
        return $this->where('user_id', $userId)->delete();
    }

    /**
     * Updates the quantity of an item in the cart.
     */
    public function updateItemQuantity(int $cartItemId, int $quantity)
    {
        log_message('debug', 'CartModel::updateItemQuantity - Updating item ID ' . $cartItemId . ' to quantity ' . $quantity);
        
        if ($quantity > 0) {
            return $this->update($cartItemId, ['quantity' => $quantity]);
        } else {
            // If quantity is 0 or less, remove the item
            return $this->removeItem($cartItemId);
        }
    }
}

