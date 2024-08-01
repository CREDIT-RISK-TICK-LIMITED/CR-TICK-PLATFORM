<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class Dbfix extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dbfix_m');
        $this->load->model('migrate_m');
    }

    function index()
    {
        $this->dbfix_m->add_column('loans', array(
            'loan_type_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('deposits', array(
            'is_admin' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_result_status' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_result_description' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loans', array(
            'transaction_alert_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loans', array(
            'loan_application_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'declined_by' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'approval_code' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('member_additional_fields', array(
            'is_hidden' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'reference_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'declined_by' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'is_disbursed' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'disbursed_on' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_charges' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_receipt_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'declined_on' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_failed_error_message' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('safaricomb2btransactions',
        array(
            'credit_account_balance'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricomc2bpayments',
			array(
				'is_reversed'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomc2bpayments',
			array(
				'reversed_on'=>array(
					'type'=> 'blob',
				),
			)
		);
        $this->dbfix_m->add_column('safaricomc2bpayments',
			array(
				'loan_id'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomc2bpayments',
			array(
				'reversal_transaction_id'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomc2bpayments',
			array(
				'reversal_charge'=>array(
					'type'=> 'blob',
				),
			)
		);
        $this->dbfix_m->add_column('safaricomb2crequest',
			array(
				'account_id'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomb2crequest',
			array(
				'request_reconcilled'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomb2crequest',
			array(
				'callback_sent'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('safaricomb2crequest',
			array(
				'reference_number'=>array(
					'type'=> 'blob',
				),
			)
		);
        
        $this->dbfix_m->add_column('safaricomb2crequest',
        array(
            'account_id'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('equity_bank_configurations',array(
        'username'=>array(
            'type'=> 'blob',
        ))
    );
    $this->dbfix_m->add_column('safaricomb2crequest',
        array(
            'request_reconcilled'=>array(
                'type'=> 'blob',
            ),
        )
    );

    $this->dbfix_m->add_column('safaricomb2crequest',
        array(
            'callback_sent'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricomb2crequest',
        array(
            'disburse_charge'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricomb2crequest',
    array(
        'is_reversed'=>array(
            'type'=> 'blob',
        ),
    )
);
    $this->dbfix_m->add_column('safaricom_configurations',
        array(
            'consumer_key'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricom_configurations',
    array(
        'encrypted_user_password'=>array(
            'type'=> 'blob',
        ),
    )
);
    $this->dbfix_m->add_column('safaricom_configurations',
        array(
            'consumer_secret'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricom_configurations',
    array(
        'endpoint'=>array(
            'type'=> 'blob',
        ),
    )
);
    $this->dbfix_m->add_column('safaricomb2crequest',
        array(
            'reference_number'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('safaricomb2crequest',
    array(
        'withdrawal_request_id'=>array(
            'type'=> 'blob',
        ),
    )
);
    $this->dbfix_m->add_column('safaricomb2crequest',
    array(
        'test_environment'=>array(
            'type'=> 'blob',
        ),
    )
);
$this->dbfix_m->add_column('safaricomb2crequest',
array(
    'command_id'=>array(
        'type'=> 'blob',
    ),
)
);

        $this->dbfix_m->add_column('loan_applications', array(
            'reference_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('payment_transactions',
        array(
            'checkout_request_id'=>array(
                'type'=> 'blob',
            ),
        )
    );

    $this->dbfix_m->add_column('payment_transactions',
        array(
            'shortcode'=>array(
                'type'=> 'blob',
            ),
        )
    );

    $this->dbfix_m->add_column('payment_transactions',
        array(
            'merchant_request_id'=>array(
                'type'=> 'blob',
            ),
        )
    );

    $this->dbfix_m->add_column('payment_transactions',
        array(
            'transaction_date'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('payment_transactions',
    array(
        'unique_reference_number'=>array(
            'type'=> 'blob',
        ),
    )
);
$this->db->query("CREATE TABLE IF NOT EXISTS `accounts` (
    `id` int(11) NOT NULL,
    `account_name` blob DEFAULT NULL,
    `account_number` blob DEFAULT NULL,
    `reference_number` blob DEFAULT NULL,
    `balance` blob DEFAULT NULL,
    `currency_id` blob DEFAULT NULL,
    `is_closed` blob DEFAULT NULL,
    `active` blob DEFAULT NULL,
    `created_on` blob DEFAULT NULL,
    `created_by` blob DEFAULT NULL,
    `modified_on` blob DEFAULT NULL,
    `modified_by` blob DEFAULT NULL
  )");

    $this->dbfix_m->add_column('payment_transactions',
        array(
            'transaction_id'=>array(
                'type'=> 'blob',
            ),
        )
    );

    $this->dbfix_m->add_column('payment_transactions',
        array(
            'narration'=>array(
                'type'=> 'blob',
            ),
        )
    );
    $this->dbfix_m->add_column('payment_transactions',
    array(
        'disburse_charge'=>array(
            'type'=> 'blob',
        ),
    )
);	 
$this->dbfix_m->add_column('payment_transactions',
			array(
				'bank_account_number'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('payment_transactions',
			array(
				'source_account_number'=>array(
					'type'=> 'blob',
				),
			)
		);

		$this->dbfix_m->add_column('payment_transactions',
			array(
				'destination_reference_number'=>array(
					'type'=> 'blob',
				),
			)
		);

        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_failed_error_message' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('withdrawal_requests', array(
            'is_disbursed' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('member_checkoff_contribution_amount_pairings', array(
            'active' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_signatory_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'disbursement_fail_reason' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'declined_by' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'account_set_by' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'loan_type_is_admin' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_applications', array(
            'auto_disburse' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'status' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'account_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'comments' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('recipients', array(
            'type' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('notifications', array(
            'loan_application_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_applications', array(
            'is_declined' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_guarantorship_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_guarantorship_requests', array(
            'guarantor_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_guarantorship_requests', array(
            'loan_applicant_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_signatory_requests', array(
            'loan_applicant_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_signatory_requests', array(
            'signatory_member_id' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('banks', array(
            'wallet' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'checkoff_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('transaction_statements', array(
            'checkoff_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('deposits', array(
            'checkoff_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('contributions', array(
            'enable_checkoff' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'declined_on' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'request_expiry_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('online_payment_requests', array(
            'transaction_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('online_payment_requests', array(
            'account_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('online_payment_requests', array(
            'is_reconcilled' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('online_payment_requests', array(
            'transaction_alert_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'withdrawal_requests' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_member_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_phone_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'declined_on' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'decline_reason' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'approved_on' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'is_disbursed' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'reference_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_status' => array(
                    'type' => 'blob',
                )
            )
        );
        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursed_on' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_charges' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_result_status' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_result_description' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_failed_error_message' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'disbursement_receipt_number' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'withdrawal_requests',
            array(
                'is_reconcilled' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column('investment_groups', array(
            'next_monthly_contribution_statement_request_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('application_settings', array(
            'application_phone' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('application_settings', array(
            'application_email' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('investment_groups', array(
            'theme' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'online_banking_enabled' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_member_information_privacy' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'member_listing_order_by' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_merge_transaction_alerts' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('statements', array(
            'statement_type' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'bank_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'account_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'account_name' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('menus', array(
            'description' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'description' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'enabled_or_disabled' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'enabled_disabled_feature' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'contextual_help_content' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'size' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('bank_menus', array(
            'enable_menu_for' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_menus', array(
            'help_url' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('contributions', array(
            'category' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('menus', array(
            'contextual_help_content' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('menus', array(
            'enable_menu_for' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('menus', array(
            'enabled_or_disabled' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('menus', array(
            'enabled_disabled_feature' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_offer_loans' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_setup_position' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_setup_status' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_type' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_is_registered' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'group_registration_certificate_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'allow_members_request_loan' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column(
            'settings',
            array(
                'default_language_id' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column('members', array(
            'suspension_initiated' => array(
                'type' => 'blob',
            ),
            'suspension_initiated_by' => array(
                'type' => 'blob',
            ),
            'suspension_initiated_on' => array(
                'type' => 'blob',
            ),
            'suspension_reason' => array(
                'type' => 'blob',
            ),
            'old_id' => array(
                'type' => 'blob',
            ),
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'disable_notifications' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('online_payment_requests', array(
            'transaction_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('online_payment_requests', array(
            'account_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('online_payment_requests', array(
            'is_reconcilled' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('online_payment_requests', array(
            'transaction_alert_id' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('contribution_transfers', array(
            'share_transfer_recipient_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('contributions', array(
            'enable_deposit_statement_display' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('members', array(
            'place_of_work' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'share_transfer_giver_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'share_transfer_recipient_member_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_paybill_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_paybill_account_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'member_to_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'member_from_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column(
            'loan_signatory_requests',
            array(
                'loan_signatory_progress_status' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'loan_signatory_requests',
            array(
                'is_declined' => array(
                    'type' => 'blob',
                )
            )
        );

        $this->dbfix_m->add_column(
            'email_queue',
            array(
                'email_type' => array(
                    'type' => 'blob',
                )
            )
        );

        $ignore_tables = array('users_groups');

        $tables = $this->db->list_tables();
        foreach ($tables as $key => $table) {
            if (!in_array($table, $ignore_tables)) {
                if ($this->db->field_exists('group_id', $table)) {
                    // if($this->migrate_m->unencrypt_group_id($table)){
                    //     die($table);
                    // }
                    $this->dbfix_m->modify_column(
                        $table,
                        array(
                            'group_id' => array(
                                'name' => 'group_id',
                                'type' => 'int',
                            )
                        )
                    );
                }
            }
        }

        $this->dbfix_m->add_column('banks', array(
            'default_bank' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'is_disbursement_declined' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_account_number' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_bank_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column(
            'notifications ',
            array(
                'loan_application_id' => array(
                    'type' => 'blob',
                ),
            )
        );
        $this->dbfix_m->add_column('loan_types', array(
            'disable_automatic_loan_processing_income' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_types', array(
            'grace_period_date' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_types', array(
            'enable_automatic_disbursements' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_types', array(
            'limit_to_one_loan_application' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('safaricomstkpushrequests', array(
            'loan_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('safaricomstkpushrequests', array(
            'transaction_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('safaricomstkpushrequests', array(
            'merchant_request_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('safaricomstkpushrequests', array(
            'organization_balance' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('safaricomstkpushrequests', array(
            'transaction_date' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loans', array(
            'enable_automatic_disbursements' => array(
                'type' => 'blob'
            )
        ));
        
        $this->dbfix_m->add_column('loan_types', array(
            'loan_processing_recovery_on' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('users', array(
            'first_time_login_status' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('users', array(
            'loan_limit' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('users', array(
            'prompt_to_change_password' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_signatory_requests', array(
            'loan_type_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('banks', array(
            'country_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_branches', array(
            'country_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('saccos', array(
            'country_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sacco_branches', array(
            'country_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'is_admin' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'is_admin' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'referral_code' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawals', array(
            'loan_type_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('members', array(
            'invitation_sent' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'bulk_invitation_sent' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('user_demo_requests', array(
            'user_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('user_demo_requests', array(
            'middle_name' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'bank_account_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'is_manually_disbursed' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_requests', array(
            'transfer_to' => array(
                'type' => 'blob'
            )
        ));



        $this->dbfix_m->add_column('users', array(
            'language_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'is_validated' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'is_validated' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sms', array(
            'reference_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'join_code' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'email_join_code' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('mobile_money_providers', array(
            'country_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'account_currency' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'cif' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('recipients', array(
            'customer_name' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'account_currency_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'expiry_time' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'login_validated' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'otp_expiry_time' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('users', array(
            'password_check' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'session_timeout' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('settings', array(
            'application_phone' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('settings', array(
            'application_email' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'is_otp_verified' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sms_templates', array(
            'language_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'account_to_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('settings', array(
            'allow_self_onboarding' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'show_system_account_balance' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'enable_online_disbursement' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('bank_accounts', array(
            'actual_balance' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'recipient_id' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('members', array(
            'suspension_reason' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'last_group_activity' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('user_pin_access_token', array(
            'language_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'billing_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'is_email_sent' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_invoices', array(
            'disable_interest' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('loan_invoices', array(
            'processing_fee' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('safaricom_stk_push_requests', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('sms', array(
            'category' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_e_wallet' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('money_market_investments', array(
            'total_cash_in_amount' => array(
                'type' => 'blob'
            )
        ));


        $this->dbfix_m->add_column('bank_accounts', array(
            'notification_keys' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'trial_days_end_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'statement_send_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'next_monthly_contribution_statement_send_date' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_auto_reconcile' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'remove_duplicates' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'arrears' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'has_transaction_alerts' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'enable_email_transaction_alerts_to_group_account_managers' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'enable_sms_transaction_alerts_to_group_account_managers' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'enable_email_transaction_alerts_to_managers' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'enable_sms_transaction_alerts_to_managers' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('contributions', array(
            'hidden_in_statement' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('contributions', array(
            'is_closed' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('group_bank_branch_pairings', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'outstanding_loan_balance_fine_one_off_percentage_fine_rate' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'outstanding_loan_balance_fine_one_off_fine_type' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'outstanding_loan_balance_fine_one_off_percentage_fine_on' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'enable_loan_approvals' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sms', array(
            'status_code' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sms', array(
            'status' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('sms', array(
            'cost' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('statements', array(
            'unencrypted_group_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'enable_two_factor_auth' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('settings', array(
            'enable_staff_to_create_loans' => array(
                'type' => 'blob'
            )
        ));
        $this->dbfix_m->add_column('settings', array(
            'activate_billing' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'enable_google_recaptcha' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'entity_name' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('settings', array(
            'activate_login_attempts' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('billing_packages', array(
            'enable_extra_member_charge' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('billing_packages', array(
            'monthly_pay_over' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('billing_packages', array(
            'quarterly_pay_over' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('billing_packages', array(
            'annual_pay_over' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('billing_packages', array(
            'members_limit' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'linkage_type' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_accounts', array(
            'is_linked' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'join_code' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_edit_member_profile' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_compose_sms' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_compose_email' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_add_members_manually' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('investment_groups', array(
            'enable_import_members_manually' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('group_membership_requests', array(
            'is_deleted' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('bank_account_signatories', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('group_account_managers', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_types', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_approval_requests', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'old_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'account_number' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'account_name' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'disbursement_channel' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'bank_id' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('withdrawal_requests', array(
            'welfare_recipient' => array(
                'type' => 'blob'
            )
        ));

        $this->dbfix_m->add_column('loan_invoices', array(
            'loan_fine_type' => array(
                'type' => 'blob'
            )
        ));
       
        $this->dbfix_m->add_column('loan_types', array(
            'disable_fines_past_loan_date' => array(
                'type' => 'blob'
            )
        ));
       
        $this->dbfix_m->add_column('loans', array(
            'disable_fines_past_loan_date' => array(
                'type' => 'blob'
            )
        ));
        echo ucfirst($_SERVER['HTTP_HOST']) . " DB Fixed\n<br/>";
    }
}
