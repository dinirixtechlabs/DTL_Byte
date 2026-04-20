/* ================= MODAL HELPERS ================= */

function openModal(){
    const modal = document.getElementById("authModal");
    if(modal) modal.style.display = "flex";
}

function closeModal(){
    const modal = document.getElementById("authModal");
    if(modal) modal.style.display = "none";
}

/* ================= TAB SWITCHING ================= */

function showLogin(){
    openModal();
    toggleForms("loginForm");
}

function showSignup(){
    openModal();
    toggleForms("signupForm");
}

function showForgot(){
    openModal();
    toggleForms("forgotForm");
}

function toggleForms(activeId){
    const forms = ["loginForm", "signupForm", "forgotForm"];
    forms.forEach(id => {
        const el = document.getElementById(id);
        if(el) el.style.display = (id === activeId) ? "block" : "none";
    });
}

function closeReset(){
    const reset = document.getElementById("resetModal");
    if(reset) reset.style.display = "none";
}

/* ================= PASSWORD TOGGLE ================= */

function togglePassword(icon){
    const input = icon.previousElementSibling;
    if(!input) return;

    input.type = (input.type === "password") ? "text" : "password";
    icon.innerText = (input.type === "password") ? "👁️" : "🙈";
}

/* ================= SIDEBAR ================= */

function toggleSidebar(){
    const sidebar = document.querySelector(".sidebar");
    if(sidebar) sidebar.classList.toggle("active");
}

document.addEventListener("click", function(e){
    const sidebar = document.querySelector(".sidebar");
    const btn = document.querySelector(".menu-btn");

    if(sidebar && btn){
        if(!sidebar.contains(e.target) && !btn.contains(e.target)){
            sidebar.classList.remove("active");
        }
    }
});

/* ================= BLOCK OUTSIDE CLICK ================= */

window.addEventListener("click", function(e){
    const authModal = document.getElementById("authModal");
    const resetModal = document.getElementById("resetModal");

    if(authModal && e.target === authModal){}
    if(resetModal && e.target === resetModal){}
});

/* ================= AUTO OPEN VERIFY ================= */

window.addEventListener("load", function(){
    const params = new URLSearchParams(window.location.search);

    if(params.has("verify")){
        openModal();
        toggleForms("signupForm");
    }
});

/* ========================================================= */
/* ================= SIGNUP OTP SYSTEM ====================== */
/* ========================================================= */

let otpTime = 60;
let otpInterval;

/* SEND OTP */
function sendOTP(){
    const email = document.getElementById("signup_email").value;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailPattern.test(email)){
    alert("Enter valid email");
    return;
    }

    fetch("send_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "email=" + encodeURIComponent(email)
    })
    .then(res => res.text())
    .then(data => {
        data = data.trim();

        if(data === "sent"){
            alert("OTP sent ✅");
            document.getElementById("otp_section").style.display = "block";
            startOTPTimer();
        }
        else if(data === "exists"){
            alert("Email already registered ❌");
        }
        else if(data === "invalid"){
            alert("Invalid email ❌");
        }
        else{
            alert("Failed to send OTP ❌");
        }
    });
}

/* VERIFY OTP */
function verifyOTP(){
    const otp = document.getElementById("otp").value;

    if(!/^[0-9]{6}$/.test(otp)){
    alert("Enter valid 6-digit OTP");
    return;
}
    fetch("verify_signup_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "otp=" + encodeURIComponent(otp)
    })
    .then(res => res.text())
    .then(data => {
    data = data.trim();

    if(data === "success"){
        alert("Email Verified ✅");

        document.getElementById("signup_password").disabled = false;
        document.getElementById("signup_btn").disabled = false;
        document.getElementById("signup_email").readOnly = true;

        clearInterval(otpInterval);
        document.getElementById("otp_timer").innerText = "";

    }else if(data === "expired"){
        alert("OTP Expired ⏳ Please resend");

    }else if(data === "session_expired"){
        alert("Session expired. Please try again.");

    }else if(data === "invalid"){
        alert("Invalid OTP format");

    }else{
        alert("Wrong OTP ❌");
    }
});
}

/* OTP TIMER */
function startOTPTimer(){
    clearInterval(otpInterval);
    otpTime = 60;

    const timer = document.getElementById("otp_timer");
    const resendBtn = document.getElementById("resend_btn");

    if(resendBtn) resendBtn.disabled = true;

    otpInterval = setInterval(() => {
        otpTime--;

        if(timer){
            timer.innerText = "Resend OTP in " + otpTime + " sec";
        }

        if(otpTime <= 0){
            clearInterval(otpInterval);
            if(timer) timer.innerText = "";
            if(resendBtn) resendBtn.disabled = false;
        }

    }, 1000);
}

/* RESEND OTP (FIXED) */
function resendOTP(){
    const email = document.getElementById("signup_email").value;

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailPattern.test(email)){
    alert("Enter valid email");
    return;
    }

    fetch("send_otp.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "email=" + encodeURIComponent(email) + "&resend=1"
    })
    .then(res => res.text())
    .then(data => {
        data = data.trim();

        if(data === "resent"){
            alert("OTP Resent ✅");
            startOTPTimer();
        }else{
            alert("Resend failed ❌");
        }
    });
}

/* ========================================================= */
/* ================= FORGOT OTP SYSTEM ====================== */
/* ========================================================= */

let forgotTime = 60;
let forgotInterval;

function startForgotTimer(){
    clearInterval(forgotInterval);
    forgotTime = 60;

    const timer = document.getElementById("forgot_timer");
    const resendBtn = document.getElementById("forgot_resend");

    if(resendBtn) resendBtn.disabled = true;

    forgotInterval = setInterval(() => {
        forgotTime--;

        if(timer){
            timer.innerText = "Resend OTP in " + forgotTime + " sec";
        }

        if(forgotTime <= 0){
            clearInterval(forgotInterval);
            if(timer) timer.innerText = "";
            if(resendBtn) resendBtn.disabled = false;
        }

    }, 1000);
}

/* RESEND FORGOT OTP */
function resendForgotOTP(){
    fetch("forgot_password_process.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "resend=1"
    })
    .then(res => res.text())
    .then(data => {
        data = data.trim();

        if(data === "resent"){
            alert("OTP Resent ✅");
            startForgotTimer();

        }else if(data === "session_expired"){
            alert("Session expired. Restart process ❌");

        }else{
            alert("Resend failed ❌");
        }
    });
}