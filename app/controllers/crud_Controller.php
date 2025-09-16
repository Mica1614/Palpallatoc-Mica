<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: Customer
 */
class Crud_Controller extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('crud_Model');
        $this->call->library('form_validation');
    }

      public function read() 
    {
        
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 3;

        $all = $this->crud_Model->page($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => '⏮ First',
            'last_link'      => 'Last ⏭',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('bootstrap'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page, site_url('/').'?q='.$q);
        $data['page'] = $this->pagination->paginate();
        $this->call->view('index', $data);
    }

    public function createCustomer() {
        $this->form_validation
            ->name('customer_id')
                ->required()
                ->max_length(50)
            ->name('first_name')
                ->required()
                ->max_length(200)
            ->name('last_name')
                ->required()
                ->max_length(200)
            ->name('email')
                ->required()
                ->max_length(150)
            ->name('phone')
                ->required()
                ->max_length(15);

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->get_errors();
            setErrors($errors);
            redirect('/');
        } else {
            $this->crud_Model->insert([
                'customer_id' => $_POST['customer_id'],
                'first_name'  => $_POST['first_name'],
                'last_name'   => $_POST['last_name'],
                'email'       => $_POST['email'],
                'phone'       => $_POST['phone']
            ]);

            setMessage('success', 'Customer registered successfully!');
            redirect('/');
        }
    }

    public function updateCustomer($id) {
        $this->crud_Model->update($id, [
            'customer_id' => $_POST['customer_id'],
            'first_name'  => $_POST['first_name'],
            'last_name'   => $_POST['last_name'],
            'email'       => $_POST['email'],
            'phone'       => $_POST['phone'],
        ]);
        setMessage('success', 'Customer updated successfully!');
        redirect('/');
    }

    public function deleteCustomer($id) {
        $this->crud_Model->delete($id);
        setMessage('danger', 'Customer deleted successfully!');
        redirect('/');
    }
}
