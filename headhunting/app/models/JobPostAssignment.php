<?php
/**
 * JobPostAssignment.php
 *
 * This file contatins Model class to provide Data Logic for all JobPostAssignment Table
 *
 * @category   Models
 * @package    JobPostAssignment
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    JobPostAssignment
 *
 */
class JobPostAssignment extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_post_assignments';

    /**
     *
     * assignedTo : Relation between Job Posts & User.
     *
     * @return Object belongs to Relation User JP.
     */
    public function assignedTo() {

        return $this->belongsTo('User','assigned_to_id','id')->select(array('id', 'first_name', 'last_name', 'email'));
    }
}
