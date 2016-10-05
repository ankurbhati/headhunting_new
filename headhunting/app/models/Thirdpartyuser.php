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
class Thirdpartyuser extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'third_party_users';


    /**
     *
     * jobsAssigned : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function vendors() {
        return $this->hasMany('Thirdparty','id','source_id');
    }

}
