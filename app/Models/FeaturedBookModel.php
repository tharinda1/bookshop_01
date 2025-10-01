<?php

namespace App\Models;

use CodeIgniter\Model;

class FeaturedBookModel extends Model
{
    protected $table = 'featured_books';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['book_id'];

    public function getFeaturedBooks()
    {
        return $this->select('books.*')
                    ->join('books', 'books.id = featured_books.book_id')
                    ->findAll();
    }
}
