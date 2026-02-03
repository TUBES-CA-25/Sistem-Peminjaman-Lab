<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public function sendResetEmail($toEmail, $toName, $resetLink)
    {
        try {
            $mail = new PHPMailer(true);

            // ✅ GMAIL SMTP CONFIGURATION (dari .env)
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Password - Sistem Peminjaman Lab';
            $mail->Body = $this->getResetEmailTemplate($toName, $resetLink);
            $mail->AltBody = "Halo {$toName},\n\nKlik link berikut untuk reset password Anda:\n$resetLink\n\nLink berlaku 1 jam.";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    private function getResetEmailTemplate($nama, $resetLink)
    {
        return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; padding: 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 40px 30px; color: #333; }
            .content p { line-height: 1.6; margin-bottom: 20px; }
            .btn { display: inline-block; background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white !important; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: bold; margin: 20px 0; }
            .btn:hover { opacity: 0.9; }
            .footer { background: #0f172a; padding: 20px; text-align: center; color: rgba(255,255,255,0.7); font-size: 12px; }
            .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; margin: 20px 0; border-radius: 4px; color: #92400e; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🔒 Reset Password</h1>
            </div>
            <div class='content'>
                <p>Halo <strong>$nama</strong>,</p>
                <p>Kami menerima permintaan untuk reset password akun Anda di <strong>ICLABS - Sistem Peminjaman Laboratorium</strong>.</p>
                <p>Klik tombol di bawah ini untuk membuat password baru:</p>
                <center>
                    <a href='$resetLink' class='btn' style='color: white;'>Reset Password Saya</a>
                </center>
                <div class='warning'>
                    ⚠️ <strong>Penting:</strong> Link ini hanya berlaku selama <strong>1 jam</strong> dan hanya bisa digunakan sekali.
                </div>
                <p>Jika Anda tidak meminta reset password, abaikan email ini. Akun Anda tetap aman.</p>
                <p>Terima kasih,<br><strong>Tim ICLABS</strong></p>
            </div>
            <div class='footer'>
                <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
                <p>&copy; 2026 ICLABS. All Rights Reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    }
    public function sendOTPEmail($toEmail, $toName, $otp)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Kode Verifikasi Akun - ICLABS';
            $mail->Body = $this->getOTPEmailTemplate($toName, $otp);
            $mail->AltBody = "Halo $toName, kode verifikasi Anda adalah: $otp";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("OTP Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function getOTPEmailTemplate($nama, $otp)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Inter', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
                .header { background: linear-gradient(135deg, #1e3a8a, #1F45AC); color: white; padding: 40px 20px; text-align: center; }
                .content { padding: 40px; text-align: center; color: #334155; }
                .otp-box { background: #f1f5f9; padding: 20px; border-radius: 12px; font-size: 32px; font-weight: bold; letter-spacing: 12px; color: #1e3a8a; margin: 30px 0; border: 2px dashed #cbd5e1; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;'>🔐 Verifikasi Akun</h1>
                </div>
                <div class='content'>
                    <p>Halo <strong>$nama</strong>,</p>
                    <p>Terima kasih telah mendaftar di <strong>ICLABS</strong>. Silakan masukkan kode verifikasi berikut untuk mengaktifkan akun Anda:</p>
                    <div class='otp-box'>$otp</div>
                    <p style='font-size: 14px; color: #64748b;'>Kode ini berlaku selama 15 menit. Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2026 Tim ICLABS. All Rights Reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    public function sendEmailChangeCode($toEmail, $toName, $otp)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Perubahan Email - ICLABS';
            $mail->Body = $this->getEmailChangeTemplate($toName, $otp);
            $mail->AltBody = "Halo $toName, kode verifikasi perubahan email Anda adalah: $otp";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email Change Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    private function getEmailChangeTemplate($nama, $otp)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Inter', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
                .container { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
                .header { background: linear-gradient(135deg, #1e3a8a, #d63384); color: white; padding: 40px 20px; text-align: center; }
                .content { padding: 40px; text-align: center; color: #334155; }
                .otp-box { background: #fdf2f8; padding: 20px; border-radius: 12px; font-size: 32px; font-weight: bold; letter-spacing: 12px; color: #be185d; margin: 30px 0; border: 2px dashed #f9a8d4; }
                .footer { background: #f8fafc; padding: 20px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1 style='margin:0;'>📧 Verifikasi Email Baru</h1>
                </div>
                <div class='content'>
                    <p>Halo <strong>$nama</strong>,</p>
                    <p>Kami menerima permintaan untuk mengubah alamat email akun Anda. Silakan masukkan kode verifikasi berikut untuk mengonfirmasi email baru ini:</p>
                    <div class='otp-box'>$otp</div>
                    <p style='font-size: 14px; color: #64748b;'>Kode ini berlaku selama 15 menit. Jika Anda tidak merasa melakukan perubahan ini, abaikan email ini dan segera amankan akun Anda.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2026 Tim ICLABS. All Rights Reserved.</p>
                </div>
            </div>
        </body>
        </html>";
    }
}
