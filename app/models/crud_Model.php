<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: Customer_Model
 */
class crud_Model extends Model {
    protected $table = 'customers';
    protected $primaryKey = 'id';   

    protected $fillable = [     

        'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone'     


    ];

    public function __construct()
    {
        parent::__construct();
    }

         public function page($q, $records_per_page = null, $page = null) {
            if (is_null($page)) {
                return $this->db->table($this->table)->get_all();
            } else {
                $query = $this->db->table($this->table);
                
                // Build LIKE conditions
                $query->like('customer_id', '%'.$q.'%')
                    ->or_like('first_name', '%'.$q.'%')
                    ->or_like('last_name', '%'.$q.'%')
                    ->or_like('phone', '%'.$q.'%')
                    ->or_like('email', '%'.$q.'%')
                   ;

                // Clone before pagination
                $countQuery = clone $query;

                $data['total_rows'] = $countQuery->select_count('*', 'count')
                                                ->get()['count'];

                $data['records'] = $query->pagination($records_per_page, $page)
                                        ->get_all();

                return $data;
            }
        }


    
}
