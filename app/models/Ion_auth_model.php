<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
*
* Version: 2.5.2
*
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*	  	   @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Last Change: 3.22.13
*
* Changelog:
* * 3-22-13 - Additional entropy added - 52aa456eef8b60ad6754b31fbdcc77bb
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ion_auth_model extends MY_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	public $identity_column;

	/**
	 * Where
	 *
	 * @var array
	 **/
	public $_ion_where = array();

	/**
	 * Select
	 *
	 * @var array
	 **/
	public $_ion_select = array();

	/**
	 * Like
	 *
	 * @var array
	 **/
	public $_ion_like = array();

	/**
	 * Limit
	 *
	 * @var string
	 **/
	public $_ion_limit = NULL;

	/**
	 * Offset
	 *
	 * @var string
	 **/
	public $_ion_offset = NULL;

	/**
	 * Order By
	 *
	 * @var string
	 **/
	public $_ion_order_by = NULL;

	/**
	 * Order
	 *
	 * @var string
	 **/
	public $_ion_order = NULL;

	/**
	 * Hooks
	 *
	 * @var object
	 **/
	protected $_ion_hooks;

	/**
	 * Response
	 *
	 * @var string
	 **/
	protected $response = NULL;

	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;

	/**
	 * error start delimiter
	 *
	 * @var string
	 **/
	protected $error_start_delimiter;

	/**
	 * error end delimiter
	 *
	 * @var string
	 **/
	protected $error_end_delimiter;

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group = array();

	/**
	 * caching of groups
	 *
	 * @var array
	 **/
	protected $_cache_groups = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->load->dbforge();
		$this->lang->load('ion_auth');
		// initialize db tables data
		$this->tables  = $this->config->item('tables', 'ion_auth');

		//initialize data
		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length     = $this->config->item('salt_length', 'ion_auth');
		$this->join			   = $this->config->item('join', 'ion_auth');


		// initialize hash method options (Bcrypt)
		$this->hash_method = $this->config->item('hash_method', 'ion_auth');
		$this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
		$this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
		$this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
		$this->max_rounds = $this->config->item('max_rounds', 'ion_auth');


		// initialize messages and error
		$this->messages    = array();
		$this->errors      = array();
		$delimiters_source = $this->config->item('delimiters_source', 'ion_auth');

		// load the error delimeters either from the config file or use what's been supplied to form validation
		if ($delimiters_source === 'form_validation')
		{
			// load in delimiters from form_validation
			// to keep this simple we'll load the value using reflection since these properties are protected
			$this->load->library('form_validation');
			$form_validation_class = new ReflectionClass("CI_Form_validation");

			$error_prefix = $form_validation_class->getProperty("_error_prefix");
			$error_prefix->setAccessible(TRUE);
			$this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
			$this->message_start_delimiter = $this->error_start_delimiter;

			$error_suffix = $form_validation_class->getProperty("_error_suffix");
			$error_suffix->setAccessible(TRUE);
			$this->error_end_delimiter = $error_suffix->getValue($this->form_validation);
			$this->message_end_delimiter = $this->error_end_delimiter;
		}
		else
		{
			// use delimiters from config
			$this->message_start_delimiter = $this->config->item('message_start_delimiter', 'ion_auth');
			$this->message_end_delimiter   = $this->config->item('message_end_delimiter', 'ion_auth');
			$this->error_start_delimiter   = $this->config->item('error_start_delimiter', 'ion_auth');
			$this->error_end_delimiter     = $this->config->item('error_end_delimiter', 'ion_auth');
		}


		// initialize our hooks object
		$this->_ion_hooks = new stdClass;

		// load the bcrypt class if needed
		if ($this->hash_method == 'bcrypt') {
			if ($this->random_rounds)
			{
				$rand = rand($this->min_rounds,$this->max_rounds);
				$params = array('rounds' => $rand);
			}
			else
			{
				$params = array('rounds' => $this->default_rounds);
			}

			$params['salt_prefix'] = $this->config->item('salt_prefix', 'ion_auth');
			$this->load->library('bcrypt',$params);
		}

		$this->trigger_events('model_constructor');

		$this->install();
	}


	/**
	 *	To create tables
	 *
	 *
	 *
	*/
	function install()
	{
		$this->db->query("
					CREATE TABLE IF NOT EXISTS `groups` (
					  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
					  `name` BLOB,
					  `description` BLOB,
					  `created_on` BLOB,
					  `created_by` BLOB,
					  `modified_by` BLOB,
					  `modified_on` BLOB,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `users` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `ip_address` blob,
				  `username` blob,
				  `password` blob,
				  `salt` blob,
				  `email` blob,
				  `activation_code` blob,
				  `forgotten_password_code` blob,
				  `forgotten_password_time` blob,
				  `remember_code` blob,
				  `created_on` blob,
				  `last_login` blob,
				  `active` blob,
				  `first_name` blob,
				  `middle_name` blob,
				  `last_name` blob,
				  `company` blob,
				  `phone` blob,
				  `id_number` blob,
				  `ussd_pin` blob,
				  `confirmation_code` blob,
				  `created_by` BLOB,
				  `modified_by` BLOB,
				  `modified_on` BLOB,
				  `expiry_time` blob,
				  `user_account_activation_code` blob,
				  `user_account_active` blob,
				  `avatar` blob,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `users_groups` (
				  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) unsigned NOT NULL,
				  `group_id` mediumint(8) unsigned NOT NULL,
				  `created_on` BLOB,
				  `created_by` BLOB,
				  `modified_by` BLOB,
				  `modified_on` BLOB,
				  PRIMARY KEY (`id`),
				  KEY `fk_users_groups_users1_idx` (`user_id`),
				  KEY `fk_users_groups_groups1_idx` (`group_id`),
				  CONSTRAINT `uc_users_groups` UNIQUE (`user_id`, `group_id`),
				  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
				  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;

			");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `login_attempts` (
					`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`ip_address` blob,
					`login` blob,
					`time` blob,
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");

		//function insert admin

		if(!$this->db->count_all('groups'))
		{
			$this->insert_secure_data('groups',array(
					'name'	=>	'admin',
					'description'	=>	'Administrator'
				));
		}

		if(!$this->db->count_all('users'))
		{
			$additional_data = array(
					'ip_address'		=>		'127.0.0.1', 
					'username'			=>		'Geofrey',
					'activation_code'	=>		'', 
					'forgotten_password_code'	=>	'', 
					'created_on'		=>		time(), 
					'last_login'		=>		'', 
					'active'			=>		1, 
					'ussd_pin'			=>		rand(1000,9999),
					'first_name'		=>		'Geofrey', 
					'last_name'			=>		'Ongidi', 
					'company'			=>		'ADMIN', 
					);

			$this->register('254748974489', '36955148','ongidigeofrey@gmail.com', $additional_data,array(1));
		}

	
	}

	/**
	 * Misc functions
	 *
	 * Hash password : Hashes the password to be stored in the database.
	 * Hash password db : This function takes a password and validates it
	 * against an entry in the users table.
	 * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/



	public function hash_password($password, $salt=false, $use_sha1_override=FALSE,$new_password_hash = 0)
	{
		if (empty($password))
		{
			return FALSE;
		}

		if($new_password_hash){
			return sha1($password . $salt);
		}

		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			return $this->bcrypt->hash($password);
		}


		if ($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		}
		else
		{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/

	public function hash_password_db($id, $password, $use_sha1_override = FALSE)
    {
        if (empty($id) || empty($password))
        {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        $this->select_secure('password');
        $this->select_secure('salt');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        $hash_password_db = $query->row();;

        if ($query->num_rows() !== 1)
        {
            return FALSE;
        }

        // bcrypt
        if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
        {
            if ($this->bcrypt->verify($password, $hash_password_db->password))
            {
                return TRUE;
            }

            return FALSE;
        }

        // sha1
        if ($this->store_salt)
        {
            $db_password = sha1($password . $hash_password_db->salt);

        }
        else
        {
            $salt = substr($hash_password_db->password, 0, $this->salt_length);
            echo $db_password = $salt . substr(sha1($salt . $password), 0, -$this->salt_length);

            
        }

        if ($db_password == $hash_password_db->password)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}

	/**
	 * Generates a random salt value.
	 *
	 * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
	 *
	 * @return void
	 * @author Anthony Ferrera
	 **/
	public function salt()
	{

		$raw_salt_len = 16;

 		$buffer = '';
        $buffer_valid = false;

        if (function_exists('random_bytes')) {
		  $buffer = random_bytes($raw_salt_len);
		  if ($buffer) {
		    $buffer_valid = true;
		  }
		}

		if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
		     $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
		    if ($buffer) {
		        $buffer_valid = true;
		    }
		}

        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && @is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len) {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++) {
                if ($i < $bl) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }

        $salt = $buffer;

        // encode string with the Base64 variant used by crypt
        $base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string   = base64_encode($salt);
        $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

	    $salt = substr($salt, 0, $this->salt_length);


		return $salt;

	}

	/**
	 * Activation functions
	 *
	 * Activate : Validates and removes activation code.
	 * Deactivae : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */

	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/

	public function activate($id, $code = FALSE)
    {
        $this->trigger_events('pre_activate');

        if ($code !== FALSE)
        {
            $query = $this->db->select($this->identity_column)
                    ->where('activation_code', $code)
                    ->limit(1)
                    ->get($this->tables['users']);

            $result = $query->row();

            if ($query->num_rows() !== 1)
            {
                $this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
                $this->set_error('activate_unsuccessful');
                return FALSE;
            }

            $identity = $result->{$this->identity_column};

            $data = array(
                'activation_code' => NULL,
                'active' => 1
            );

            $this->trigger_events('extra_where');
            $this->db->update_secure_data($id,$this->tables['users'], $data);
        }
        else
        {
            $data = array(
                'activation_code' => NULL,
                'active' => 1
            );


            $this->trigger_events('extra_where');
            $this->update_secure_data($id,$this->tables['users'], $data);
        }


        $return = $this->db->affected_rows() == 1;
        if ($return)
        {
            $this->trigger_events(array('post_activate', 'post_activate_successful'));
            $this->set_message('activate_successful');
        }
        else
        {
            $this->trigger_events(array('post_activate', 'post_activate_unsuccessful'));
            $this->set_error('activate_unsuccessful');
        }


        return $return;
    }


	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}

		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data = array(
		    'activation_code' => $activation_code,
		    'active'          => 0
		);

		$this->trigger_events('extra_where');
		$this->db->update_secure_data($id,$this->tables['users'], $data);

		$return = $this->db->affected_rows() == 1;
		if ($return)
			$this->set_message('deactivate_successful');
		else
			$this->set_error('deactivate_unsuccessful');

		return $return;
	}

	public function clear_forgotten_password_code($code) {

		if (empty($code))
		{
			return FALSE;
		}

		$this->db->where('forgotten_password_code', $code);

		if ($this->db->count_all_results($this->tables['users']) > 0)
		{
			$data = array(
			    'forgotten_password_code' => NULL,
			    'forgotten_password_time' => NULL,
			    'expiry_time'	=> NuLL,
			    'confirmation_code'	=>	NULL,
			);

			$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * reset password
	 *
	 * @return bool
	 * @author Mathew
	 **/

	public function reset_password($identity, $new)
    {
        $this->trigger_events('pre_change_password');
		
		if(valid_phone($identity))
		{
			$identity = valid_phone($identity);
		}
		
        if (!$this->identity_check($identity))
        {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            return FALSE;
        }

        $this->identity_column = valid_email($identity)?'email':'phone';

        $this->trigger_events('extra_where');

        $query = $this->db->select(array('id',$this->dxa('password'),$this->dxa('salt')))
                ->where($this->dx($this->identity_column)." ='$identity'",NULL,FALSE)
                ->limit(1)
                ->get($this->tables['users'])->row();
		
		
		
        if (!$query->id)
        {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

		$result = $query;

        $new = $this->hash_password($new, $result->salt);

        //store the new password and reset the remember code so all remembered instances have to re-login
        //also clear the forgotten password code
        $data = array(
            'password' => $new,
            'remember_code' => NULL,
            'forgotten_password_code' => NULL,
            'forgotten_password_time' => NULL,
        );

        $this->trigger_events('extra_where');
		
        $this->update_secure_data($query->id,$this->tables['users'], $data);

        $return = $this->db->affected_rows() == 1;
        if ($return)
        {
        	$this->messaging->send_password_change_notice($identity);
            $this->trigger_events(array('post_change_password', 'post_change_password_successful'));
            $this->set_message('password_change_successful');
        }
        else
        {
            $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
        }

        return $return;
    }

	/**
	 * change password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new){
		if(valid_phone($identity))
        {
        	$identity = valid_phone($identity);
        }

        $this->identity_column = valid_phone($identity)?'phone':'email';
		$this->trigger_events('pre_change_password');

		$this->trigger_events('extra_where');

		$query = $this->db->select(array(
							'id', 
							$this->dxa('password'), 
							$this->dxa('salt')
						))
		                  ->where($this->dx($this->identity_column).' = "'.$identity.'"',NULL,FALSE)
		                  ->limit(1)
		    			  ->order_by('id', 'desc')
		                  ->get($this->tables['users']); 

		if ($query->num_rows() !== 1){
			$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$user = $query->row();

		$old_password_matches = $this->hash_password_db($user->id, $old);

		if ($old_password_matches === TRUE){

			// store the new password and reset the remember code so all remembered instances have to re-login
			$hashed_new_password  = $this->hash_password($new, $user->salt);
			$data = array(
			    'password' => $hashed_new_password,
			    'remember_code' => NULL,
			);

			$this->trigger_events('extra_where');

			$successfully_changed_password_in_db = $this->update_secure_data($user->id,$this->tables['users'], $data);
			if ($successfully_changed_password_in_db){
				$this->trigger_events(array('post_change_password', 'post_change_password_successful'));
				$this->set_message('password_change_successful');
			}else{
				$this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
				$this->set_error('password_change_unsuccessful');
			}

			return $successfully_changed_password_in_db;
		}
		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}

	/**
	 * Checks username
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function username_check($username = '')
	{
		$this->trigger_events('username_check');

		if (empty($username))
		{
			return FALSE;
		}

		$this->trigger_events('extra_where');

		return $this->db->where('username', $username)
										->group_by("id")
										->order_by("id", "ASC")
										->limit(1)
		                ->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Checks email
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function email_check($email = '')
    {
        $this->trigger_events('email_check');

        if (empty($email))
        {
            return FALSE;
        }

        $this->trigger_events('extra_where');

        return $this->db->where($this->dx('email')." = '$email'",NULL,FALSE)
                        ->count_all_results($this->tables['users']) > 0;
    }


	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function identity_check($identity = '')
    {
        $this->trigger_events('identity_check');

        if (empty($identity))
        {
            return FALSE;
        }
        if(valid_phone($identity))
        {
        	$identity = valid_phone($identity);
        }
        $this->select_all_secure('users');
        if(valid_phone($identity)){
			$this->db->where('('.$this->dx('users.phone').'="'.$identity.'" OR '.$this->dx('users.phone').' = "'.valid_phone($identity).'"  OR '.$this->dx('users.phone').' = "+'.valid_phone($identity).'" OR '.$this->dx('users.phone').' ="+'.$identity.'" OR '.$this->dx('users.phone').' ="'.str_replace('+','', $identity).'" )',NULL,FALSE);
			return $this->db->get($this->tables['users'])->row();
		}else{
			$this->db->where($this->dx('users.email').'="'.$identity.'"',NULL,FALSE);
			return $this->db->get($this->tables['users'])->row();
		}
    }

    function check_mail_duplicate($email = '')
    {
    	if(empty($email))
    	{
    		return FALSE;
    	}

    	return $this->db->where($this->dx('email')." ='".$email."'",NULL,FALSE)
                        ->count_all_results($this->tables['users']) > 0;
    }

    function id_number_check($id_number = '')
    {
    	$this->select_all_secure('users');
        $this->db->where($this->dx('id_number')." ='".$id_number."'",NULL,FALSE);
        return $this->db->get($this->tables['users'])->row();
    }

	/**
	 * Insert a forgotten password key.
	 *
	 * @return bool
	 * @author Mathew
	 * @updated Ryan
	 * @updated 52aa456eef8b60ad6754b31fbdcc77bb
	 **/

	public function forgotten_password($identity,$remember_code = '')
    {
		if (empty($identity))
        {
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));
            return FALSE;
        }

        if(valid_phone($identity))
        {
        	//$identity = valid_phone($identity);
        }


        $key = $this->hash_code(microtime() . $identity);

        $this->forgotten_password_code = $key;

        $this->trigger_events('extra_where');

        $code = rand(10000,99999);
        $expiry_time = $time=strtotime('+30 minutes',time());
        if($remember_code){

        }else{
        	$remember_code = rand(1111,9999);
        }
        
        $update = array(
            'forgotten_password_code' => $key,
            'forgotten_password_time' => time(),
            'confirmation_code' => $code,
            'expiry_time'	=> $expiry_time,
            'remember_code' => $remember_code,
        );
        $this->identity_column = valid_email($identity)?'email':'phone';		
		$user = $this->get_user_by_identity($identity);
		
		if(!$user) return FALSE;
        $this->db->update($this->tables['users'],  
         $update = array(
            'forgotten_password_code' => $key,
            'forgotten_password_time' =>time(),
            'confirmation_code' => $code,
            'expiry_time'	=> $expiry_time
        ));
		$this->update_secure_data($user->id,$this->tables['users'],$update);
        $return = $this->db->affected_rows() == 1;
				
        if ($return)
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_successful'));
        else
            $this->trigger_events(array('post_forgotten_password', 'post_forgotten_password_unsuccessful'));

	
		
        return $return;
    }


	/**
	 * Forgotten Password Complete
	 *
	 * @return string
	 * @author Mathew
	 **/


	function confirm_code_alone($code=0){
		if(!$code)
		{
			return FALSE;
		}		

		$this->select_all_secure($this->tables['users']);
		$this->db->where($this->dx('confirmation_code') . " = '" . $this->db->escape_str($code) . "'", NULL, FALSE);
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);
        return $query->row();
	}


	public function confirm_code($identity,$code)
	{
		if(!$identity && !$code)
		{
			return FALSE;
		}
		$this->identity_column = valid_phone($identity)?'phone':'email';

		$this->select_all_secure($this->tables['users']);
		if($this->identity_column=='phone'){
        	$this->db->where(" ( 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str(valid_phone($identity)) . "' OR 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'  OR 
        		".$this->dx($this->identity_column) . " = '+" . $this->db->escape_str(valid_phone($identity)) . "' )", NULL, FALSE);
        }else{
        	$this->db->where($this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'", NULL, FALSE);
        }
		$this->db->where($this->dx('confirmation_code') . " = '" . $this->db->escape_str($code) . "'", NULL, FALSE);
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        if ($query->num_rows() === 1)
        {
        	return $query->row();
        }
        else
        {
        	$this->set_error('confirmation_code_error');
        	return FALSE;
        }
	}


	public function forgotten_password_complete($code, $salt = FALSE)
    {
        $this->trigger_events('pre_forgotten_password_complete');

        if (empty($code))
        {
            $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
            return FALSE;
        }

        $profile = $this->db->where($this->dx('forgotten_password_code')." ='$code'",NULL,FALSE)->get('users')->row(); //pass the code to profile

        if ($profile)
        {

            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
            {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $profile->forgotten_password_time > $expiration)
                {
                    //it has expired
                    $this->set_error('forgot_password_expired');
                    $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
                    return FALSE;
                }
            }

            $password = $this->salt();

            $data = array(
                'password' => $this->hash_password($password, $salt),
                'forgotten_password_code' => NULL,
                'active' => 1,
            );

            $this->update_secure_data($profile->id,$this->tables['users'], $data);

            $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_successful'));
            return $password;
        }

        $this->trigger_events(array('post_forgotten_password_complete', 'post_forgotten_password_complete_unsuccessful'));
        return FALSE;
    }

	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register($identity, $password, $email, $additional_data = array(), $groups = array(),$ignore_phone_validation=FALSE)
	{
		if(valid_phone($identity) && $ignore_phone_validation == FALSE)
		{
			$identity = valid_phone($identity);
		}
		if(valid_email($identity) && empty($email))
		{
			$email = $identity;
		}

		$this->trigger_events('pre_register');

		$manual_activation = $this->config->item('manual_activation', 'ion_auth');

		if ($this->identity_check($identity))
		{
			$this->set_error('account_creation_duplicate_identity');
			return FALSE;
		}
		elseif ( !$this->config->item('default_group', 'ion_auth') && empty($groups) )
		{
			$this->set_error('account_creation_missing_default_group');
			return FALSE;
		}

		if($email)
		{
			if($this->check_mail_duplicate($email))
			{
				$this->set_error('account_creation_duplicate_identity');
				return False;
				
			}
		}

		$this->identity_column = valid_phone($identity)?'phone':'email';

		// check if the default set in config exists in database
		$query = $this->db->get_where($this->tables['groups'],array('name' => $this->config->item('default_group', 'ion_auth')),1)->row();
		if( !isset($query->id) && empty($groups) )
		{
			$this->set_error('account_creation_invalid_default_group');
			return FALSE;
		}

		// capture default group details
		$default_group = $query;

		// IP Address
		$ip_address = $this->_prepare_ip($this->input->ip_address());
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);

		// Users table.
		$data = array(
		    $this->identity_column   => $identity,
		    'password'   => $password,
		    'email'      => $email,
		    'ip_address' => $ip_address,
		    'created_on' => time(),
		    'active'     => ($manual_activation === false ? 1 : 0)
		);

		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}

		// filter out any data passed that doesnt have a matching column in the users table
		// and merge the set user data and the additional data
		$user_data = array_merge($this->_filter_data($this->tables['users'], $additional_data), $data);

		$this->trigger_events('extra_set');

		$id = $this->insert_secure_data($this->tables['users'], $user_data);

		// add in groups array if it doesn't exits and stop adding into default group if default group ids are set
		if( isset($default_group->id) && empty($groups) )
		{
			$groups[] = $default_group->id;
		}

		if (!empty($groups))
		{
			// add to groups
			foreach ($groups as $group)
			{
				$this->add_to_group($group, $id);
			}
		}

		$this->trigger_events('post_register');

		return (isset($id)) ? $id : FALSE;
	}

	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login($identity, $password, $remember = FALSE){

    	if(valid_phone($identity))
    	{
    		//$identity = valid_phone($identity);
    	}
        $this->trigger_events('pre_login');

        if (empty($identity) || empty($password))
        {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }
        $this->identity_column = valid_email($identity)?'email':'phone';
        $this->trigger_events('extra_where');
        $this->select_all_secure($this->tables['users']);
        if($this->identity_column=='phone'){
        	$this->db->where(" ( 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str(valid_phone($identity)) . "' OR 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'  OR 
        		".$this->dx($this->identity_column) . " = '+" . $this->db->escape_str(valid_phone($identity)) . "' )", NULL, FALSE);
        }else{
        	$this->db->where($this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'", NULL, FALSE);
        }
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        if($query->row() && $query->row()->active !=1)
        {
        	 $this->set_error('inactive_account');
              return FALSE;
        }

        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            $password = $this->hash_password_db($user->id, $password);
            if ($password === TRUE)
            {
            	
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');

                    return FALSE;
                }

                $session_data = array(
                    'identity' => $user->{$this->identity_column},
                    'username' => $user->username,
                    'email' => $user->email,
                    'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
                    'old_last_login' => $user->last_login
                );


                $this->update_last_login($user->id);


                $this->clear_login_attempts($identity);

                $this->session->set_userdata($session_data);


                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_user($user->id);
                }

                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;
            }
            
        }

        //Hash something anyway, just to take up time
        $this->hash_password($password);
		$this->increase_login_attempts($identity);
        $this->trigger_events('post_login_unsuccessful');
        $this->set_error('login_unsuccessful');

        return FALSE;
    }


     /**
     * login
     *
     * @return bool
     * @author Mathew
     * */
    public function droid_login($identity, $password, $remember = FALSE)
    {
        if(valid_phone($identity))
    	{
    		//$identity = valid_phone($identity);
    	}
        $this->trigger_events('pre_login');

        if (empty($identity) || empty($password))
        {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }
        $this->identity_column = valid_email($identity)?'email':'phone';
        $this->trigger_events('extra_where');
        $this->select_all_secure($this->tables['users']);
        if($this->identity_column=='phone'){
        	$this->db->where(" ( 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str(valid_phone($identity)) . "' OR 
        		".$this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'  OR 
        		".$this->dx($this->identity_column) . " = '+" . $this->db->escape_str(valid_phone($identity)) . "' )", NULL, FALSE);
        }else{
        	$this->db->where($this->dx($this->identity_column) . " = '" . $this->db->escape_str($identity) . "'", NULL, FALSE);
        }
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        if($query->row() && $query->row()->active !=1)
        {
        	 //$this->set_error('inactive_account');
              return FALSE;
        }


        if ($query->num_rows() === 1)
        {
            $user = $query->row();
            $password = $this->hash_password_db($user->id, $password);
            if ($password === TRUE)
            {
            	
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    //$this->set_error('login_unsuccessful_not_active');

                    return FALSE;
                }

                $session_data = array(
                    'identity' => $user->{$this->identity_column},
                    'username' => $user->username,
                    'email' => $user->email,
                    'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
                    'old_last_login' => $user->last_login
                );


                $this->update_last_login($user->id);


                $this->clear_login_attempts($identity);

                $this->session->set_userdata($session_data);


                if ($remember && $this->config->item('remember_users', 'ion_auth'))
                {
                    $this->remember_user($user->id);
                }

                $this->trigger_events(array('post_login', 'post_login_successful'));
                //$this->set_message('login_successful');

                return $user;
            }
            
        }

        //Hash something anyway, just to take up time
       // $this->hash_password($password);

        $this->increase_login_attempts($identity);

        $this->trigger_events('post_login_unsuccessful');
        //$this->set_error('login_unsuccessful');

        return FALSE;
    }


    /**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function machine_login($user_id){

		if(!defined('ARA')){
			die;
		}
    	
		$this->select_all_secure($this->tables['users']);
		$this->db->where('id',$user_id);
		$this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        if($query->row() && $query->row()->active !=1)
        {
        	 //$this->set_error('inactive_account');
              return FALSE;
        }



        if ($query->num_rows() === 1)
        {
           	$user = $query->row();
                if ($user->active == 0)
                {
                    $this->trigger_events('post_login_unsuccessful');
                    $this->set_error('login_unsuccessful_not_active');

                    return FALSE;
                }

                $session_data = array(
                    'identity' => $user->{$this->identity_column},
                    'username' => $user->username,
                    'email' => $user->email,
                    'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
                    'old_last_login' => $user->last_login
                );


                $this->update_last_login($user->id);


                $this->clear_login_attempts($user->{$this->identity_column});

                $this->session->set_userdata($session_data);


                $this->trigger_events(array('post_login', 'post_login_successful'));
                $this->set_message('login_successful');

                return TRUE;

        }

        //Hash something anyway, just to take up time
        //$this->hash_password($password);

        $this->increase_login_attempts($identity);

        $this->trigger_events('post_login_unsuccessful');
        $this->set_error('login_unsuccessful');

        return FALSE;
    }

	/**
	 * is_max_login_attempts_exceeded
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 * @return boolean
	 **/
	public function is_max_login_attempts_exceeded($identity) {
		if ($this->config->item('track_login_attempts', 'ion_auth')) {
			$max_attempts = $this->config->item('maximum_login_attempts', 'ion_auth');
			if ($max_attempts > 0) {
				$attempts = $this->get_attempts_num($identity);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	/**
	 * Get number of attempts to login occured from given IP-address or identity
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param	string $identity
	 * @return	int
	 */
	public function get_attempts_num($identity) {
		$ip_address = $this->_prepare_ip($this->input->ip_address());
    	$this->where($this->dx('ip_address')." = ".$this->db->escape($ip_address),NuLL,FALSE);
		$this->where($this->dx('login')." = ".$this->db->escape($identity),NuLL,FALSE);
        return $this->count_all_results(strtoupper($this->tables['login_attempts']));
	}

	/**
	 * Get a boolean to determine if an account should be locked out due to
	 * exceeded login attempts within a given period
	 *
	 * @return	boolean
	 */
	public function is_time_locked_out($identity) {
		return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'ion_auth');
	}

	/**
	 * Get the time of the last time a login attempt occured from given IP-address or identity
	 *
	 * @param	string $identity
	 * @return	int
	 */
	public function get_last_attempt_time($identity) {
		if ($this->config->item('track_login_attempts', 'ion_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			//$this->db->select_max('time');
			$this->select_all_secure($this->tables['login_attempts']);
			$this->db->order_by('time', 'desc');
            if ($this->config->item('track_login_ip_address', 'ion_auth')) {
				$this->where('IP_ADDRESS', $ip_address);
			}
			else if (strlen($identity) > 0){
				$this->or_where('LOGIN', $identity);
			}			
			$qres = $this->db->get($this->tables['login_attempts'], 1);

			if($qres->num_rows() > 0) {
				return $qres->row()->time;
			}
		}

		return 0;
	}

	/**
	 * increase_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function increase_login_attempts($identity) {
		if ($this->config->item('track_login_attempts', 'ion_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			//return $this->db->insert(strtoupper($this->tables['login_attempts']), array('IP_ADDRESS' => $ip_address, 'LOGIN' => $identity, 'TIME' => time()));
			return $this->insert_secure_data($this->tables['login_attempts'], array(
				'ip_address' => $ip_address,
				'login' => $identity,
				'time' => time()
			));
		}
		return FALSE;
	}

	/**
	 * clear_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function clear_login_attempts($identity, $expire_period = 86400) {
		if ($this->config->item('track_login_attempts', 'ion_auth')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->where(array('ip_address' => $ip_address, 'login' => $identity));
			// Purge obsolete login attempts
			$this->db->or_where('time <', time() - $expire_period, FALSE);

			return $this->db->delete($this->tables['login_attempts']);
		}
		return FALSE;
	}

	public function limit($limit, $offset = 0)
	{
		$this->trigger_events('limit');
		$this->_ion_limit = $limit;

		return $this;
	}

	public function offset($offset)
	{
		$this->trigger_events('offset');
		$this->_ion_offset = $offset;

		return $this;
	}

	public function where($where, $value = NULL)
	{
		$this->trigger_events('where');

		if (!is_array($where))
		{
			$where = array($where => $value);
		}

		array_push($this->_ion_where, $where);

		return $this;
	}

	public function like($like, $value = NULL, $position = 'both')
	{
		$this->trigger_events('like');

		if (!is_array($like))
		{
			$like = array($like => array(
				'value'    => $value,
				'position' => $position,
			));
		}

		array_push($this->_ion_like, $like);

		return $this;
	}

	public function select($select)
	{
		$this->trigger_events('select');

		$this->_ion_select[] = $select;

		return $this;
	}

	public function order_by($by, $order='desc')
	{
		$this->trigger_events('order_by');

		$this->_ion_order_by = $by;
		$this->_ion_order    = $order;

		return $this;
	}

	public function row()
	{
		$this->trigger_events('row');

		$row = $this->response->row();

		return $row;
	}

	public function row_array()
	{
		$this->trigger_events(array('row', 'row_array'));

		$row = $this->response->row_array();

		return $row;
	}

	public function result()
	{
		$this->trigger_events('result');

		$result = $this->response->result();

		return $result;
	}

	public function result_array()
	{
		$this->trigger_events(array('result', 'result_array'));

		$result = $this->response->result_array();

		return $result;
	}

	public function num_rows()
	{
		$this->trigger_events(array('num_rows'));

		$result = $this->response->num_rows();

		return $result;
	}

	/**
	 * users
	 *
	 * @return object Users
	 * @author Ben Edmunds
	 **/
	public function users($groups = NULL)
    {
        $this->trigger_events('users');

        //default selects
        $this->select_all_secure($this->tables['users']);
        $this->select(array($this->dx('id') . " as user_id "), FALSE);

        if (isset($this->_ion_select))
        {
            foreach ($this->_ion_select as $select)
            {
                $this->db->select($select);
            }

            $this->_ion_select = array();
        }

        //filter by group id(s) if passed
        if (isset($groups))
        {
            //build an array if only one group was passed
            if (is_numeric($groups))
            {
                $groups = Array($groups);
            }

            //join and then run a where_in against the group ids
            if (isset($groups) && !empty($groups))
            {
                $this->db->distinct();
                $this->db->join(
                        $this->tables['users_groups'], $this->tables['users_groups'] . '.user_id = ' . $this->tables['users'] . '.id', 'inner'
                );

                $this->db->where_in($this->tables['users_groups'] . '.group_id', $groups);
            }
        }

        $this->trigger_events('extra_where');

        //run each where that was passed
        if (isset($this->_ion_where))
        {
            foreach ($this->_ion_where as $where)
            {
                //$this->db->where($where);
                foreach ($where as $k => $v)
                {
                    if (preg_match('/id/i', $k) || preg_match('/\.id/i', $k))
                    {
                        $this->db->where("$k = '$v'", NULL, FALSE);
                    }
                    else
                    {
                        $this->db->where($this->dx($k) . " = '$v'", NULL, FALSE);
                    }
                }
            }

            $this->_ion_where = array();
        }

        if (isset($this->_ion_limit) && isset($this->_ion_offset))
        {
            $this->db->limit($this->_ion_limit, $this->_ion_offset);

            $this->_ion_limit = NULL;
            $this->_ion_offset = NULL;
        }
        else if (isset($this->_ion_limit))
        {
            $this->db->limit($this->_ion_limit);

            $this->_ion_limit = NULL;
        }

        //set the order
        if (isset($this->_ion_order_by) && isset($this->_ion_order))
        {
            $this->db->order_by($this->_ion_order_by, $this->_ion_order);

            $this->_ion_order = NULL;
            $this->_ion_order_by = NULL;
        }

        $this->response = $this->db->get($this->tables['users']);

        return $this;
    }

	/**
	 * user
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function user($id = NULL)
	{
		$this->trigger_events('user');

		// if no id was passed use the current users id
		$id || $id = $this->session->userdata('user_id');

		$this->limit(1);
		$this->order_by($this->tables['users'].'.id', 'desc');
		$this->where($this->tables['users'].'.id', $id);

		$this->users();

		return $this;
	}

	/**
	 * get_users_groups
	 *
	 * @return array
	 * @author Ben Edmunds
	 **/
	public function get_users_groups($id=FALSE)
	{
		$this->trigger_events('get_users_group');

		// if no id was passed use the current users id
		$id || $id = $this->session->userdata('user_id');

		return $this->db->select($this->tables['users_groups'].'.'.$this->join['groups'].' as id, '.$this->dx($this->tables['groups'].'.name').'as name,'.$this->dx($this->tables['groups'].'.description').'as description')
		                ->where($this->tables['users_groups'].'.'.$this->join['users'], $id)
		                ->join($this->tables['groups'], $this->tables['users_groups'].'.'.$this->join['groups'].'='.$this->tables['groups'].'.id')
		                ->get($this->tables['users_groups']);
	}

	/**
	 * add_to_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function add_to_group($group_ids, $user_id=false)
	{
		$this->trigger_events('add_to_group');

		// if no id was passed use the current users id
		$user_id || $user_id = $this->session->userdata('user_id');

		if(!is_array($group_ids))
		{
			$group_ids = array($group_ids);
		}

		$return = 0;
		$group_name = '';

		// Then insert each into the database
		foreach ($group_ids as $group_id)
		{
			if ($this->db->insert($this->tables['users_groups'], array( $this->join['groups'] => (int)$group_id, $this->join['users'] => (int)$user_id)))
			{
				if (isset($this->_cache_groups[$group_id])) {
					$group_name = $this->_cache_groups[$group_id];
				}
				else {
					$group = $this->group($group_id)->result();
					if(!empty($group)){
						$group_name = $group[0]->name;
						$this->_cache_groups[$group_id] = $group_name;
					}					
				}
				if($group_name){
					$this->_cache_user_in_group[$user_id][$group_id] = $group_name;
				}
				

				// Return the number of groups added
				$return += 1;
			}
		}

		return $return;
	}

	/**
	 * remove_from_group
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remove_from_group($group_ids=false, $user_id=false)
	{
		$this->trigger_events('remove_from_group');

		// user id is required
		if(empty($user_id))
		{
			return FALSE;
		}

		// if group id(s) are passed remove user from the group(s)
		if( ! empty($group_ids))
		{
			if(!is_array($group_ids))
			{
				$group_ids = array($group_ids);
			}

			foreach($group_ids as $group_id)
			{
				$this->db->delete($this->tables['users_groups'], array($this->join['groups'] => (int)$group_id, $this->join['users'] => (int)$user_id));
				if (isset($this->_cache_user_in_group[$user_id]) && isset($this->_cache_user_in_group[$user_id][$group_id]))
				{
					unset($this->_cache_user_in_group[$user_id][$group_id]);
				}
			}

			$return = TRUE;
		}
		// otherwise remove user from all groups
		else
		{
			if ($return = $this->db->delete($this->tables['users_groups'], array($this->join['users'] => (int)$user_id))) {
				$this->_cache_user_in_group[$user_id] = array();
			}
		}
		return $return;
	}

	/**
	 * groups
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function groups()
	{
		$this->select_all_secure('groups');
		$this->trigger_events('groups');

		// run each where that was passed
		if (isset($this->_ion_where) && !empty($this->_ion_where))
		{
			foreach ($this->_ion_where as $where)
			{
				$this->db->where($where);
			}
			$this->_ion_where = array();
		}

		if (isset($this->_ion_limit) && isset($this->_ion_offset))
		{
			$this->db->limit($this->_ion_limit, $this->_ion_offset);

			$this->_ion_limit  = NULL;
			$this->_ion_offset = NULL;
		}
		else if (isset($this->_ion_limit))
		{
			$this->db->limit($this->_ion_limit);

			$this->_ion_limit  = NULL;
		}

		// set the order
		if (isset($this->_ion_order_by) && isset($this->_ion_order))
		{
			$this->db->order_by($this->_ion_order_by, $this->_ion_order);
		}

		$this->response = $this->db->get($this->tables['groups']);

		return $this;
	}

	/**
	 * group
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function group($id = NULL)
	{
		$this->trigger_events('group');

		if (isset($id))
		{
			$this->where($this->tables['groups'].'.id', $id);
		}

		$this->limit(1);
		$this->order_by('id', 'desc');

		return $this->groups();
	}

	/**
	 * update
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function update($id,$data=array(),$skip_validation = FALSE)
	{
		$this->trigger_events('pre_update_user');

		$user = $this->user($id)->row();

		$this->db->trans_begin();

		if (array_key_exists($this->identity_column, $data) && $this->identity_check($data[$this->identity_column]) && $user->{$this->identity_column} !== $data[$this->identity_column])
		{
			$this->db->trans_rollback();
			$this->set_error('account_creation_duplicate_identity');

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');

			return FALSE;
		}

		// Filter the data passed
		$data = $this->_filter_data($this->tables['users'], $data);

		if (array_key_exists($this->identity_column, $data) || array_key_exists('password', $data) || array_key_exists('email', $data))
		{
			if (array_key_exists('password', $data))
			{
				if( ! empty($data['password']))
				{
					$data['password'] = $this->hash_password($data['password'], $user->salt);
				}
				else
				{
					// unset password so it doesn't effect database entry if no password passed
					unset($data['password']);
				}
			}
		}

		$this->trigger_events('extra_where');
		$this->update_secure_data($user->id,$this->tables['users'], $data);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			$this->trigger_events(array('post_update_user', 'post_update_user_unsuccessful'));
			$this->set_error('update_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_update_user', 'post_update_user_successful'));
		$this->set_message('update_successful');
		return TRUE;
	}

	/**
	* delete_user
	*
	* @return bool
	* @author Phil Sturgeon
	**/
	
	/**
	 * update_last_login
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function update_last_login($id)
	{
		$this->trigger_events('update_last_login');

		$this->load->helper('date');

		$this->trigger_events('extra_where');

		$this->update_secure_data($id,$this->tables['users'], array('last_login' => time()));

		return $this->db->affected_rows() == 1;
	}

	/**
	 * set_lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function set_lang($lang = 'en')
	{
		$this->trigger_events('set_lang');

		// if the user_expire is set to zero we'll set the expiration two years from now.
		if($this->config->item('user_expire', 'ion_auth') === 0)
		{
			$expire = (60*60*24*365*2);
		}
		// otherwise use what is set
		else
		{
			$expire = $this->config->item('user_expire', 'ion_auth');
		}

		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $expire
		));

		return TRUE;
	}

	/**
	 * set_session
	 *
	 * @return bool
	 * @author jrmadsen67
	 **/
	public function set_session($user)
	{

		$this->trigger_events('pre_set_session');

		$session_data = array(
		    'identity'             => $user->{$this->identity_column},
		    $this->identity_column             => $user->{$this->identity_column},
		    'email'                => $user->email,
		    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
		    'old_last_login'       => $user->last_login
		);

		$this->session->set_userdata($session_data);

		$this->trigger_events('post_set_session');

		return TRUE;
	}

	/**
	 * remember_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function remember_user($id)
	{
		$this->trigger_events('pre_remember_user');

		if (!$id)
		{
			return FALSE;
		}

		$user = $this->user($id)->row();

		$salt = $this->salt();

		$this->update_secure_data($id,$this->tables['users'], array('remember_code' => $salt));

		if ($this->db->affected_rows() > -1)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'ion_auth') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'ion_auth');
			}

			set_cookie(array(
			    'name'   => $this->config->item('identity_cookie_name', 'ion_auth'),
			    'value'  => $user->{$this->identity_column},
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => $this->config->item('remember_cookie_name', 'ion_auth'),
			    'value'  => $salt,
			    'expire' => $expire
			));

			$this->trigger_events(array('post_remember_user', 'remember_user_successful'));
			return TRUE;
		}

		$this->trigger_events(array('post_remember_user', 'remember_user_unsuccessful'));
		return FALSE;
	}

	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function login_remembered_user()
    {
        $this->trigger_events('pre_login_remembered_user');

        //check for valid data
        if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity')))
        {
            $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
            return FALSE;
        }

         //get the user
        $this->trigger_events('extra_where');
        $this->select_secure($this->identity_column);
        $this->db->select('id');
        $this->db->where($this->dx($this->identity_column) ."='". get_cookie('identity'). "' ", NULL, FALSE);
        $this->db->where($this->dx('remember_code')."='". get_cookie('remember_code'). "' ", NULL, FALSE);
        $this->db->limit(1);
        $query = $this->db->get($this->tables['users']);

        //if the user was found, sign them in
        if ($query->num_rows() == 1)
        {
            $user = $query->row();

            $this->update_last_login($user->id);

            $session_data = array(
                $this->identity_column => $user->{$this->identity_column},
                'id' => $user->id, //kept for backwards compatibility
                'user_id' => $user->id, //everyone likes to overwrite id so we'll use user_id
            );

            $this->session->set_userdata($session_data);


            //extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'ion_auth'))
            {
                $this->remember_user($user->id);
            }

            $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_successful'));
            return TRUE;
        }

        $this->trigger_events(array('post_login_remembered_user', 'post_login_remembered_user_unsuccessful'));
        return FALSE;
    }


	/**
	 * create_group
	 *
	 * @author aditya menon
	*/
	public function create_group($group_name = FALSE, $group_description = '', $additional_data = array())
	{
		// bail if the group name was not passed
		if(!$group_name)
		{
			$this->set_error('group_name_required');
			return FALSE;
		}

		// bail if the group name already exists
		$existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->num_rows();
		if($existing_group !== 0)
		{
			$this->set_error('group_already_exists');
			return FALSE;
		}

		$data = array('name'=>$group_name,'description'=>$group_description);

		// filter out any data passed that doesnt have a matching column in the groups table
		// and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);

		$this->trigger_events('extra_group_set');

		// insert the new group
		$group_id = $this->db->insert_secure($this->tables['groups'], $data);

		// report success
		$this->set_message('group_creation_successful');
		// return the brand new group id
		return $group_id;
	}

	/**
	 * update_group
	 *
	 * @return bool
	 * @author aditya menon
	 **/
	public function update_group($group_id = FALSE, $group_name = FALSE, $additional_data = array())
	{
		if (empty($group_id)) return FALSE;

		$data = array();

		if (!empty($group_name))
		{
			// we are changing the name, so do some checks

			// bail if the group name already exists
			$existing_group = $this->db->get_where($this->tables['groups'], array('name' => $group_name))->row();
			if(isset($existing_group->id) && $existing_group->id != $group_id)
			{
				$this->set_error('group_already_exists');
				return FALSE;
			}

			$data['name'] = $group_name;
		}

		// restrict change of name of the admin group
        $group = $this->db->get_where($this->tables['groups'], array('id' => $group_id))->row();
        if($this->config->item('admin_group', 'ion_auth') === $group->name && $group_name !== $group->name)
        {
            $this->set_error('group_name_admin_not_alter');
            return FALSE;
        }


		// IMPORTANT!! Third parameter was string type $description; this following code is to maintain backward compatibility
		// New projects should work with 3rd param as array
		if (is_string($additional_data)) $additional_data = array('description' => $additional_data);


		// filter out any data passed that doesnt have a matching column in the groups table
		// and merge the set group data and the additional data
		if (!empty($additional_data)) $data = array_merge($this->_filter_data($this->tables['groups'], $additional_data), $data);


		$this->update_secure_data($group_id,$this->tables['groups'], $data);

		$this->set_message('group_update_successful');

		return TRUE;
	}

	/**
	* delete_group
	*
	* @return bool
	* @author aditya menon
	**/
	public function delete_group($group_id = FALSE)
	{
		// bail if mandatory param not set
		if(!$group_id || empty($group_id))
		{
			return FALSE;
		}
		$group = $this->group($group_id)->row();
		if($group->name == $this->config->item('admin_group', 'ion_auth'))
		{
			$this->trigger_events(array('post_delete_group', 'post_delete_group_notallowed'));
			$this->set_error('group_delete_notallowed');
			return FALSE;
		}

		$this->trigger_events('pre_delete_group');

		$this->db->trans_begin();

		// remove all users from this group
		$this->db->delete($this->tables['users_groups'], array($this->join['groups'] => $group_id));
		// remove the group itself
		$this->db->delete($this->tables['groups'], array('id' => $group_id));

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->trigger_events(array('post_delete_group', 'post_delete_group_unsuccessful'));
			$this->set_error('group_delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_delete_group', 'post_delete_group_successful'));
		$this->set_message('group_delete_successful');
		return TRUE;
	}

	public function set_hook($event, $name, $class, $method, $arguments)
	{
		$this->_ion_hooks->{$event}[$name] = new stdClass;
		$this->_ion_hooks->{$event}[$name]->class     = $class;
		$this->_ion_hooks->{$event}[$name]->method    = $method;
		$this->_ion_hooks->{$event}[$name]->arguments = $arguments;
	}

	public function remove_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]))
		{
			unset($this->_ion_hooks->{$event}[$name]);
		}
	}

	public function remove_hooks($event)
	{
		if (isset($this->_ion_hooks->$event))
		{
			unset($this->_ion_hooks->$event);
		}
	}

	protected function _call_hook($event, $name)
	{
		if (isset($this->_ion_hooks->{$event}[$name]) && method_exists($this->_ion_hooks->{$event}[$name]->class, $this->_ion_hooks->{$event}[$name]->method))
		{
			$hook = $this->_ion_hooks->{$event}[$name];

			return call_user_func_array(array($hook->class, $hook->method), $hook->arguments);
		}

		return FALSE;
	}

	public function trigger_events($events)
	{
		if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				$this->trigger_events($event);
			}
		}
		else
		{
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
			{
				foreach ($this->_ion_hooks->$events as $name => $hook)
				{
					$this->_call_hook($events, $name);
				}
			}
		}
	}

	/**
	 * set_message_delimiters
	 *
	 * Set the message delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message_delimiters($start_delimiter, $end_delimiter)
	{
		$this->message_start_delimiter = $start_delimiter;
		$this->message_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_error_delimiters
	 *
	 * Set the error delimiters
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error_delimiters($start_delimiter, $end_delimiter)
	{
		$this->error_start_delimiter = $start_delimiter;
		$this->error_end_delimiter   = $end_delimiter;

		return TRUE;
	}

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}



	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}


	/**
	 * clear_messages
	 *
	 * Clear messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function clear_messages()
	{
		$this->messages = array();

		return TRUE;
	}


	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
		}

		return $_output;
	}

	/**
	 * errors as array
	 *
	 * Get the error messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->errors as $error)
			{
				$errorLang = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}


	/**
	 * clear_errors
	 *
	 * Clear Errors
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function clear_errors()
	{
		$this->errors = array();

		return TRUE;
	}



	protected function _filter_data($table, $data)
	{
		$filtered_data = array();
		$columns = $this->db->list_fields($table);

		if (is_array($data))
		{
			foreach ($columns as $column)
			{
				if (array_key_exists($column, $data))
					$filtered_data[$column] = $data[$column];
			}
		}

		return $filtered_data;
	}

	protected function _prepare_ip($ip_address) {
		// just return the string IP address now for better compatibility
		return $ip_address;
	}


	public function get_user($id=0)
	{
		$this->select_all_secure('users');
		$this->db->select('*');
		if($id)
		{
			$this->db->where('id',$id);
			return $this->db->get('users')->row();
		}else
		{
			return $this->db->get('users')->result();
		}
	}	

	public function is_in_group($user_id, $group_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('group_id', $group_id);
        $groups = $this->db->get('users_groups')->result();

        return count(($groups)) ? TRUE : FALSE;
    }

	function my_users($groups)
    {
        $this->select_all_secure($this->tables['users']);
        $this->select(array($this->dx('id') . " as user_id "), FALSE);
         $this->db->distinct();
                $this->db->join(
                        $this->tables['users_groups'], $this->tables['users_groups'] . '.user_id = ' . $this->tables['users'] . '.id', 'inner'
                );

                $this->db->where_in($this->tables['users_groups'] . '.group_id', $groups);

           $this->response = $this->db->get($this->tables['users']);

        return $this;
    }

    function get_group_by_name($name='')
    {
    	//$this->select_all_secure('groups');
    	$this->db->select('id');
    	$this->db->where($this->dx('name').'="'.$name.'"',NULL,FALSE);

    	return $this->db->get('groups')->row();
    }

    function get_user_by_email($email = ''){
    	if($email){
	    	$this->select_all_secure('users');
			$this->db->where($this->dx('email').' = "'.$email.'"',NULL,FALSE);
			return $this->db->get('users')->row();
		}
    }

    function get_user_by_phone($phone = ''){
    	if($phone){
	    	$this->select_all_secure('users');
			//$this->db->where($this->dx('phone').' = "'.$phone.'"',NULL,FALSE);
			$this->db->where('('.$this->dx('users.phone').'="'.$phone.'" OR '.$this->dx('users.phone').' = "'.valid_phone($phone).'"  OR '.$this->dx('users.phone').' = "+'.valid_phone($phone).'" OR '.$this->dx('users.phone').' ="+'.$phone.'" OR '.$this->dx('users.phone').' ="'.str_replace('+','', $phone).'" )',NULL,FALSE);
			return $this->db->get('users')->row();
		}
    }

    function get_user_by_identity($identity = ''){
		$this->select_all_secure($this->tables['users']);
		if(valid_phone($identity)){
			$this->db->where('('.$this->dx('users.phone').'="'.$identity.'" OR '.$this->dx('users.phone').' = "'.valid_phone($identity).'"  OR '.$this->dx('users.phone').' = "+'.valid_phone($identity).'" OR '.$this->dx('users.phone').' ="+'.$identity.'" OR '.$this->dx('users.phone').' ="'.str_replace('+','', $identity).'" )',NULL,FALSE);
			return $this->db->get('users')->row();
		}else{
			// $this->db->where("CONVERT(".$this->dx('users.email')." using 'latin1') = '".$identity."'",NULL,FALSE);
			$this->db->where($this->dx('users.email').'="'.trim($identity).'"',NULL,FALSE);
			$user = $this->db->get('users')->row();
			
			
			return $user;
		}
    }

    public function delete_user($id)
	{
		$this->trigger_events('pre_delete_user');

		$this->db->trans_begin();

		// remove user from groups
		$this->remove_from_group(NULL, $id);

		// delete user from users table should be placed after remove from group
		$this->db->delete($this->tables['users'], array('id' => $id));

		// if user does not exist in database then it returns FALSE else removes the user from groups
		if ($this->db->affected_rows() == 0)
		{
		    return FALSE;
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
			$this->set_error('delete_unsuccessful');
			return FALSE;
		}

		$this->db->trans_commit();

		$this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
		$this->set_message('delete_successful');
		return TRUE;
	}

	function generate_one_time_password($one_time_password = ''){
		$salt = $this->store_salt ? $this->salt() : FALSE;
		$password = $this->hash_password($one_time_password, $salt);
		return $password;
	}

    
}
