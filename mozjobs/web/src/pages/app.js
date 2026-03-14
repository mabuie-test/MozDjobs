async function api(path, options={}){
  const response = await fetch('http://localhost:8080/api'+path, options);
  return response.json();
}

function saveToken(token){ localStorage.setItem('mozjobs_token', token); }
function token(){ return localStorage.getItem('mozjobs_token') || ''; }
function authHeaders(){ return token() ? {Authorization: 'Bearer '+token()} : {}; }
