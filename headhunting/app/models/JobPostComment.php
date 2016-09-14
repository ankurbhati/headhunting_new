<?php
/**
 * JobPostComment.php
 *
 * This file contatins Model class to provide Data Logic for all JobPost Comments
 *
 * @category   Models
 * @package    JobPostComment
 * @version    SVN: <svn_id>
 * @since      8th Sept 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    JobPost
 *
 */
class JobPostComment extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_post_comments';

    /**
     *
     * @param $timestamp : Disabling Time Stamp to False for stoping Auto Updating time.
     */
    public $timestamps = false;

    /**
     *
     * user : Relation between User Sales.
     *
     * @return Object belongs to Relation User.
     */
    public function user() {

    	return $this->belongsTo('User','added_by','id')->select(array('id', 'first_name', 'last_name'));
    }

    /**
     *
     * requirement : Relation between Job Posts.
     *
     * @return Object belongs to Relation Job Post.
     */
    public function requirement() {

        return $this->belongsTo('JobPost','job_post_id','id');
    }
}
