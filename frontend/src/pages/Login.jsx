// src/pages/Login.jsx
import { useState, useEffect } from 'react';
import axios from 'axios';
// Send cookies (sanctum XSRF-TOKEN / session) with requests by default
axios.defaults.withCredentials = true;
import { useNavigate } from 'react-router-dom';

export default function Login() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [companyCode, setCompanyCode] = useState('');
  const [companies, setCompanies] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    const fetchCompanies = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/companies');
        setCompanies(response.data);
      } catch (err) {
        console.error('Failed to fetch companies:', err);
        setError('Failed to load company list. Check if Laravel is running.');
      } finally {
        setLoading(false);
      }
    };
    fetchCompanies();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    try {
      // Ensure the CSRF cookie is set (Sanctum SPA flow)
      await axios.get('http://localhost:8000/sanctum/csrf-cookie');
      // Read XSRF-TOKEN cookie and set X-XSRF-TOKEN header explicitly
      const match = document.cookie.match(new RegExp('(^|; )XSRF-TOKEN=([^;]+)'));
      if (match) {
        // cookie is URL-encoded
        const xsrf = decodeURIComponent(match[2]);
        axios.defaults.headers.common['X-XSRF-TOKEN'] = xsrf;
      }
      const res = await axios.post('http://localhost:8000/login', {
        username,
        password,
        company_code: companyCode,
      });

      // Store session user and company returned by server
      localStorage.setItem('user', JSON.stringify(res.data.user));
      localStorage.setItem('company', JSON.stringify(res.data.company));

      // Apply theme
  document.documentElement.style.setProperty('--primary', res.data.company.primary_color);
  document.documentElement.style.setProperty('--accent', res.data.company.accent_color || '#ccc');

      navigate('/dashboard');
    } catch (err) {
      setError(err.response?.data?.error || 'Login failed');
    }
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <h2>Company Portal</h2>

        {error && <div className="error">{error}</div>}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="form-group">
            <label>Username</label>
            <input
              type="text"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
            />
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>

          <div className="form-group">
            <label>Company</label>
            {loading ? (
              <div className="w-full px-3 py-2 border border-gray-300 rounded-md animate-pulse">Loading...</div>
            ) : (
              <select
                value={companyCode}
                onChange={(e) => setCompanyCode(e.target.value)}
                required
              >
                <option value="">Select Company</option>
                {companies.map((company) => (
                  <option key={company.code} value={company.code}>
                    {company.name}
                  </option>
                ))}
              </select>
            )}
          </div>

          <button type="submit" className="btn">
            Login
          </button>
        </form>
      </div>
    </div>
  );
}
