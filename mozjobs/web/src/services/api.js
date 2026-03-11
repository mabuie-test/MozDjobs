const API_BASE = 'http://localhost:8080/api';

export async function api(path, options = {}) {
  const response = await fetch(`${API_BASE}${path}`, options);
  const data = await response.json();
  if (!response.ok) {
    throw new Error(data.error || `HTTP ${response.status}`);
  }
  return data;
}

export function authHeaders(token) {
  return token ? { Authorization: `Bearer ${token}` } : {};
}
