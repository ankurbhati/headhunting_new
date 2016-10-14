<?php
/**
 * MassMail.php
 *
 * This file contatins Model class to provide Data Logic for all Client Table
 *
 * @category   Models
 * @package    MassMail
 * @version    SVN: <svn_id>
 * @since      02nd Sept 2016
 */

/**
 * Model class to provide Data Logic
 *
 * @category   Models
 * @package    MassMail
 *
 */
class MassMail extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mass_mails';

    /**
     *
     * countries : Relation between mail_mass & mail_group.
     *
     * @return Object belongs to Relation mail_mass mail_group.
     */
    public function mailgroup() {
    
    	return $this->belongsTo('MailGroup','mail_group_id','id');
    }

    /**
     *
     * countries : Relation between mail & user.
     *
     * @return Object belongs to Relation mail & user..
     */
    public function sendby() {
    
        return $this->belongsTo('User','send_by','id');
    }
}
