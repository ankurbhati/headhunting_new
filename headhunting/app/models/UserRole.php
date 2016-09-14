<?php
/**
 * UserRole.php
 *
 * This file contatins Model class to provide Data Logic for all User UserRole Table
 *
 * @category   Models
 * @package    User
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    User
 *
 */
class UserRole extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_roles';
    
    /**
     *
     * roles : roles for the User.
     *
     * @return Object belongsTo Relation Roles
     */
    public function roles() {

    	return $this->belongsTo('Role','role_id','id');
    }
    
    /**
     *
     * state : Relation between User & State.
     *
     * @return Object belongs to Relation User State.
     */
    public function user() {
    
    	return $this->belongsTo('User','user_id','id');
    }
}