<?php

namespace Shadowapp\Models;

use Shadowapp\Sys\Db\Model;
use Shadowapp\Sys\Db\Query\Builder as DB;

Class StaffShadow extends Model
{

  protected $db;
   
  public function __construct () 
  {
  	parent::__construct();

  	$this->db = new DB;
  } 
   
  public function add( array $fields )
  {
     $this->firstname = trim(shcol('firstname',$fields));
     $this->lastname = trim(shcol('lastname',$fields));
     $this->username = trim(shcol('username',$fields));
     $this->email = trim(shcol('email',$fields));
     $this->phone = trim(shcol('phone',$fields));
     $this->password = hash('sha256',trim(shcol('password',$fields)));
     $this->create_date = date('Y-m-d h:i:s');
     $this->update_date = date('Y-m-d h:i:s');

     return $this->save();
  }


  public function storeConfirmData ( $id, $randstr )
  {
      # check if confirm value already exists
     $confrimTableInfo = $this->db->from('confirm_table')->where([
      'staff_id' => $id
  	 ])->get();
     
     if ($this->db->rowCount > 0) {
        return shcol('0.confirm_token',$confrimTableInfo);
     }
     
      if (!$this->db->insert( 'confirm_table', [
         'staff_id' => $id,
         'confirm_token' => $randstr,
         'create_date' => date('Y-m-d h:i:s')
      ])) {
         return false;
      }

      return $randstr;
   }

   public function checkToken( $token )
   {

      $userToConfirm = $this->db
                ->from('confirm_table')
                ->where([
                  'confirm_token' => $token
                ])->get();

     if ( $userToConfirm === false ) {
        return false;
     }

     return shcol('0.staff_id',$userToConfirm); 
    
   }


   public function checkIfStaffNeedsConfirm( $staffId )
   {
    
      $staffData =  $this->db
                ->from('staff')
                ->where([
                  'id' => $staffId
                ])->get();
      if (shcol('0.confirmed',$staffData) == 0) {
         return true;
      }

        return false;
      
   }

   public function confirm( $staffId )
   {
     // Delete staff from confirm_table
     $delQuery = $this->db->delete('confirm_table',[
        'staff_id' => $staffId
     ]);
     

     //update staff confirmed status
     $updateQuery = $this->db->where([
       'id' => 3 
     ])->update('staff',[
       'confirmed' => 1,
     ]);
     
     return ($delQuery && $updateQuery);
      
   }

   public function getRole ( $userId ) 
   {
      $userRole = $this->db
           ->select('role_name')
          ->from('roles')
          ->where([
            'id' => $userId
          ])->first('objects');


echo '<pre>'.print_R($userRole,1).'</pre>'; 
   }


   public function authenticate( array $request)
   {

      // grab user with given credentials
      $request['password'] = hash('sha256',trim(shcol('password',$request)));
      $request['confirmed'] = 1;

      $staffUser = $this->db->select('*')
               ->from('staff')
               ->where( $request )
               ->get();
      
      if (!$staffUser) {
        return false;
      } 
      
     return shcol('0',$staffUser);

   }
}