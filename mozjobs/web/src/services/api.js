async function api(path, options={}) { const res = await fetch(`http://localhost:8080/api${path}`, options); return res.json(); }
