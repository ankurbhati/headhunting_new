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
 * @package    Vendor
 *
 */
class Vendor extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendors';

    /**
     *
     * countries : Relation between vendor & company.
     *
     * @return Object belongs to Relation vendor company.
     */
    public function createdby() {
    
    	return $this->belongsTo('User','created_by','id');
    }

}