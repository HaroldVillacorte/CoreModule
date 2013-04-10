<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_email_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        // Load the database class.
        $this->load->database();
    }

    /**
     * Update the system settings.
     *
     * @param array $post
     * @return boolean
     */
    public function system_settings_set($post = array())
    {
        unset($post['submit']);

        $post = prep_post($post);

        $result = $this->db->where('core_email_id', 1)->limit(1)->update('core_email', $post);

        return ($result) ? TRUE : FALSE;
    }

    /**
     * Get the system email settings.
     *
     * @return object
     */
    public function system_settings_get($return_password = FALSE)
    {
        switch ($return_password)
        {
            case TRUE:
                $select = 'core_email_Host, core_email_Port, core_email_SMTPAuth,
                          core_email_SMTPSecure, core_email_Username,
                          core_email_Password, core_email_From, core_email_FromName,
                          core_email_reply_to, core_email_reply_to_name';
                break;
            case FALSE:
                $select = 'core_email_Host, core_email_Port, core_email_SMTPAuth,
                          core_email_SMTPSecure, core_email_Username, core_email_From,
                          core_email_FromName, core_email_reply_to, core_email_reply_to_name';
                break;
        }
        $result = $this->db
            ->select($select)
            ->get_where('core_email', array('core_email_id' => 1), 1);

        return ($result->num_rows() > 0) ? $result->row() : FALSE;
    }

}

/* End of file core_email_model.php */
