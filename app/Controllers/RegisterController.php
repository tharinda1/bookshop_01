<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AuthorModel;
use App\Models\PublisherModel;
use CodeIgniter\Controller;
use Ramsey\Uuid\Uuid;

class RegisterController extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('register_view');
    }

    public function processRegistration()
    {
        helper(['form']);
        $session = session();

        // Get the User Provider (UserModel)
        $users = auth()->getProvider();

        $password = $this->request->getPost('password');

        if (empty($password)) {
            $session->setTempdata('error', 'Password was not received.', 3);
            return redirect()->back()->withInput();
        }

        // Create a new user entity
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $password,
        ]);

        // Attempt to save the user
        if ($users->save($user)) {
            // Get the new user's ID
            $user = $users->findById($users->getInsertID());

            // Add user to default group
            $user->addGroup('user');

            // Check for and create author/publisher records
            $isAuthor = $this->request->getPost('is_author');
            if ($isAuthor) {
                $authorModel = new \App\Models\AuthorModel();
                $authorModel->insert([
                    'name'    => $this->request->getPost('author_name'),
                    'user_id' => $user->id,
                ]);
            }

            $isPublisher = $this->request->getPost('is_publisher');
            if ($isPublisher) {
                $publisherModel = new \App\Models\PublisherModel();
                $publisherModel->insert([
                    'name'    => $this->request->getPost('publisher_name'),
                    'user_id' => $user->id,
                ]);
            }
            
            $session->setTempdata('success', 'Registration successful! You can now log in.', 3);
            return redirect()->to('login');

        } else {
            // Get validation errors
            $errors = $users->errors();
            $session->setTempdata('error', 'Registration failed!', 3);
            return redirect()->back()->withInput()->with('errors', $errors);
        }
    }
}