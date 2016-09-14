<?php
/**
 * UserPeer.php
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
class UserPeer extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_peer';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;

    /**
     *
     * state : Relation between User & Peers.
     *
     * @return Object belongs to Relation User Peers.
     */
    public function peer() {
    
    	return $this->belongsTo('User','user_id','id');
    }
}