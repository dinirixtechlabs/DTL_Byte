// LOGIN TAB
function showLogin(){
document.getElementById("authModal").style.display = "flex";

document.getElementById("loginForm").style.display = "block";
document.getElementById("signupForm").style.display = "none";

let forgot = document.getElementById("forgotForm");
if(forgot) forgot.style.display = "none";
}

// SIGNUP TAB
function showSignup(){
document.getElementById("authModal").style.display = "flex";

document.getElementById("loginForm").style.display = "none";
document.getElementById("signupForm").style.display = "block";

let forgot = document.getElementById("forgotForm");
if(forgot) forgot.style.display = "none";
}

// CLOSE MODAL
function closeModal(){
document.getElementById("authModal").style.display="none";
}

// FORGOT PASSWORD
function showForgot(){
document.getElementById("authModal").style.display="flex";

document.getElementById("loginForm").style.display="none";
document.getElementById("signupForm").style.display="none";

let forgot = document.getElementById("forgotForm");
if(forgot) forgot.style.display="block";
}

// SHOW / HIDE PASSWORD
function togglePassword(icon){
const input = icon.previousElementSibling;

if(input.type === "password"){
input.type = "text";
icon.innerText = "🙈";
}
else{
input.type = "password";
icon.innerText = "👁️";
}
}

// SIDEBAR TOGGLE
function toggleSidebar(){
const sidebar = document.querySelector(".sidebar");
if(sidebar){
sidebar.classList.toggle("active");
}
}

// CLOSE SIDEBAR WHEN CLICK OUTSIDE
document.addEventListener("click", function(e){

const sidebar = document.querySelector(".sidebar");
const btn = document.querySelector(".menu-btn");

if(sidebar && btn){
if(!sidebar.contains(e.target) && !btn.contains(e.target)){
sidebar.classList.remove("active");
}
}

});