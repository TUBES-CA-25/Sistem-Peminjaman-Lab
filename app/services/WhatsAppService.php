<?php

/**
 * WhatsAppService
 * 
 * Service untuk mengirim notifikasi WhatsApp via Fonnte API.
 * Digunakan untuk mengirim pesan otomatis ke koordinator lab atau admin.
 * 
 * @author System
 * @version 1.0
 */
class WhatsAppService
{
    /** @var int Timeout untuk koneksi API (dalam detik) */
    private const API_TIMEOUT = 30;

    /**
     * Kirim pesan WhatsApp via Fonnte API
     * 
     * @param string $target Nomor tujuan (format 08xx atau 628xx)
     * @param string $pesan Isi pesan
     * @return string Response dari API Fonnte (JSON)
     */
    public function kirimPesanFonnte($target, $pesan)
    {
        // Pastikan token tersedia
        $token = defined('FONNTE_TOKEN') ? FONNTE_TOKEN : '';

        if (empty($token)) {
            // Token tidak dikonfigurasi - kembalikan error
            return json_encode([
                'status' => false, 
                'message' => 'Token WhatsApp tidak dikonfigurasi'
            ]);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.fonnte.com/send',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => self::API_TIMEOUT,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $pesan,
            'countryCode' => '62',
          ),
          CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
          ),
        ));

        $response = curl_exec($curl);
        
        // Handle CURL errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return json_encode([
                'status' => false, 
                'reason' => $error_msg
            ]);
        }
        
        curl_close($curl);
        
        return $response ?? json_encode([
            'status' => false, 
            'reason' => 'Unknown error'
        ]);
    }
}
