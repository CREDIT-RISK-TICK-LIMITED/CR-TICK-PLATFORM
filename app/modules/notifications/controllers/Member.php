<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Member_Controller{

    protected $notification_status_options = array(
        0 => "Unread",
        1 => "Read",
    );

	function __construct(){
        parent::__construct();
        $this->load->model('notifications_m');
    }

    function index(){
        $data = array();
        $from = strtotime(xss_clean_input($this->input->get('from')))?:'';
        $to = strtotime(xss_clean_input($this->input->get('to')))?:'';
        $data['from'] = $from;
        $data['to'] = $to;
        $data['notification_status_options'] = $this->notification_status_options;
        $this->template->title(translate('Notifications'))->build('shared/index',$data);
    }

    function listing(){
        $data = array();
        $from = strtotime(xss_clean_input($this->input->get('from')))?:'';
        $to = strtotime(xss_clean_input($this->input->get('to')))?:'';
        $data['from'] = $from;
        $data['to'] = $to;
        $data['notification_status_options'] = $this->notification_status_options;
        $this->template->title(translate('List Notifications'))->build('shared/listing',$data);
    }

    function ajax_get_notifications_listing(){
        $from = strtotime(xss_clean_input($this->input->get('from')))?:'';
        $to = strtotime(xss_clean_input($this->input->get('to')))?:'';
        $filter_parameters = array(
            'from' => $from,
            'to' => $to,
            'is_read' => $this->input->get('is_read'),
        );
        $total_rows = $this->notifications_m->count_member_notifications($filter_parameters);
        $pagination = create_pagination('group/notifications/listing/pages',$total_rows,50,5,TRUE);
        $posts = $this->notifications_m->limit($pagination['limit'])->get_member_notifications($filter_parameters);
        if(!empty($posts)){
            echo form_open('group/notifications/action', ' id="form"  class="form-horizontal"');
                if ( ! empty($pagination['links'])):
                echo '
                <div class="row col-md-12">
                    <p class="paging">Showing from <span class="greyishBtn">'.$pagination['from'].'</span> to <span class="greyishBtn">'.$pagination['to'].'</span> of <span class="greyishBtn">'.$pagination['total'].'</span> Notifications</p>';
                    echo '<div class ="top-bar-pagination">';
                    echo $pagination['links']; 
                    echo '</div></div>';
                endif; 
                echo '  
                <table class="table table-condensed table-striped table-hover table-header-fixed table-searchable">
                    <thead>
                        <tr>
                            <th width=\'2%\'>
                                 <input type="checkbox" name="check" value="all" class="check_all">
                            </th>
                            <th>
                                Notification Details
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>';
                        foreach($posts as $post):
                            echo '
                            <tr ';
                            echo $post->is_read==0?"class='unread'":''; 
                            echo '
                            >
                                <td><input name=\'action_to[]\' type="checkbox" class="checkboxes" value="'.$post->id.'" /></td>
                                <td>
                                    <a href="'.site_url($post->call_to_action_link).'">';
                                        echo '<span class="font-dark">'.$post->message.'</span></br>';
                                        echo '<small>'.timestamp_to_datetime($post->created_on).' - <cite>'.timestamp_to_time_elapsed($post->created_on).'</cite></small>'; 
                                    echo '
                                    </a>
                                </td> 
                                <td>';
                                    if($post->is_read==0){
                                        echo '
                                        <a href="'.site_url('group/notifications/mark_as_read/'.$post->id).'" class="btn btn-xs default">
                                            <i class="fa fa-check"></i> Mark as Read &nbsp;&nbsp; 
                                        </a>';
                                    }else{
                                        echo '
                                        <a href="'.site_url('group/notifications/mark_as_unread/'.$post->id).'" class="btn btn-xs default">
                                            <i class="fa fa-remove"></i> Mark as Unread &nbsp;&nbsp; 
                                        </a>';
                                    }
                                echo '
                                </td>
                            </tr>';
                        endforeach;
                        echo '
                    </tbody>
                </table>
                <div class="clearfix"></div>
                <div class="row col-md-12">';
                    if( ! empty($pagination['links'])): 
                    echo $pagination['links']; 
                    endif; 
                echo '
                </div>
                <div class="clearfix"></div>';
                if($posts):
                    echo '
                    <button class="btn btn-sm btn-default confirmation_bulk_action" name=\'btnAction\' value=\'bulk_mark_as_read\' data-toggle="confirmation" data-placement="top"> <i class=\'fa fa-eye\'></i> Bulk Mark As Read</button>
                    <button class="btn btn-sm btn-default confirmation_bulk_action" name=\'btnAction\' value=\'bulk_mark_as_unread\' data-toggle="confirmation" data-placement="top"> <i class=\'fa fa-eye-slash\'></i> Bulk Mark As Unread</button>';
                endif;
            echo form_close();
        }else{
            echo '
            <div class="alert alert-info">
                <h4 class="block">Information! No records to display</h4>
                <p>
                    No notifications to display.
                </p>
            </div>';
        } 
    }

    function mark_as_read($id=0,$redirect=TRUE){
        $id OR redirect('member/notifications/listing');
        $post = $this->notifications_m->get_member_notification($id);    
        $post OR redirect('member/notifications/listing');
        $input = array(
            'is_read'=>1,
            'modified_by'=>$this->user->id,
            'modified_on'=>time(),
        );
        if($result = $this->notifications_m->update($id,$input)){
            $this->session->set_flashdata('success','Notification was successfully marked as read');
        }else{
            $this->session->set_flashdata('error','Unable to mark the notification as read ');
        }
        if($redirect){
            redirect('member/notifications/listing');
        }
        return TRUE;
    }

    function mark_as_unread($id=0,$redirect=TRUE){
        $id OR redirect('member/notifications/listing');
        $post = $this->notifications_m->get_member_notification($id);    
        $post OR redirect('member/notifications/listing');
        $input = array(
            'is_read'=>0,
            'modified_by'=>$this->user->id,
            'modified_on'=>time(),
        );
        if($result = $this->notifications_m->update($id,$input)){
            $this->session->set_flashdata('success','Notification was successfully marked as unread');
        }else{
            $this->session->set_flashdata('error','Unable to mark notification as unread');
        }
        if($redirect){
            redirect('member/notifications/listing');
        }
        return TRUE;
    }

    function action(){
        $action_to = $this->input->post('action_to');
        $action = $this->input->post('btnAction');
        if($action == 'bulk_mark_as_read'){
            for($i=0;$i<count($action_to);$i++){
                $this->mark_as_read($action_to[$i],FALSE);
            }
        }else if($action == 'bulk_mark_as_unread'){
            for($i=0;$i<count($action_to);$i++){
                $this->mark_as_unread($action_to[$i],FALSE);
            }
        }
        redirect('member/notifications/listing');
    }

}