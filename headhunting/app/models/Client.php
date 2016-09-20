<?php
/**
 * Client.php
 *
 * This file contatins Model class to provide Data Logic for all Client Table
 *
 * @category   Models
 * @package    Client
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    Client
 *
 */
class Client extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';


    /**
     *
     * countries : Relation between client & company.
     *
     * @return Object belongs to Relation client company.
     */
    public function createdby() {
    
    	return $this->belongsTo('User','created_by','id');
    }

}
