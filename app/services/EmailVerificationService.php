<?php

class EmailVerificationService
{
    private $db;
    private $mailer;

    public function __construct()
    {
        $this->db = new Database();
        if (!class_exists('Mailer')) {
            require_once __DIR__ . '/../core/Mailer.php';
        }
        $this->mailer = new Mailer();
    }

    /**
     * Generate and send a verification code to the new email.
     */
    public function sendVerificationCode($userId, $newEmail, $userName)
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiredAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Clear previous requests for this user
        $this->db->query("DELETE FROM email_verifications WHERE user_id = :user_id");
        $this->db->bind('user_id', $userId);
        $this->db->execute();

        // Save to database
        $this->db->query("INSERT INTO email_verifications (user_id, new_email, token, expired_at) 
                          VALUES (:user_id, :new_email, :token, :expired_at)");
        $this->db->bind('user_id', $userId);
        $this->db->bind('new_email', $newEmail);
        $this->db->bind('token', $code);
        $this->db->bind('expired_at', $expiredAt);

        if ($this->db->execute()) {
            // Send email
            return $this->mailer->sendEmailChangeCode($newEmail, $userName, $code);
        }

        return false;
    }

    /**
     * Verify the code and return the new email if valid.
     */
    public function verifyCode($userId, $code)
    {
        $code = trim($code);
        $currentTime = date('Y-m-d H:i:s');
        $this->db->query("SELECT * FROM email_verifications 
                          WHERE user_id = :user_id AND token = :token AND expired_at > :current_time 
                          ORDER BY created_at DESC LIMIT 1");
        $this->db->bind('user_id', $userId);
        $this->db->bind('token', $code, PDO::PARAM_STR);
        $this->db->bind('current_time', $currentTime);
        
        $result = $this->db->single();

        if ($result) {
            return $result['new_email'];
        }
        return false;
    }

    /**
     * Clear verification entry after successful update.
     */
    public function clearVerificationCode($userId)
    {
        $this->db->query("DELETE FROM email_verifications WHERE user_id = :user_id");
        $this->db->bind('user_id', $userId);
        $this->db->execute();
    }
}
