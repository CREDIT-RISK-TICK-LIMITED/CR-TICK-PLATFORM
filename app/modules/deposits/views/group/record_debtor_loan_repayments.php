<?php echo form_open($this->uri->uri_string(),'class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed form_submit loan_repayments_form m-form--state" id="loan_repayments_form" role="form"'); ?>
    <span class="error"></span>
    <div class="table-responsive">
        <table class="table table-condensed table-multiple-items multiple_payment_entries">
            <thead>
                <tr> 
                    <th width="1%">
                        #
                    </th>
                    <th width="11%">
                        <?php echo translate('Date');?>
                        <span class='required'>*</span>
                    </th>
                    <th width="16%">
                        <?php echo translate('Debtor');?>
                        <span class='required'>*</span>
                    </th>
                    <th width="17%">
                        <?php echo translate('Loan');?>
                        <span class='required'>*</span>
                    </th>
                    <th width="17%">
                        <?php echo translate('Account');?>
                        <span class='required'>*</span>
                    </th>
                    <th width="13%">
                        <?php echo translate('Channel');?>
                        <span class='required'>*</span>
                    </th>
                    <th width="12%">
                        <?php echo translate('Amount');?>
                        <span class='required'>*</span>
                    </th>                    
                    <th width="7%">
                        <?php echo translate('Alerts');?>
                    </th>
                    <th width="3%">
                       &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody id='append-place-holder'>
                <tr>
                    <th scope="row" class="count">
                        1
                    </th>
                    <td>
                        <?php echo form_input('deposit_dates[0]',timestamp_to_datepicker(time()),' class="form-control input-sm m-input deposit_date date-picker" readonly="readonly" data-date-format="dd-mm-yyyy" data-date-viewmode="years" data-date-end-date="0d" data-date-start-date="-20y" autocomplete="off" ');?>
                    </td>
                    <td>
                        <span class="m-select2-sm m-input--air">
                             <?php echo form_dropdown('debtors[0]',array(''=>'Select debtor')+$this->active_group_debtor_options+array('0'=>"Add debtor"),$this->input->get_post('debtor_id'),' class="form-control m-input m-select2 debtor" ');?>
                         </span>
                    </td>
                    <td>
                        <span class="m-select2-sm m-input--air change-loan">
                            <?php echo form_dropdown('loans[0]',array(''=>'Select debtor First')+$loans_options,'',' class="form-control m-input m-select2 loan" readonly ');?>
                        </span>
                        <br>
                        <a href="javascript:;" class=" btn btn-sm m-btn--square btn-default btn-xs inline-table-button add_deposit_description" id="" style="margin-top:2px; width: 100%;">
                            <i class="la la-plus"></i>
                            <span class="hidden-380">
                            <?php echo translate('Add description');?>
                            </span>
                        </a>
                        <div class="margin-top-5 deposit_description" data-original-title="" data-container="body" style="display:none;"><i class="" ></i>
                            <?php 
                                $textarea = array(
                                    'name' => 'deposit_descriptions[0]',
                                    'id' => '',
                                    'value' => '',
                                    'cols' => 25,
                                    'rows' => 5,
                                    'maxlength '=> '',
                                    'class' => 'form-control',
                                    'placeholder' => ''
                                ); 
                                echo form_textarea($textarea);

                            ?>
                        </div>
                    </td>
                    <td>
                        <span class="m-select2-sm m-input--air">
                            <?php echo form_dropdown('accounts[0]',array(''=>'Select account')+$account_options,'',' class="form-control m-input m-select2 account" ');?>
                        </span>
                    </td>
                    <td>
                        <span class="m-select2-sm m-input--air">
                            <?php echo form_dropdown('deposit_methods[0]',array(''=>'Select deposit method')+$deposit_method_options,'',' class="form-control m-input m-select2 deposit_method" ');?>
                        </span>
                    </td>
                    <td>
                        <?php echo form_input('amounts[0]','',' class="form-control input-sm amount currency text-right" ');?>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--brand" data-toggle="m-tooltip" title="" data-original-title="Send SMS notification">
                                    <?php echo form_checkbox('send_sms_notification[0]',1,FALSE,' class = "send_sms_notification" '); ?>
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-6 col-sm-6" style="padding-left: 5px;">
                                <label class="m-checkbox m-checkbox--solid m-checkbox--brand" data-toggle="m-tooltip" title="" data-original-title="Send Email notification">
                                    <?php echo form_checkbox('send_email_notification[0]',1,FALSE,' class = "send_email_notification" '); ?>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </td>
                    <td class="text-right">
                        <a href='javascript:;' class="remove-line">
                            <i class="text-danger la la-trash" style="margin-top:25%;"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan=6>
                        <?php echo translate('Totals');?>
                    </td>
                    <td class="text-right total-amount"><?php echo number_to_currency();?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-default btn-sm add-new-line" id="add-new-line">
                <i class="la la-plus"></i><?php echo translate('Add New Payment Line');?>
            </a>
        </div>
    </div>

    <div class="m-form__actions m-form__actions p-0 pt-5 m--margin-top-10">                            
        <div class="row">
            <div class="col-md-12">
                <span class="float-lg-right float-md-left float-sm-left float-xl-right">
                    <button class="btn btn-primary m-btn m-btn--custom m-btn--icon btn-sm submit_form_button" id="submit_form_button" type="submit">
                       <?php echo translate('Record Loan Repayments');?>                              
                    </button>
                    &nbsp;&nbsp;
                    <button class="btn btn-metal m-btn m-btn--custom m-btn--icon btn-sm cancel_form" type="button" id="">
                        Cancel                              
                    </button>
                </span>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>

<div class="d-none" id='append-new-line'>
    <table>
        <tbody>
            <tr>
                <th scope="row" class="count">
                    1
                </th>
                <td>
                    <?php echo form_input('deposit_dates[0]',timestamp_to_datepicker(time()),' class="form-control input-sm m-input deposit_date date-picker" readonly="readonly" data-date-format="dd-mm-yyyy" data-date-viewmode="years" data-date-end-date="0d" data-date-start-date="-20y" autocomplete="off" ');?>
                </td>
                <td>
                    <span class="m-select2-sm m-input--air">
                         <?php echo form_dropdown('debtors[0]',array(''=>'Select debtor')+$this->active_group_debtor_options+array('0'=>"Add debtor"),'',' class="form-control m-input m-select2-append debtor" ');?>
                     </span>
                </td>
                <td>
                   <span class="m-select2-sm m-input--air change-loan">
                        <?php echo form_dropdown('loans[0]',array(''=>'Select debtor First')+$loans_options,'',' class="form-control m-input m-select2-append loan" readonly ');?>
                    </span>
                    <br>
                    <a href="javascript:;" class=" btn btn-sm m-btn--square btn-default btn-xs inline-table-button add_deposit_description" id="" style="margin-top:2px; width: 100%;">
                        <i class="la la-plus"></i>
                        <span class="hidden-380">
                            <?php echo translate('Add description');?>
                        </span>
                    </a>
                    <div class="deposit_description" data-original-title="" data-container="body" style="display:none;"><i class="" ></i>
                        <?php 
                            $textarea = array(
                                'name' => 'deposit_descriptions[0]',
                                'id' => '',
                                'value' => '',
                                'cols' => 25,
                                'rows' => 5,
                                'maxlength '=> '',
                                'class' => 'form-control',
                                'placeholder' => ''
                            ); 
                            echo form_textarea($textarea);
                        ?>
                    </div>
                </td>
                <td>
                    <span class="m-select2-sm m-input--air">
                        <?php echo form_dropdown('accounts[0]',array(''=>'Select account')+$account_options,'',' class="form-control m-input m-select2-append account" ');?>
                    </span>
                </td>
                <td>
                    <span class="m-select2-sm m-input--air">
                        <?php echo form_dropdown('deposit_methods[0]',array(''=>'Select deposit method')+$deposit_method_options,'',' class="form-control m-input m-select2-append deposit_method" ');?>
                    </span>
                </td>
                <td>
                    <?php echo form_input('amounts[0]','',' class="form-control input-sm amount currency text-right" ');?>
                </td>
                <td>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <label class="m-checkbox m-checkbox--solid m-checkbox--brand" data-toggle="m-tooltip" title="" data-original-title="Send SMS notification">
                                <?php echo form_checkbox('send_sms_notification[0]',1,FALSE,' class = "send_sms_notification" '); ?>
                                <span></span>
                            </label>
                        </div>
                        <div class="col-lg-6 col-sm-6" style="padding-left: 5px;">
                            <label class="m-checkbox m-checkbox--solid m-checkbox--brand" data-toggle="m-tooltip" title="" data-original-title="Send Email notification">
                                <?php echo form_checkbox('send_email_notification[0]',1,FALSE,' class = "send_email_notification" '); ?>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </td>
                <td class="text-right">
                    <a href='javascript:;' class="remove-line">
                        <i class="text-danger la la-trash" style="margin-top:25%;"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="modal fade" id="create_new_account_pop_up" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <?php echo translate('Create New Account');?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#bank_account_tab" onClick="handle_tab_switch('bank_account')">
                            <?php echo translate('Bank');?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#sacco_account_tab" onClick="handle_tab_switch('sacco_account')">
                            <?php echo translate('Group');?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#mobile_money_account_tab" onClick="handle_tab_switch('mobile_money_account')">
                            <?php echo translate('Mobile Money');?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#petty_cash_account_tab" onClick="handle_tab_switch('petty_cash_account')">
                            <?php echo translate('Petty Cash');?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active show" id="bank_account_tab" role="tabpanel">
                        <?php echo form_open($this->uri->uri_string(),'class=" bank_account_form form_submit m-form m-form--state" id="bank_account_form" role="form"'); ?>
                            <div class="m-form__section m-form__section--first">
                                <span class="error"></span>
                                <div class="form-group m-form__group">
                                    <label for="example_input_full_name">
                                        <?php echo translate('Account Name');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_name','','id="bank_account_name" class="form-control" placeholder="Account Name"'); ?>
                                  <!--   <span class="m-form__help">
                                        <?php echo translate('Enter your account name as registered');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        <?php echo translate('Bank Name');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_dropdown('bank_id',array(''=>'--Select Bank--')+$banks,'','id="bank_id" class="form-control m-select2"  ') ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Select the bank your account is registered to');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group bank_branch_id" style="display: none;">
                                    <label>
                                        <?php echo translate('Bank Branch');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_dropdown('bank_branch_id',array(''=>'--Select Bank Name First--'),'','class="form-control m-select2" id = "bank_branch_id"  ') ?>
                                    <!-- <span class="m-form__help">
                                        <?php echo translate('Select the bank branch your account is registered to');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group bank_account_number" style="display: none;">
                                    <label>
                                        <?php echo translate('Account Number');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_number','',' id="bank_account_number" class="form-control" placeholder="Account Number"'); ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Enter your account number as registered');?>
                                    </span> -->
                                </div>

                                <div class="row">
                                    <div class="col-md-12 m--align-right">
                                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">
                                            <?php echo translate('Cancel');?>
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="create_bank_account">
                                            <?php echo translate('Submit');?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane" id="sacco_account_tab" role="tabpanel">
                        <?php echo form_open($this->uri->uri_string(),'class=" sacco_account_form form_submit m-form m-form--state" id="sacco_account_form" role="form"'); ?>
                            <div class="m-form__section m-form__section--first">
                                <span class="error"></span>
                                <div class="form-group m-form__group">
                                    <label for="example_input_full_name">
                                        <?php echo translate('Account Name');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_name','','class="form-control" placeholder="Account Name" id="sacco_account_name" '); ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Enter your account name as registered');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        <?php echo translate('Group Name');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_dropdown('sacco_id',array(''=>'--Select Sacco--')+$saccos,'','class="form-control m-select2" id="sacco_id"  ') ?>
                                  <!--   <span class="m-form__help">
                                        <?php echo translate('Select the Group your account is registered to');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group sacco_branch_id" style="display: none;">
                                    <label>
                                        <?php echo translate('Group Branch');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_dropdown('sacco_branch_id',array(''=>'--No branch records found--'),'','class="form-control m-select2" id = "sacco_branch_id"  ') ?>
                                    <!-- <span class="m-form__help">
                                        <?php echo translate('Select the Group your account is registered to');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group sacco_account_number" style="display: none;">
                                    <label>
                                        <?php echo translate('Account Number');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_number','','class="form-control" placeholder="Account Number" id="sacco_account_number"'); ?>
                                    <!-- <span class="m-form__help">
                                        <?php echo translate('Enter your account number as registered');?>
                                    </span> -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 m--align-right">
                                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">
                                            <?php echo translate('Cancel');?>
                                        </button>
                                        <button type="submit" class="btn btn-primary" id="create_sacco_account">
                                            <?php echo translate('Submit');?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane" id="mobile_money_account_tab" role="tabpanel">
                        <?php echo form_open($this->uri->uri_string(),'class=" mobile_money_account_form form_submit m-form m-form--state" id="mobile_money_account_form" role="form"'); ?>
                            <div class="m-form__section m-form__section--first">
                                <span class="error"></span>
                                <div class="form-group m-form__group">
                                    <label for="example_input_full_name">
                                        <?php echo translate('Mobile Money Account Name');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_name','','class="form-control" placeholder="Mobile Money Account Name" id="mobile_money_account_name" '); ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Enter your account name');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group">
                                    <label>
                                        <?php echo translate('Mobile Money Provider');?>
                                        <span class="required">*</span>
                                    </label>
                                     <?php echo form_dropdown('mobile_money_provider_id',array(''=>'--Select Mobile Money Provider--')+$mobile_money_providers,'','class="form-control  m-select2" id="mobile_money_provider_id"  ') ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Select the mobile money provider your account is registered to');?>
                                    </span> -->
                                </div>
                                <div class="form-group m-form__group mobile_money_account_number" style="display: none;">
                                    <label>
                                        <?php echo translate('Account Number');?>/
                                        <?php echo translate('Till Number');?>/
                                        <?php echo translate('Phone Number');?>
                                        <span class="required">*</span>
                                    </label>
                                    <?php echo form_input('account_number','','class="form-control" placeholder="Account Number / Phone Number / Till Number" id="mobile_money_account_number"'); ?>
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Enter your account number as registered');?>
                                    </span> -->
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 m--align-right">
                                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">
                                            <?php echo translate('Cancel');?>
                                        </button>
                                        <button type="submit" id="create_mobile_money_account" class="btn btn-primary">
                                            <?php echo translate('Submit');?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div class="tab-pane" id="petty_cash_account_tab" role="tabpanel">
                        <?php echo form_open($this->uri->uri_string(),'class=" petty_cash_account_form form_submit m-form m-form--state" id="petty_cash_account_form" role="form"'); ?>
                            <div class="m-form__section m-form__section--first">
                                <span class="error"></span>
                                <div class="form-group m-form__group">
                                    <label for="example_input_full_name">
                                        <?php echo translate('Petty Cash Account Name');?>
                                        <span class="required">*</span>                                            
                                    </label>
                                    <?php echo form_input('account_name','','class="form-control slug_parent" placeholder="Petty Cash Account Name " id="petty_cash_account_name"'); ?>
                                    <?php echo form_hidden('slug','','class="form-control slug"'); ?>     
                                   <!--  <span class="m-form__help">
                                        <?php echo translate('Enter your account name');?>
                                    </span> -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 m--align-right">
                                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">
                                            <?php echo translate('Cancel');?>
                                        </button>
                                        <button type="submit" id="create_petty_cash_account" class="btn btn-primary">
                                            <?php echo translate('Submit');?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_new_debtor_pop_up" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <?php echo translate('Add debtor');?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>
                <div class="modal-body">
                <?php echo form_open($this->uri->uri_string(),'class=" add_new_member_form form_submit m-form m-form--state" id="add_new_debtor_form" role="form"'); ?>
                    <span class="error"></span>
                    <div class="m-form__section m-form__section--first">
                        <div class="form-group m-form__group row">
                            <div class="col-sm-12 m-form__group-sub pt-0 m--padding-0">
                                <label><?php echo translate('Debtor Name');?><span class="required">*</span></label>
                                  <?php echo form_input('name','','class="form-control m-input m-input--air " placeholder="Debtor Name" ');?>
                            </div>
                            <div class="col-sm-12 m-form__group-sub pt-0 m--padding-0">
                                <label><?php echo translate('Phone number');?><span class="required">*</span></label>
                                  <?php echo form_input('phone','','class="form-control m-input m-input--air " placeholder="Phone number" ');?>
                            </div>
                            <div class="col-sm-12 m-form__group-sub pt-0 m--padding-0">
                                <label><?php echo translate('Email Address');?></label>
                                  <?php echo form_input('email','','class="form-control m-input m-input--air " placeholder="Email Address" ');?>
                            </div>
                            <div class="col-sm-12 m-form__group-sub pt-0 m--padding-0">
                                <label><?php echo translate('Description');?></label>
                                <?php 
                                    $textarea = array(
                                        'name'  =>  'description',
                                        'class' =>  'form-control',
                                        'rows'  =>  6,
                                        'value' => '',
                                        'placeholder'=>'Debtor Description'
                                        );
                                    echo form_textarea($textarea);
                                ?>
                            </div>
                        </div>                           
                    </div>            
                    <div class="modal-footer">
                        <span class="float-lg-right float-md-right float-sm-right float-xl-right">
                            <button class="btn btn-primary m-btn m-btn--custom m-btn--icon btn-sm" id="create_debtor_button" type="button">
                                <?php echo translate('Save Changes');?>
                            </button>
                            &nbsp;&nbsp;

                            <button type="button" class="btn btn-metal m-btn m-btn--custom m-btn--icon btn-sm" data-dismiss="modal" aria-label="Close"  id="debtor_close_modal">
                                <?php echo translate('Cancel');?>
                            </button> 
                        </span>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<a class="inline d-none" data-toggle="modal" data-target="#create_new_debtor_pop_up" data-title="Add debtor" data-id="create_debtor" id="add_new_debtor"  data-backdrop="static" data-keyboard="false">
    <?php echo translate('Add debtor');?>
</a>

<a class="inline d-none" data-toggle="modal" data-target="#create_new_account_pop_up" data-title="Create New Account" data-id="create_account" id="add_new_account"  data-backdrop="static" data-keyboard="false"><?php echo translate('Add Account');?></a>

<script>
    $(document).ready(function(){
        $(document).on('click','.add_deposit_description',function(){
            $(this).parent().find('.deposit_description').toggle();
        });

        //add account modal close eventt
        $('#create_new_account_pop_up').on('hidden.bs.modal', function () {
            $(':input','#create_new_account_pop_up')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#bank_account_tab #bank_branch_id,#bank_account_tab .bank_account_number,#sacco_account_tab #sacco_branch_id,#sacco_account_tab .sacco_account_number,#mobile_money_account_tab .mobile_money_account_number,#create_new_account_pop_up .data_error').slideUp();
            console.log('add account modal close event');
        });

        //add debtor modal close eventt
        $('#create_new_debtor_pop_up').on('hidden.bs.modal', function () {
            $("#create_new_debtor_pop_up input[type=text],#create_new_debtor_pop_up textarea").val("");
            $("#create_new_debtor_pop_up input[type=checkbox]").prop('checked',false);
            $("#create_new_debtor_pop_up .data_error").html('').slideUp();
        });

        $('#add-new-line').on('click',function(){
            var html = $('#append-new-line tbody').html();
            html = html.replace_all('checker','');
            $('#append-place-holder').append(html);
            $('.tooltips').tooltip();
            var number = 1;
            $('.count').each(function(){
                $(this).text(number);
                $(this).parent().find('.deposit_date').attr('name','deposit_dates['+(number-1)+']');
                $(this).parent().find('.debtor').attr('name','debtors['+(number-1)+']');
                $(this).parent().find('.loan').attr('name','loans['+(number-1)+']');
                $(this).parent().find('.deposit_description').attr('name','deposit_descriptions['+(number-1)+']');
                $(this).parent().find('.account').attr('name','accounts['+(number-1)+']');
                $(this).parent().find('.deposit_method').attr('name','deposit_methods['+(number-1)+']');
                $(this).parent().find('.amount').attr('name','amounts['+(number-1)+']');
                $(this).parent().find('.send_sms_notification').attr('name','send_sms_notifications['+(number-1)+']');
                $(this).parent().find('.send_email_notification').attr('name','send_email_notifications['+(number-1)+']');
                number++;
            });

            $('.table-multiple-items .m-select2-append').select2({
                
                placeholder:{
                    id: '-1',
                    text: "--Select option--",
                }, 
            });

            $('.date-picker').datepicker({ dateFormat: 'dd-mm-yy' ,autoclose: true});
            FormInputMask.init();
        });

        $('.table-multiple-items').on('click','a.remove-line',function(event){
            $(this).parent().parent().remove();
            var number = 1;
            $('.count').each(function(){
                $(this).text(number);
                number++;
            });
            TotalAmount.init();
        });

        //get debtor loan through ajax
        //var drop_down = $('.loan-to-populate').html();
        SnippetCreateDebtor.init(false,true);
        SnippetRepayDebtorLoan.init();
        $(document).on('change','.table-multiple-items select.debtor',function(){
            var row = $(this).parent().parent().parent();
            var debtor = $(this);
            var debtor_id = debtor.val();
            var attribute = debtor.attr('name');
            var loan_id = <?php echo $this->input->get_post('loan_id')?:0;?>;
            var url = '<?php echo site_url('group/loans/ajax_get_active_external_loans')?>';
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
                $(".table-multiple-items .loan").select2({
                    language: 
                        {
                            noResults: function() {
                            return 'Select debtor first';
                        }
                    },
                    escapeMarkup: function (markup) {
                        return 'Select debtor first';
                    }
                });
                $(row.find(".loan")).attr('readonly',true);
                // mApp.unblock(row.find(".change-loan").parent());
            }else{
                $(this).parent().parent().removeClass('has-danger');
                if($(this).val()=='0'){
                    $('#add_new_debtor').trigger('click');
                    $(this).val("").trigger('change');
                    // mApp.unblock(row.find(".change-loan"));
                }else{
                    mApp.block(row.find('.change-loan').parent(), {
                        overlayColor: 'grey',
                        animate: true,
                        type: 'loader',
                        state: 'primary',
                        // message: 'Loading...'
                    });
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "html",
                        data: {'debtor_id': debtor_id , 'attribute':attribute,'loan_id':loan_id},
                        success: function(res) 
                        {
                            debtor.parent().parent().parent().find('.change-loan').html(res);
                            debtor.parent().parent().parent().find('.loan').select2({
                                
                                language: 
                                    {
                                },
                                escapeMarkup: function (markup) {
                                    return markup;
                                }
                            });
                            $('.loan').val(loan_id).trigger("change")
                            mApp.unblock(row.find(".change-loan"));
                            mApp.unblock(row.find(".change-loan").parent());
                            $(row.find(".loan")).attr('readonly',false);
                        },
                    });
                }
            }
        });

        <?php if($this->input->get_post('debtor_id')){?>
            $('.table-multiple-items select.debtor').trigger('change');
        <?php }?>

        $(document).on('change','.table-multiple-items select.loan',function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $(document).on('change','.table-multiple-items select.account',function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $(document).on('change','.table-multiple-items select.deposit_method',function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $(document).on('blur','.table-multiple-items input.amount',function(){
            if($(this).val()==''){
                $(this).parent().addClass('has-danger');
            }else{
                var amount = $(this).val();
                regex = /^[0-9.,\b]+$/;;
                if(regex.test(amount)){
                    if(amount < 1){
                        $(this).parent().addClass('has-danger');
                    }else{
                        $(this).parent().removeClass('has-danger');
                    }
                }else{ 
                    $(this).parent().addClass('has-danger');
                }
            }
        });

        $(document).on('changeDate','.table-multiple-items input.deposit_date',function(){
            if($(this).val()==''){
                $(this).parent().addClass('has-danger');
            }else{
                $(this).parent().removeClass('has-danger');
            }
        });

        $('.date-picker').datepicker({ dateFormat: 'dd-mm-yy' ,autoclose: true}).on('changeDate', function(e) {
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $(document).on('change','select[name="bank_id"]',function(){
            var empty_branch_list = $('#bank_branch_id').find('select').html();
            var branch_id = '';
            var bank_id = $(this).val();
            $('.bank_branch_id, .bank_account_number').slideUp();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            if(bank_id){
                $.post('<?php echo site_url('group/bank_accounts/ajax_get_bank_branches');?>',{'bank_id':bank_id,'branch_id':branch_id},
                function(data){
                    $('#bank_branch_id').html(data);
                    $('#create_new_account_pop_up .select2-append').select2({
                        
                        placeholder:{
                            id: '-1',
                            text: "--Select option--",
                        }, 
                    });
                    $('.bank_branch_id').slideDown();
                    mApp.unblock('.modal-body');
                });
            }else{
                $('#bank_branch_id').html('<select name="bank_id" class="form-control select2" id="bank_branch_id">'+empty_branch_list+'</select>');
                mApp.unblock('.modal-body');
            }
        });

        $(document).on('change','select[name="bank_branch_id"]',function(){
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            var bank_branch_id = $(this).val();
            if(bank_branch_id){
                $('.bank_account_number').slideDown();
                mApp.unblock('.modal-body');
            }else{
                $('.bank_account_number').slideUp();
                mApp.unblock('.modal-body');
            }
        });

        $(document).on('change','select[name="sacco_id"]',function(){
            var empty_branch_list =$('#sacco_branch_id').find('select').html();
            var branch_id = '';
            var sacco_id = $(this).val();
            $('.sacco_branch_id, .sacco_account_number').slideUp();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            if(sacco_id){
                $.post('<?php echo site_url('group/sacco_accounts/ajax_get_sacco_branches');?>',{'sacco_id':sacco_id,'branch_id':''},
                function(data){
                    $('#sacco_branch_id').html(data);
                    $('#create_new_account_pop_up .select2-append').select2({
                        
                        placeholder:{
                            id: '-1',
                            text: "--Select option--",
                        }, 
                    });
                    $('.sacco_branch_id').slideDown();
                    mApp.unblock('.modal-body');
                });
            }else{
                 $('#sacco_branch_id').html('<select name="bank_id" class="form-control select2" id="bank_branch_id">'+empty_branch_list+'</select>');
                mApp.unblock('.modal-body');
            }
        });

        $(document).on('change','select[name="sacco_branch_id"]',function(){
            var element = $(this);
            var sacco_branch_id = $(this).val();
            $('.sacco_account_number').slideUp();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            if(sacco_branch_id){
                $('.sacco_account_number').slideDown();
                mApp.unblock('.modal-body');
            }else{
                mApp.unblock('.modal-body');
            }
        });

        $(document).on('change','select[name="mobile_money_provider_id"]',function(){
            var mobile_money_provider_id = $(this).val();
            $('.mobile_money_account_number').slideUp();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            if(mobile_money_provider_id){
                $('.mobile_money_account_number').slideDown();
                mApp.unblock('.modal-body');
            }else{
                mApp.unblock('.modal-body');
            }
        });

        /*$(document).on('submit','.loan_repayments_form',function(e){
            e.preventDefault();
            $('.submit_form_button').addClass("m-loader m-loader--right m-loader--light").attr("disabled", true);
            mApp.block('.loan_repayments_form', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Processing...'
            });
            if(validate_loan_repayments_form()){
                var form = $(".loan_repayments_form");
                $.ajax({
                    url:'<?php echo site_url('ajax/deposits/record_loan_repayments')?>',
                    type:'POST',
                    data:form.serialize(),
                    success: function(data) {
                        var response = $.parseJSON(data);
                        if(response.status == 1){
                            toastr['success'](response.message);
                            window.location.href = response.refer;
                        }else{
                            $('.loan_repayments_form .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong>'+response.message+'</div>');
                        }
                        mApp.unblock('.loan_repayments_form');
                        $('.loan_repayments_form .submit_form_button').removeClass('m-loader m-loader--light m-loader--left').attr('disabled',false);
                    }
                });
            }else{
                $('.loan_repayments_form .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> There are errors on the form, please review the highlighted fields and try submitting again.</div>');
                mApp.unblock('.loan_repayments_form');
                $('.loan_repayments_form .submit_form_button').removeClass('m-loader m-loader--light m-loader--left').attr('disabled',false);
            }
            
        });*/

        $(document).on('submit','#bank_account_form',function(e){
            e.preventDefault();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Processing...'
            });
            var form = $(this);
            RemoveDangerClass(form);
            $('#bank_account_tab .error').html('').slideUp();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("ajax/bank_accounts/create"); ?>',
                data: form.serialize(),
                success: function(response) {
                    if(isJson(response)){
                        var data = $.parseJSON(response);
                        if(data.status == 1){
                            $('select.account').each(function(){
                                $(this).append('<option value="bank-' + data.bank_account.id + '">'+data.bank_account.bank_details+' - ' + data.bank_account.account_name + ' ('+data.bank_account.account_number+')</option>').trigger('change');
                            });
                            $('..table-multiple-items select[name="accounts['+current_row+']"]').val("bank-"+data.bank_account.id).trigger('change');
                            $('#create_new_account_pop_up .close').trigger('click');
                            toastr['success']('You have successfully added a new bank account, you can now select it in the accounts dropdown.','Bank account added successfully');
                        }else{
                            $('#bank_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+data.message+'</div>').slideDown('slow');
                            if(data.validation_errors){
                                $.each(data.validation_errors, function( key, value ) {
                                    var error_message ='<div class="form-control-feedback">'+value+'</div>';
                                    $('#bank_account_tab input[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                    $('#bank_account_tab select[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                });
                            }
                        }
                        
                    }else{
                        $('#bank_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> There was a problem processing your request, please try again</div>').slideDown('slow');
                    }
                    mApp.unblock('.modal-body');
                }
            });
        });

        $(document).on('submit','#sacco_account_form',function(e){
            e.preventDefault();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Processing...'
            });
            var form = $(this);
            RemoveDangerClass(form);
            $('#create_new_account_pop_up .error').html('').slideUp();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("group/sacco_accounts/ajax_create"); ?>',
                data: form.serialize(),
                success: function(response) {
                    if(isJson(response)){
                        var data = $.parseJSON(response);
                        if(data.status == 1){
                            $('select.account').each(function(){
                                $(this).append('<option value="sacco-' + data.sacco_account.id + '">'+data.sacco_account.sacco_details+' - ' + data.sacco_account.account_name + ' ('+data.sacco_account.account_number+')</option>').trigger('change');
                            });
                            $('.table-multiple-items select[name="accounts['+current_row+']"]').val("sacco-"+data.sacco_account.id).trigger('change');
                            $('#create_new_account_pop_up .close').trigger('click');
                            toastr['success']('You have successfully added a new sacco account, you can now select it in the accounts dropdown.','Sacco account added successfully');
                        }else{
                            $('#sacco_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+data.message+'</div>').slideDown('slow');
                            if(data.validation_errors){
                                $.each(data.validation_errors, function( key, value ) {
                                    var error_message ='<div class="form-control-feedback">'+value+'</div>';
                                    $('#sacco_account_tab input[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                    $('#sacco_account_tab select[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                });
                            }
                        }
                        
                    }else{
                        $('#sacco_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> There was a problem processing your request, please try again</div>').slideDown('slow');
                    }
                    mApp.unblock('.modal-body');
                }
            });
        });

        $(document).on('submit','#mobile_money_account_form',function(e){
            e.preventDefault();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            var form = $(this);
            RemoveDangerClass(form);
            $('#create_new_account_pop_up .error').html('').slideUp();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("group/mobile_money_accounts/ajax_create"); ?>',
                data: form.serialize(),
                success: function(response) {
                    if(isJson(response)){
                        var data = $.parseJSON(response);
                        if(data.status == 1){
                            $('select.account').each(function(){
                                $(this).append('<option value="mobile-' + data.mobile_money_account.id + '">'+data.mobile_money_account.mobile_money_provider_details+' - ' + data.mobile_money_account.account_name + ' ('+data.mobile_money_account.account_number+')</option>').trigger('change');
                            });
                            $('.table-multiple-items select[name="accounts['+current_row+']"]').val("mobile-"+data.mobile_money_account.id).trigger('change');
                            $('#create_new_account_pop_up .close').trigger('click');
                            toastr['success']('You have successfully added a new mobile money account, you can now select it in the accounts dropdown.','Mobile money account added successfully');
                        }else{
                            $('#mobile_money_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+data.message+'</div>').slideDown('slow');
                            if(data.validation_errors){
                                $.each(data.validation_errors, function( key, value ) {
                                    var error_message ='<div class="form-control-feedback">'+value+'</div>';
                                    $('#mobile_money_account_tab input[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                    $('#mobile_money_account_tab select[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                });
                            }
                        }
                    }else{
                        $('#mobile_money_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> There was a problem processing your request, please try again</div>').slideDown('slow');
                    }
                    mApp.unblock('.modal-body');
                }
            });
        });

        $(document).on('submit','#petty_cash_account_form',function(e){
            e.preventDefault();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Loading...'
            });
            var form = $(this);
            RemoveDangerClass(form);
            $('#create_new_account_pop_up .error').html('').slideUp();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("group/petty_cash_accounts/ajax_create"); ?>',
                data: form.serialize(),
                success: function(response) {
                    if(isJson(response)){
                        var data = $.parseJSON(response);
                        if(data.status == 1){
                            $('select.account').each(function(){
                                $(this).append('<option value="petty-' + data.petty_cash_account.id + '">' + data.petty_cash_account.account_name + '</option>').trigger('change');
                            });
                            $('.table-multiple-items select[name="accounts['+current_row+']"]').val("petty-"+data.petty_cash_account.id).trigger('change');
                            $('#create_new_account_pop_up .close').trigger('click');
                            toastr['success']('You have successfully added a new petty cash account, you can now select it in the accounts dropdown.','Petty cash account added successfully');
                        }else{
                            $('#petty_cash_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+data.message+'</div>').slideDown('slow');
                            if(data.validation_errors){
                                $.each(data.validation_errors, function( key, value ) {
                                    if(key == 'account_slug'){
                                        //skip
                                    }else{
                                        var error_message ='<div class="form-control-feedback">'+value+'</div>';
                                        $('#petty_cash_account_tab input[name="account_name"]').parent().addClass('has-danger').append(error_message);
                                    }
                                   
                                });
                            }
                        }
                    }else{
                        $('#petty_cash_account_tab .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> There was a problem processing your request, please try again</div>').slideDown('slow');
                    }
                    mApp.unblock('.modal-body');
                }
            });
        });

        $(document).on('submit','#add_new_debtor_form',function(e){
            e.preventDefault();
            mApp.block('.modal-body', {
                overlayColor: 'grey',
                animate: true,
                type: 'loader',
                state: 'primary',
                message: 'Processing...'
            });
            var form = $(this);
            RemoveDangerClass(form);
            $('#create_new_debtor_pop_up .error').html('').slideUp();
            $('#add_debtor_submit').addClass('m-loader m-loader--light m-loader--left').attr('disabled',true);
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("group/debtors/ajax_add_debtor"); ?>',
                data: form.serialize(),
                success: function(response) {
                    if(isJson(response)){
                        var data = $.parseJSON(response);
                        if(data.status == 1){
                            $('select.debtor').each(function(){
                                $(this).append('<option value="' + data.debtor.id + '">' + data.debtor.first_name +' '+ data.debtor.last_name + '</option>').trigger('change');
                            });
                            $('.table-multiple-items select[name="debtors['+current_row+']"]').val(data.debtor.id).trigger('change');
                            $('#create_new_debtor_pop_up .close').trigger('click');
                            toastr['success']('You have successfully added a new debtor to your group, you can now select him/her in the debtors dropdown.','debtor added successfully');
                        }else{
                            $('#create_new_debtor_pop_up .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>'+data.message+'</div>').slideDown('slow');
                            if(data.validation_errors){
                                $.each(data.validation_errors, function( key, value ) {
                                    var error_message ='<div class="form-control-feedback">'+value+'</div>';
                                    $('#create_new_debtor_pop_up input[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                    $('#create_new_debtor_pop_up select[name="'+key+'"]').parent().addClass('has-danger').append(error_message);
                                });
                            }
                        }
                    }else{
                        $('#create_new_debtor_pop_up .error').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><strong>Sorry!</strong> Could not process your request at the moment</div>').slideDown('slow');
                    }
                    $('#add_debtor_submit').removeClass('m-loader m-loader--light m-loader--left').attr('disabled',false);
                    mApp.unblock('.modal-body', {});
                }
            });
        });

        $(document).on('click','#add_new_debtor',function(){
            $(".table-multiple-items .debtor").select2({
                
                language: 
                    {
                    noResults: function() {
                        return '<a class="inline" data-toggle="modal" data-content="#create_new_debtor_pop_up" data-title="Add debtor" data-id="create_debtor" id="add_new_debtor"  >Add debtor</a>';
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            }).trigger("select2:close");
        });

        $(document).on('click','#add_new_account',function(){
            $(".table-multiple-items .account").select2({
                
                language: 
                    {
                     noResults: function() {
                        return '<a class="inline " data-toggle="modal" data-content="#create_new_account_pop_up" data-title="Create New Account" data-id="create_account" id="add_new_account"  >Add Account</a>';
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            }).trigger("select2:close");
            $('#submit_form .modal-footer').hide();
        });

        $(document).on('click','#add_loan',function(){
            $(".table-multiple-items .loan").select2({
                
                // language: 
                //     {
                //      noResults: function() {
                //         return '<a class="inline hidden" data-row="" data-toggle="modal" data-content="#loans_form" data-title="Add Loan" data-id="add_loan" id="add_loan" href="#">Add Loan</a>';
                //     }
                // },
                escapeMarkup: function (markup) {
                    return markup;
                }
            }).trigger("select2:close");
            var debtor_id = $('select[name="debtors['+current_row+']"]').val();
            $('#modal').on('shown.bs.modal', function(){
                $(document).find('#submit_form select[name="debtor_id"]').val(debtor_id).trigger('change').prop('disabled',true);
                $(document).find('#submit_form input[name="debtor_id"]').val(debtor_id);
            });
        });

        $(document).on('change','.account',function(){
            if($(this).val()=='0'){
                $('#add_new_account').trigger('click');
                $(this).val("").trigger('change');
            }
            $('#create_new_account_pop_up .select2-append').select2({
                
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });

        $(document).on('change','.loan',function(){
            if($(this).val()=='0'){
                $('#add_loan').trigger('click');
                $(this).val("").trigger('change');
            }
        });

        var current_row = 0;
        $(document).on('select2:open','.debtor', function(e) {
            // do something
            var name = $(this).attr("name");
            var row = name.substring(name.lastIndexOf("[")+1,name.lastIndexOf("]"));
            current_row = row;
        });

        $(document).on('select2:open','.account', function(e) {
            // do something
            var name = $(this).attr("name");
            var row = name.substring(name.lastIndexOf("[")+1,name.lastIndexOf("]"));
            current_row = row;
        });

        $(document).on('select2:open','.loan', function(e) {
            // do something
            var name = $(this).attr("name");
            var row = name.substring(name.lastIndexOf("[")+1,name.lastIndexOf("]"));
            current_row = row;
        });

        $('.loan').each(function(){
            var row = $(this).parent().parent().parent();
            if(row.find('.debtor').val()==""){
                // $(this).attr('readonly',true);
                // $(this).prop('disabled',true);
            }
        });

        if($('#submit_form input[name="sms_notifications_enabled"]').prop("checked")==true){
            $('#submit_form #sms_template').slideDown();
        }else{
            $('#submit_form #sms_template').slideUp();
        }

        $(document).on('click','#submit_form input[name="sms_notifications_enabled"]',function(){
            var sms_template =$(this).prop('checked');
            if(sms_template==true){
                $('#submit_form #sms_template').slideDown();
            }else{
                $('#submit_form #sms_template').slideUp();
            }
        });

        $(document).on('change',"#submit_form #interest_type",function(){
            if($(this).val()==2){
                $('#submit_form #enable_reducing_balance_installment_recalculation').slideDown();
            }else{
                $('#submit_form #enable_reducing_balance_installment_recalculation').slideUp();
            }
        });

        $(document).on('change','#submit_form select[name="interest_type"]',function(){
            var interest_type = $(this).val();
            if(interest_type==3){
                $('#submit_form .not_for_custom_settings').hide();
                $('#submit_form .for_custom_settings').slideDown();
                $('#submit_form .custom_invoice_breakdown_settings').slideDown();
                $('#submit_form .interest_rates_breakdown').hide();
            }else{
                $('#submit_form .for_custom_settings').hide();
                $('#submit_form .not_for_custom_settings').slideDown();
            }
        });

        $(document).on('change','#submit_form input[name="custom_interest_procedure"]',function(){
            var custom_interest_procedure = $(this).val();
            if(custom_interest_procedure==1){
                $('#submit_form .custom_invoice_breakdown_settings').hide();
                $('#submit_form .interest_rates_breakdown').slideDown();
            }else if(custom_interest_procedure==2){
                $('#submit_form .interest_rates_breakdown').hide();
                $('#submit_form .custom_invoice_breakdown_settings').slideDown();
            }else{
                $('#submit_form .interest_rates_breakdown').hide();
                $('#submit_form .custom_invoice_breakdown_settings').hide();
            }
        });

        var html_rate_breakdown = $('.loan_interest_rates_breakdown_values').html();
        var html_invoice_breakdown = $('.custom_invoice_breakdown_values').html();

        $(document).on('click','#submit_form #add-new-line-rate-breakdown',function(){
            html_rate_breakdown = html_rate_breakdown.replace_all('checker','');
            $('#submit_form #append-place-holder-rates-breakdown').append('<div class="loan_interest_rates_breakdown_values">'+html_rate_breakdown+'</div>');
            $('#submit_form .interest_rate_date_from').select2();
            $('#submit_form .interest_rate_date_to').select2();
            $('.tooltips').tooltip();
        });

        $(document).on('click','#submit_form #add-new-line-invoice-breakdown',function(){
            html_invoice_breakdown = html_invoice_breakdown.replace_all('checker','');
            $('#submit_form #append-place-holder-invoice-breakdown').append('<div class="custom_invoice_breakdown_values">'+html_invoice_breakdown+'</div>');
            FormInputMask.init();
            $('.date-picker').datepicker({ dateFormat: 'dd-mm-yy' ,autoclose: true});
            $('.tooltips').tooltip();
        });

        $(document).on('click','.remove-rate-breakdown',function(){ 
            $(this).parent().parent().remove();
        });

        $(document).on('click','.remove-invoice-breakdown',function(){ 
            $(this).parent().parent().remove();
        });

        $(document).on('click','#submit_form input[name="enable_loan_fines"]',function(){
            if($(this).prop("checked") == true){
                $('#submit_form .enable_loan_fines_settings').slideDown();
            }else if($(this).prop("checked") == false){
                $(' #submit_form .enable_loan_fines_settings').slideUp();
            }
        });

        $(document).on('change','#submit_form select[name="loan_fine_type"]',function(){
            var loan_fine_type = $(this).val();
            if(loan_fine_type==1){
                $('#submit_form .late_loan_payment_fixed_fine').slideDown();
                $('#submit_form .late_loan_payment_percentage_fine').slideUp();
                $('#submit_form .late_loan_repayment_one_off_fine').slideUp();
            }else if(loan_fine_type==2){
                $('#submit_form .late_loan_payment_percentage_fine').slideDown();
                $('#submit_form .late_loan_payment_fixed_fine').slideUp();
                $('#submit_form .late_loan_repayment_one_off_fine').slideUp();
            }else if(loan_fine_type==3){
                $('#submit_form .one_off_percentage_setting').hide();
                $('#submit_form .one_off_fixed_amount_setting').hide();
                $('#submit_form .one_off_fine_type_settings').hide();
                $('#submit_form .late_loan_repayment_one_off_fine').slideDown();
                $('#submit_form .late_loan_payment_percentage_fine').slideUp();
                $('#submit_form .late_loan_payment_fixed_fine').slideUp();
            }else{
                $('#submit_form .late_loan_payment_percentage_fine').slideUp();
                $('#submit_form .late_loan_payment_fixed_fine').slideUp();
                $('#submit_form .late_loan_repayment_one_off_fine').slideUp();
            }
        });

        $(document).on('change','#submit_form select[name="one_off_fine_type"]',function(){
            var one_off_fine_type = $(this).val();
            if(one_off_fine_type==1){
                $('#submit_form .one_off_fine_type_settings').show();
                $('#submit_form .one_off_percentage_setting').hide();
                $('#submit_form .one_off_fixed_amount_setting').show();
            }else if(one_off_fine_type==2){
                $('#submit_form .one_off_fine_type_settings').show();
                $('#submit_form .one_off_fixed_amount_setting').hide();
                $('#submit_form .one_off_percentage_setting').show();
            }else if(one_off_fine_type==''){
                $('#submit_form .one_off_percentage_setting').hide();
                $('#submit_form .one_off_fixed_amount_setting').hide();
                $('#submit_form .one_off_fine_type_settings').hide();
            }
        });

        $(document).on('click','#submit_form input[name="enable_outstanding_loan_balance_fines"]',function(){
            if($(this).prop("checked") == true){
                $('.enable_outstanding_loan_balances_fines_settings').slideDown();
                $('#submit_form .outstanding_loan_balance_fine_one_off_settings').hide();
                $('#submit_form .outstanding_loan_balance_percentage_settings').hide();
                $('#submit_form .outstanding_loan_balance_fixed_fine').hide();
            }else if($(this).prop("checked") == false){
                $('.enable_outstanding_loan_balances_fines_settings').slideUp();
            }
        });

        $(document).on('change','#submit_form .outstanding_loan_balance_fine_type',function(){
            var outstanding_loan_balance_fine_type =$(this).val();
            if(outstanding_loan_balance_fine_type==1){
                $('#submit_form .outstanding_loan_balance_fine_one_off_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_percentage_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_fixed_fine').slideDown();
            }else if(outstanding_loan_balance_fine_type==2){
                $('#submit_form .outstanding_loan_balance_fine_one_off_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_fixed_fine').slideUp();
                $('#submit_form .outstanding_loan_balance_percentage_settings').slideDown();
            }else if(outstanding_loan_balance_fine_type==3){
                $('#submit_form .outstanding_loan_balance_percentage_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_fixed_fine').slideUp();
                $('#submit_form .outstanding_loan_balance_fine_one_off_settings').slideDown();
            }else{
                $('#submit_form .outstanding_loan_balance_fine_one_off_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_percentage_settings').slideUp();
                $('#submit_form .outstanding_loan_balance_fixed_fine').slideUp();
            }
        });

        $(document).on('click','#submit_form input[name="enable_loan_processing_fee"]',function(){
            if($(this).prop("checked") == true){
                $('#submit_form .loan_processing_fee_settings').slideDown();
            }else if($(this).prop("checked") == false){
                $('#submit_form .loan_processing_fee_settings').slideUp();
            }
        });

        $(document).on('change','#submit_form select[name="loan_processing_fee_type"]',function(){
            var loan_processing_fee_type = $(this).val();
            if(loan_processing_fee_type==1){
                $('#submit_form .loan_processing_fee_settings').slideDown();
                $('#submit_form .percentage_fee_processing_fee_settings').slideUp();
                $('#submit_form .fixed_amount_processing_fee_settings').slideDown();
            }else if(loan_processing_fee_type==2){
                $('#submit_form .loan_processing_fee_settings').slideDown();
                $('#submit_form .fixed_amount_processing_fee_settings').slideUp();
                $('#submit_form .percentage_fee_processing_fee_settings').slideDown();
            }else{
                $('#submit_form .fixed_amount_processing_fee_settings').slideUp();
                $('#submit_form .percentage_fee_processing_fee_settings').slideUp();
            }
        });

        $(document).on('click','#submit_form input[name="enable_loan_guarantors"]',function(){
            if($(this).prop('checked')==true){
                $('.loan_guarantor_settings').slideDown();
            }else{
                $('.loan_guarantor_settings').slideUp();
            }
        });

        var html = $('.loan_guarantor_settings_values').html();

        $(document).on('click','#add-new-line-guarantor',function(){
            html = html.replace_all('checker','');
            $('#submit_form #append-place-holder').append('<div class="loan_guarantor_settings_values">'+html+'</div>');
            $('.guarantor_id').select2();
            FormInputMask.init();
            $('.tooltips').tooltip();
        });

        $(document).on('click','.remove-line',function(){ 
            $(this).parent().parent().remove();
        });

    });

    $(window).on('load',function() {
        $('.table-multiple-items .debtor').select2({
            language: 
                {
                 noResults: function() {
                    return '<a class="inline" data-toggle="modal" data-content="#create_new_debtor_pop_up" data-title="Add debtor" data-id="create_debtor" id="add_new_debtor"  >Add debtor</a>';
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        $(".table-multiple-items .account").select2({
            language: 
                {
                 noResults: function() {
                    return '<a class="inline " data-toggle="modal" data-content="#create_new_account_pop_up" data-title="Create New Account" data-id="create_account" id="add_new_account"  >Add Account</a>';
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        $(".table-multiple-items .loan").select2({
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        $(".table-multiple-items .deposit_method").select2({
            placeholder:{
                id: '-1',
                text: "--Select option--",
            }, 
        });
    });

    function handle_tab_switch(tab){
        //check tab
        //clear values on other tabs
        //slide up on other tabs
        $('#create_new_account_pop_up .error').html('').slideUp();
        if(tab == 'bank_account'){
            $(':input','#mobile_money_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#mobile_money_account_tab .mobile_money_account_number').slideUp();
            $(':input','#sacco_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#sacco_account_tab #sacco_branch_id,#sacco_account_tab .sacco_account_number').slideUp();
            $(':input','#petty_cash_account_tab').val('');
        }else if(tab == 'sacco_account'){
            $(':input','#mobile_money_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#mobile_money_account_tab .mobile_money_account_number').slideUp();
            $(':input','#bank_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#bank_account_tab #bank_branch_id,#bank_account_tab .bank_account_number').slideUp();
            $(':input','#petty_cash_account_tab').val('');
        }else if(tab == 'mobile_money_account'){
            $(':input','#sacco_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#sacco_account_tab #sacco_branch_id,#sacco_account_tab .sacco_account_number').slideUp();
            $(':input','#bank_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#bank_account_tab #bank_branch_id,#bank_account_tab .bank_account_number').slideUp();
            $(':input','#petty_cash_account_tab').val('');
        }else if(tab == 'petty_cash_account'){
            $(':input','#sacco_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#sacco_account_tab #sacco_branch_id,#sacco_account_tab .sacco_account_number').slideUp();
            $(':input','#bank_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#bank_account_tab #bank_branch_id,#bank_account_tab .bank_account_number').slideUp();
            $(':input','#mobile_money_account_tab')
                .val('')
                .prop('checked',false)
                .removeAttr('selected')
                .trigger('change');
            $('#mobile_money_account_tab .mobile_money_account_number').slideUp();
            $(':input','#petty_cash_account_tab').val('');
        }
    }

    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
    String.prototype.replace_all = function(search,replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };

    function validate_loan_repayments_form(){
        var entries_are_valid = true;
        $('.table-multiple-items input.deposit_date').each(function(){
            if($(this).val()==''){
                $(this).parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                $(this).parent().removeClass('has-danger');
            }
        });

        $('.table-multiple-items select.loan').each(function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $('.table-multiple-items select.debtor').each(function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $('.table-multiple-items select.deposit_method').each(function(){
            if($(this).val()==''){
                $(this).parent().parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                $(this).parent().parent().removeClass('has-danger');
            }
        });

        $('.table-multiple-items input.amount').each(function(){
            if($(this).val()==''){
                $(this).parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                var amount = $(this).val();
                regex = /^[0-9.,\b]+$/;;
                if(regex.test(amount)){
                    if(amount < 1){
                        $(this).parent().addClass('has-danger');
                        entries_are_valid = false;
                    }else{
                        $(this).parent().removeClass('has-danger');
                    }
                }else{ 
                    $(this).parent().addClass('has-danger');
                    entries_are_valid = false;
                }
            }
        });

        $('.table-multiple-items select.account').each(function(){
            if($(this).val()==''){
                $(this).parent().addClass('has-danger');
                entries_are_valid = false;
            }else{
                $(this).parent().removeClass('has-danger');
            }
        });

        if(entries_are_valid){
            return true;
        }else{
            return false;
        }
    }
</script>
