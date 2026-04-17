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

const login = document.getElementById("loginForm");
const signup = document.getElementById("signupForm");
const forgot = document.getElementById("forgotForm");

if(login) login.style.display = "block";
if(signup) signup.style.display = "none";
if(forgot) forgot.style.display = "none";
}

function showSignup(){
openModal();

const login = document.getElementById("loginForm");
const signup = document.getElementById("signupForm");
const forgot = document.getElementById("forgotForm");

if(login) login.style.display = "none";
if(signup) signup.style.display = "block";
if(forgot) forgot.style.display = "none";
}

function showForgot(){
openModal();

const login = document.getElementById("loginForm");
const signup = document.getElementById("signupForm");
const forgot = document.getElementById("forgotForm");

if(login) login.style.display = "none";
if(signup) signup.style.display = "none";
if(forgot) forgot.style.display = "block";
}

function closeReset(){
const reset = document.getElementById("resetModal");
if(reset){
reset.style.display = "none";
}
}

/* ================= PASSWORD TOGGLE ================= */

function togglePassword(icon){
const input = icon.previousElementSibling;

if(!input) return;

if(input.type === "password"){
input.type = "text";
icon.innerText = "🙈";
}else{
input.type = "password";
icon.innerText = "👁️";
}
}

/* ================= SIDEBAR ================= */

function toggleSidebar(){
const sidebar = document.querySelector(".sidebar");
if(sidebar){
sidebar.classList.toggle("active");
}
}

/* Close sidebar when clicking outside */
document.addEventListener("click", function(e){

const sidebar = document.querySelector(".sidebar");
const btn = document.querySelector(".menu-btn");

if(sidebar && btn){
if(!sidebar.contains(e.target) && !btn.contains(e.target)){
sidebar.classList.remove("active");
}
}

});

/* ================= CLOSE MODAL ON OUTSIDE CLICK ================= */

window.addEventListener("click", function(e){

const authModal = document.getElementById("authModal");
const resetModal = document.getElementById("resetModal");

// ❌ Do NOTHING (no closing)
if(authModal && e.target === authModal){
    // modal will NOT close
}

if(resetModal && e.target === resetModal){
    // modal will NOT close
}

});