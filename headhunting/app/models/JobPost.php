<?php
/**
 * JobPost.php
 *
 * This file contatins Model class to provide Data Logic for all JobPost Table
 *
 * @category   Models
 * @package    JobPost
 * @version    SVN: <svn_id>
 * @since      27th Feb 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    JobPost
 *
 */
class JobPost extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_posts';

    /**
     *
     * user : Relation between User Sales & Job Posts.
     *
     * @return Object belongs to Relation User Job Posts.
     */
    public function user() {

    	return $this->belongsTo('User','created_by','id');
    }


    /**
     *
     * countries : Relation between Job Posts & country.
     *
     * @return Object belongs to Relation User Country.
     */
    public function country() {

    	return $this->belongsTo('Country','country_id','id');
    }

    /**
     *
     * state : Relation between Job Posts & State.
     *
     * @return Object belongs to Relation User State.
     */
    public function state() {

    	return $this->belongsTo('State','state_id','id');
    }

    /**
     *
     * jobsAssigned : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function candidateApplications() {

    	return $this->hasMany('CandidateApplication','job_post_id','id')->count();
    }
    
    /**
     *
     * jobsAssigned : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function candidateApplication() {

    	return $this->hasMany('CandidateApplication','job_post_id','id');
    }

    /**
     *
     * jobsAssigned : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function jobsAssigned() {

        return $this->hasMany('JobPostAssignment','job_post_id','id');
    }


    /**
     *
     * jobsAssigned : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function jobsAssignedToMe() {

    	return $this->hasMany('JobPostAssignment','job_post_id','id')->where('assigned_to_id', '=', Auth::user()->id);
    }

    /**
     *
     * jobsAssignedtoId : Relation between jobs Assigned.
     *
     * @return Object hasMany Relation jobs Assigned.
     */
    public function jobsAssignedToId($id) {

    	return $this->hasMany('JobPostAssignment','job_post_id','id')->where('assigned_to_id', '=', $id);
    }

    /**
     *
     * client : Relation between Client & Job Posts.
     *
     * @return Object belongs to Relation User Job Posts.
     */
    public function client() {

        return $this->belongsTo('Client','client_id','id');
    }

    /**
     *
     * comments : Relation between comments & Job Posts.
     *
     * @return Object belongs to Relation User Job Posts comments.
     */
    public function comments() {

        return $this->hasMany('JobPostComment','job_post_id','id');
    }

    /**
     *
     * countries : Relation between Job Posts & country.
     *
     * @return Object belongs to Relation User Country.
     */
    public function city() {

        return $this->belongsTo('City','city_id','id');
    }

    public function getAssignedNames() {

        $jobsAssigned = $this->jobsAssigned;
        $names = "";
        $ids = array();
        $jobSubmittels = $this->candidateApplication;
        foreach($jobSubmittels as $key => $submittel) {
            array_push($ids, $submittel->submitted_by);
        }

        foreach($jobsAssigned as $key => $value) {
            if($key > 0) {
                $names = $names.", ".$value->assignedTo->first_name. " ".$value->assignedTo->last_name;
                if(Auth::user()->hasRole(1)) {
                    $names = $names."<a href='/remove-assign-requirement/$value->id'><img style='width:16px; margin:0 3px;' src='/dist/img/x-button.png' title='Remove Assignment'></a>";
                }
            } else {
                $names = $value->assignedTo->first_name. " ".$value->assignedTo->last_name;
                if($value->assignedTo->id && Auth::user()->hasRole(1)  && !in_array($value->assignedTo->id, $ids) ) {
                    $names = $names."<a href='/remove-assign-requirement/$value->id'><img style='width:16px; margin:0 3px;' src='/dist/img/x-button.png' title='Remove Assignment'></a>";
                }
            }
        }
        return $names;
    }

    public function getClass() {
        $status = Config::get('notification.job_post_class')[$this->status];
        if($this->status == 2) {
            if($this->candidateApplications() > 0) {
                $status = "job-submittels";
            }
        }
        return $status;
    }
}
