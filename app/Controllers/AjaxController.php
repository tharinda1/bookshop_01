<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BooksModel;

class AjaxController extends BaseController
{
     public function index()
    {
        return view('ajax_example');
    }

    public function getData()
    {
        // Check if the request is an AJAX request
        // Although not strictly necessary, it's good practice
        if ($this->request->isAJAX()) {
            $data = [
                'message' => 'Hello from the CodeIgniter server!'
            ];
            
            // Return a JSON response
            return $this->response->setJSON($data);
        }
        
        // Handle non-AJAX requests if needed, e.g., show an error or redirect
        return redirect()->to('/');
    }

    public function suggestions()
    {
        if ($this->request->isAJAX()) {
            $query = $this->request->getGet('q');
            $booksModel = new BooksModel();
            $suggestions = $booksModel->searchBooks($query);

            $output = '';
            if (!empty($suggestions)) {
                $output .= '<ul class="list-group">';
                foreach ($suggestions as $suggestion) {
                    $output .= '<li class="list-group-item" data-title="' . esc($suggestion->title) . '"><a href="#">' . esc($suggestion->title) . ' by ' . '<i>' . esc($suggestion->author) . '</i>' . '</a></li>';
                }
                $output .= '</ul>';
            }
            return $this->response->setJSON(['suggestions' => $output]);
        }
        return redirect()->to('/');
    }

    
}