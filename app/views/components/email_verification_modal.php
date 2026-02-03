<!-- Modal Verifikasi Email -->
<div class="modal fade" id="modalVerifikasiEmail" tabindex="-1" aria-labelledby="modalVerifikasiEmailLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle" style="width: 80px; height: 80px; font-size: 40px;">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2">Verifikasi Email Baru</h4>
                <p class="text-muted mb-4">Kami telah mengirimkan kode verifikasi 6-digit ke email baru Anda. Silakan masukkan kode tersebut di bawah ini:</p>
                
                <form id="formVerifikasiEmail">
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                        <input type="text" class="form-control text-center fw-bold fs-3 otp-input" maxlength="1" style="width: 50px; height: 60px; border-radius: 12px;" required>
                    </div>
                    
                    <input type="hidden" id="fullOtpCode" name="otp">
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-3" id="btnConfirmEmailChange" style="border-radius: 12px; background: linear-gradient(135deg, #1e3a8a, #3b82f6); border: none;">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                        Konfirmasi Perubahan
                    </button>
                    
                    <p class="text-muted small">
                        Tidak menerima kode? 
                        <a href="javascript:void(0)" class="text-primary fw-bold text-decoration-none" id="btnResendEmailToken">Kirim Ulang</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.otp-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const fullOtpInput = document.getElementById('fullOtpCode');
    
    // Auto focus next input
    otpInputs.forEach((input, index) => {
        input.addEventListener('keyup', (e) => {
            if (e.key >= 0 && e.key <= 9) {
                if (index < otpInputs.length - 1) otpInputs[index + 1].focus();
            } else if (e.key === 'Backspace') {
                if (index > 0) otpInputs[index - 1].focus();
            }
            
            // Collect full code
            let code = '';
            otpInputs.forEach(inp => code += inp.value);
            fullOtpInput.value = code;
        });
    });
});
</script>
