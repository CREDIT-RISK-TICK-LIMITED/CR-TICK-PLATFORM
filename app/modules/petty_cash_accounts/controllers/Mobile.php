<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile extends Mobile_Controller{

  function __construct(){
        parent::__construct();
        $this->load->model('petty_cash_accounts_m');
    }

    protected $validation_rules = array(
        array(
                'field' =>  'account_name',
                'label' =>  'Account Name',
                'rules' =>  'xss_clean|trim'
            ),
        array(
                'field' =>  'account_slug',
                'label' =>  'Account Name',
                'rules' =>  'required|xss_clean|trim|callback__is_unique_account_name'
            ),
        array(
                'field' =>  'initial_balance',
                'label' =>  'Initial Petty Cash Account balances',
                'rules' =>  'xss_clean|trim|currency'
            ),

    );

    public function _remap($method, $params = array()){
        if(method_exists($this, $method)){
           return call_user_func_array(array($this, $method), $params);
        }
        $this->output->set_status_header('404');
        header('Content-Type: application/json');
        $file = file_get_contents('php://input')?(array)json_decode(file_get_contents('php://input')):array();
        $request = $_REQUEST+$file;
        echo encrypt_json_encode(
        array(
            'response' => array(
                'status'    =>  404,
                'message'       =>  '404 Method Not Found for URI: '.$this->uri->uri_string(),
            ))
        );
    }

    function _is_unique_account_name(){
        $account_slug = $this->input->post('account_slug');
        $id = $this->input->post('id');
        $group_id = $this->input->post('group_id');
        if($this->petty_cash_accounts_m->check_if_account_exists($id,$account_slug,$group_id))
        {
            $this->form_validation->set_message('_is_unique_account_name','The Petty Cash Account Name should be unique');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function create(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $_POST['account_slug'] = generate_slug($this->input->post('account_name'));
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    $this->form_validation->set_rules($this->validation_rules);
                    if($this->form_validation->run()){
                        $id = $this->petty_cash_accounts_m->insert(array(
                            'account_name'      =>  $this->input->post('account_name'),
                            'account_slug'      =>  $this->input->post('account_slug'),
                            'initial_balance'   =>  $this->input->post('initial_balance'),
                            'created_by'        =>  $this->user->id,
                            'created_on'        =>  time(),
                            'group_id'          =>  $this->group->id,
                            'active'            =>  1,
                        ));
                        if($id){
                            $response = array(
                                'status' => 1,
                                'message' => 'success',
                                'id' => $id,
                                'name' => $this->input->post('account_name'),
                            );
                        }else{
                            $response = array(
                                'status' => 0,
                                'message' => 'Failed to complete petty cash registration',
                            );
                        }
                    }else{
                        $post = array();
                        $form_errors = $this->form_validation->error_array();
                        foreach ($form_errors as $key => $value) {
                            $post[$key] = $value;
                        }
                        $response = array(
                            'status' => 0,
                            'time' => time(),
                            'message' => 'Form validation failed',
                            'validation_errors' => $post,
                        );
                    }
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));
    }

    function edit(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $_POST['account_slug'] = generate_slug($this->input->post('account_name'));
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    $id = $this->input->post('id');
                    if($post = $this->petty_cash_accounts_m->get_group_petty_cash_account($id,$this->group->id)){
                        $this->form_validation->set_rules($this->validation_rules);
                        if($this->form_validation->run()){
                            $id = $this->petty_cash_accounts_m->update($post->id,array(
                                'account_name'      =>  $this->input->post('account_name'),
                                'account_slug'      =>  $this->input->post('account_slug'),
                                'initial_balance'   =>  $this->input->post('initial_balance'),
                                'modified_by'        =>  $this->user->id,
                                'modified_on'        =>  time(),
                            ));
                            if($id){
                                $response = array(
                                    'status' => 1,
                                    'message' => 'success',
                                    'id' => $post->id,
                                    'name' => $this->input->post('account_name'),
                                );
                            }else{
                                $response = array(
                                    'status' => 0,
                                    'message' => 'Failed to complete petty cash registration',
                                );
                            }
                        }else{
                            $post = array();
                            $form_errors = $this->form_validation->error_array();
                            foreach ($form_errors as $key => $value) {
                                $post[$key] = $value;
                            }
                            $response = array(
                                'status' => 0,
                                'time' => time(),
                                'message' => 'Form validation failed',
                                'validation_errors' => $post,
                            );
                        }
                    }else{
                        $response = array(
                            'status' => 0,
                            'message' => 'could not find group petty cash account',
                        );
                    }
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));
    }

    function get_group_petty_cash_accounts_list(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    $posts = $this->petty_cash_accounts_m->get_all($this->group->id);
                    $petty_accounts=array();
                    foreach ($posts as $post) {
                        $petty_accounts[] = array(
                            'id' => $post->id,
                            'account_name' => $post->account_name,
                            'account_balance' => $post->initial_balance + $post->current_balance,
                            'is_closed' => $post->is_closed?1:0,
                        );
                    }
                    $response = array(
                        'status' => 1,
                        'message' => 'successful',
                        'petty_accounts' => $petty_accounts,
                    );
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));        
    }

    function close(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    if($this->member->group_role_id || $this->member->is_admin){
                        $id = $this->input->post('id');
                        if($post = $this->petty_cash_accounts_m->get_group_petty_cash_account($id,$this->group->id)){
                            if($post->is_closed){
                                $response = array(
                                    'status' => 0,
                                    'message' => 'Sorry, the petty cash account is already closed',
                                );
                            }
                            $update = array(
                                'is_closed'=>1,
                                'modified_by'=>$this->user->id,
                                'modified_on'=>time()
                            );
                            if($res = $this->petty_cash_accounts_m->update($post->id,$update)){
                                $response = array(
                                    'status' => 1,
                                    'message' => 'Petty cash account successfully closed',
                                );
                            }else{
                                $response = array(
                                    'status' => 1,
                                    'message' => 'Unable to close the petty cash account',
                                );
                            }
                        }else{
                            $response = array(
                                'status' => 0,
                                'message' => 'Could not close group petty cash account. Select a different account',
                            );
                        }
                    }else{
                        $response = array(
                            'status' => 0,
                            'message' => 'You are not allowed to perform this request. For group admins only',
                            'time' => time(),
                        );
                    }
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));
    }

    function activate(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    if($this->member->group_role_id || $this->member->is_admin){
                        $id = $this->input->post('id');
                        if($post = $this->petty_cash_accounts_m->get_group_petty_cash_account($id,$this->group->id)){
                            if(!$post->is_closed){
                                $response = array(
                                    'status' => 0,
                                    'message' => 'Sorry, the petty cash account is already activate',
                                );
                            }
                            $update = array(
                                'is_closed'=>0,
                                'modified_by'=>$this->user->id,
                                'modified_on'=>time()
                            );
                            if($res = $this->petty_cash_accounts_m->update($post->id,$update)){
                                $response = array(
                                    'status' => 1,
                                    'message' => 'Petty cash account successfully activated',
                                );
                            }else{
                                $response = array(
                                    'status' => 1,
                                    'message' => 'Unable to activate the petty cash account',
                                );
                            }
                        }else{
                            $response = array(
                                'status' => 0,
                                'message' => 'Could not activate group petty cash account. Select a different account',
                            );
                        }
                    }else{
                        $response = array(
                            'status' => 0,
                            'message' => 'You are not allowed to perform this request. For group admins only',
                            'time' => time(),
                        );
                    }
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));
    }

    function delete(){
        foreach ($this->request as $key => $value) {
            if(preg_match('/phone/', $key)){
                $_POST[$key] = valid_phone($value);
            }else{
                $_POST[$key] = $value;
            }
        }
        $user_id = $this->input->post('user_id');
        if($this->user = $this->ion_auth->get_user($user_id)){
            $this->ion_auth->update_last_login($this->user->id);
            $group_id = $this->input->post('group_id');
            if($this->group = $this->groups_m->get($group_id)){
                if($this->member = $this->members_m->get_group_member_by_user_id($this->group->id,$this->user->id)){
                    if($this->member->group_role_id || $this->member->is_admin){
                        $id = $this->input->post('id');
                        $identity = $this->user->email?:$this->user->phone;
                        $password = $this->input->post('password');
                        if($this->ion_auth->login($identity,$password)){
                            if($post = $this->petty_cash_accounts_m->get_group_petty_cash_account($id,$this->group->id)){
                                if($this->transaction_statements_m->check_if_group_account_has_transactions('petty-'.$post->id,$post->group_id)){
                                    $response = array(
                                        'status' => 0,
                                        'message' => 'The petty cash account has transactions associated to it, void all transactions associated to this account before deleting it',
                                    );
                                }else{
                                    if($this->petty_cash_accounts_m->delete($post->id,$post->group_id)){
                                        $response = array(
                                            'status' => 1,
                                            'message' => 'Petty cash account deleted successfully',
                                        );
                                    }else{
                                        $response = array(
                                            'status' => 0,
                                            'message' => 'Petty cash account could not be deleted',
                                        );
                                    }
                                }
                            }else{
                                $response = array(
                                    'status' => 0,
                                    'message' => 'Could not delete group petty cash account. Select a different account',
                                );
                            }

                            
                        }else{
                            $response = array(
                                'status' => 0,
                                'message' => 'You entered the wrong password.',
                            );
                        }
                    }else{
                        $response = array(
                            'status' => 0,
                            'message' => 'You are not allowed to perform this request. For group admins only',
                            'time' => time(),
                        );
                    }
                }else{
                    $response = array(
                        'status' => 6,
                        'message' => 'Could not find Applicant Details
',
                        'time' => time(),
                    );
                }
            }else{
                $response = array(
                    'status' => 5,
                    'message' => 'Could not find group details',
                    'time' => time(),
                );
            }
        }else{
            $response = array(
                'status' => 4,
                'message' => 'Could not find user details',
                'time' => time(),
            );
        }
        echo encrypt_json_encode(array('response'=>$response));
    }

}