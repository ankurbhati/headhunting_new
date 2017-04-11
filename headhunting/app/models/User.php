<?php
/**
 * User.php
 *
 * This file contatins Model class to provide Data Logic for all User Table
 *
 * @category   Models
 * @package    User
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

use Illuminate\Auth\UserInterface;

use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    User
 *
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', "reset_hash", "remember_token");

    //Relation Start

    /**
     *
     * userRoles : Relation between User Roles.
     *
     * @return Object hasMany Relation User Roles.
     */
    public function userRoles() {
    
    	return $this->hasMany('UserRole','user_id','id');
    }
    
    
    /**
     *
     * countries : Relation between User & country.
     *
     * @return Object belongs to Relation User Country.
     */
    public function country() {
    
    	return $this->belongsTo('Country','country_id','id');
    }

    /**
     *
     * state : Relation between User & State.
     *
     * @return Object belongs to Relation User State.
     */
    public function state() {
    
    	return $this->belongsTo('State','state_id','id');
    }

    public function getRole() {
    	return $this->userRoles[0]->roles->id;
    }
    
    public function hasRole($roleId) {
    	return UserRole::where('user_id', '=', $this->id)
    	->where('role_id', '=', $roleId)
    	->exists();
    }
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()    {
    
    	return $this->getKey();
    }
    
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
    
    	return $this->password;
    }
    
    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
    
    	return $this->remember;
    }
    
    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value) {
    
    	$this->remember = $value;
    }
    
    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
    
    	return 'remember';
    }
    
    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
    
    	return $this->email;
    }

    public function thirdpartyuser()
    {
        return $this->hasMany('Thirdpartyuser');
    }

}