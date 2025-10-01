<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuthorModel;

class AddAuthor extends BaseController
{
    public $AuthorModel;
    public function __construct()
    {
        helper('form');
        $this->AuthorModel = new AuthorModel();
    }
    public function index()
    {
        $data = [];

        $session = \Config\Services::session();

        if ($this->request->is('post'))
        {
            $rules = [
                'name' => 'required|min_length[3]|max_length[50]',
            ];
            if ($this->validate($rules))
            {
                $cdata = [
                    'name' => $this->request->getVar('name'),
                ];

                // Ready to save data to the database
                // For now, just show a success message or redirect
               
                //data inserted to database? (status)
                $status = $this->AuthorModel->saveData($cdata);

                if($status)
                {
                    $session->setTempdata('success', 'Data Inserted Successfully!', 3);
                    return redirect()->to(current_url());
                }
                {
                    $session->setTempdata('error', 'Sorry! Error in Inserting Data', 3);
                    return redirect()->to(current_url());
                }
            }
            else
            {
                $data['validation'] = $this->validator;
            }
        
            $data = [
                'page_title' => 'Add Authors',
                'page_heading' => 'Add Authors',
            ];
            
        }
        // Load the AuthorModel
        $authorModel = new AuthorModel();
        
        // Fetch all authors from the database
        $data['authors'] = $authorModel->findAll();
        
        // Pass the authors data to the view
        return view('add_authors',$data);
    }

    public function showAllAuthors()
    {
       // Load the AuthorModel
        $authorModel = new AuthorModel();
        
        // Fetch all authors from the database
        $data['authors'] = $authorModel->findAll();
        
        // Pass the authors data to the view
        return view('add_authors', $data);
    }

    public function delete($id)
    {
       $authorModel = new AuthorModel();
       // Perform the soft delete
        $authorModel->delete($id);

        // Redirect back to the authors list with a success message
        return redirect()->to(base_url('add_authors'))->with('message', 'Author deleted successfully.');
    }

    public function update_inline($id)
    {
        // Check if the request is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Invalid Request']);
        }

        helper(['form']);

        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
        ];

        // Validate the incoming data
        if (!$this->validate($rules)) {
            $response = [
                'status' => 'error',
                'message' => 'Validation failed: ' . $this->validator->listErrors()
            ];
            return $this->response->setJSON($response);
        }

        $model = new AuthorModel();
        $data = [
            'name' => $this->request->getPost('name'),
        ];
        
        // Update the author in the database
        $model->update($id, $data);
        
        // Send a successful JSON response back to the JavaScript
        $response = [
            'status' => 'success',
            'message' => 'Author updated successfully!'
        ];
        return $this->response->setJSON($response);
    }
}