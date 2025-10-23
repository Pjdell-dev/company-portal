// src/pages/Dashboard.jsx
import { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

export default function Dashboard() {
  const [modulesData, setModulesData] = useState([]);
  const [filteredData, setFilteredData] = useState([]);
  const [search, setSearch] = useState('');
  const [expandedSystems, setExpandedSystems] = useState(new Set());
  const [expandedModules, setExpandedModules] = useState(new Set());
  const navigate = useNavigate();

  useEffect(() => {
    // Use session cookie-based auth: ensure user is present in storage (set at login)
    const storedUser = localStorage.getItem('user');
    if (!storedUser) {
      navigate('/');
      return;
    }

    // Request modules with credentials (cookies) instead of Authorization header
    axios.get('http://localhost:8000/api/modules', { withCredentials: true })
      .then(res => {
        setModulesData(res.data);
        setFilteredData(res.data);
      }).catch(err => {
        console.error('Failed to load modules:', err);
        // If unauthenticated, clear storage and redirect to login
        if (err.response?.status === 401 || err.response?.status === 419) {
          localStorage.removeItem('user');
          localStorage.removeItem('company');
          navigate('/');
        }
      });
  }, [navigate]);

  useEffect(() => {
    const q = search.toLowerCase();
    if (!q) {
      setFilteredData(modulesData);
      return;
    }
    const result = modulesData.map(sys => {
      const modules = sys.modules.map(mod => {
        const subs = mod.submodules.filter(s => s.name.toLowerCase().includes(q));
        return { ...mod, submodules: subs };
      }).filter(m => m.submodules.length > 0 || mod.module_name.toLowerCase().includes(q));
      return { ...sys, modules };
    }).filter(s => s.modules.length > 0 || s.system_name.toLowerCase().includes(q));
    setFilteredData(result);
  }, [search, modulesData]);

  const toggleSystem = (systemId) => {
    const newExpanded = new Set(expandedSystems);
    if (newExpanded.has(systemId)) {
      newExpanded.delete(systemId);
    } else {
      newExpanded.add(systemId);
    }
    setExpandedSystems(newExpanded);
  };

  const toggleModule = (moduleId) => {
    const newExpanded = new Set(expandedModules);
    if (newExpanded.has(moduleId)) {
      newExpanded.delete(moduleId);
    } else {
      newExpanded.add(moduleId);
    }
    setExpandedModules(newExpanded);
  };

  const handleSubmoduleClick = (sub) => {
    alert(`Navigating to: ${sub.name}`);
  };

  // Get user and company from localStorage
  const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
  const storedCompany = JSON.parse(localStorage.getItem('company') || '{}');

  const handleLogout = async () => {
    try {
      // Use cookie based logout
      await axios.post('http://localhost:8000/api/logout', {}, { withCredentials: true });
    } catch (err) {
      console.warn('Logout failed on server:', err);
    } finally {
      localStorage.removeItem('user');
      localStorage.removeItem('company');
      localStorage.removeItem('token');
      navigate('/');
    }
  };

  return (
    <div className="dashboard">
      {/* Sidebar */}
      <div className="sidebar">
        {/* Header */}
        <div className="sidebar-header">
          <h1>Company Portal</h1>
          {/* Display company and user */}
          <div className="text-sm mt-1 opacity-90">
            Company: {storedCompany.name || 'Unknown'}
          </div>
          <div className="text-xs mt-1 opacity-75">
            User: {storedUser.full_name || 'Guest'}
          </div>
          <div className="mt-3">
            <button className="btn-secondary" onClick={handleLogout}>Logout</button>
          </div>
        </div>

        {/* Search */}
        <div className="search-box">
          <input
            type="text"
            placeholder="Search modules..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>

        {/* Module Tree */}
        <div className="module-tree">
          {filteredData.map(system => (
            <div key={system.system_id} className="system">
              <div
                className="system-name cursor-pointer flex justify-between items-center"
                onClick={() => toggleSystem(system.system_id)}
              >
                <span>{system.system_name}</span>
                <span className="text-sm">
                  {expandedSystems.has(system.system_id) ? '▼' : '▶'}
                </span>
              </div>
              {expandedSystems.has(system.system_id) && (
                <ul className="ml-4 mt-2 space-y-1">
                  {system.modules.map(module => (
                    <li key={module.module_id} className="module-item">
                      <div
                        className="font-medium cursor-pointer flex justify-between items-center"
                        onClick={() => toggleModule(module.module_id)}
                      >
                        <span>{module.module_name}</span>
                        <span className="text-sm">
                          {expandedModules.has(module.module_id) ? '▼' : '▶'}
                        </span>
                      </div>
                      {expandedModules.has(module.module_id) && (
                        <ul className="ml-4 mt-1 space-y-1">
                          {module.submodules.map(sub => (
                            <li
                              key={sub.id}
                              className="submodule"
                              onClick={() => handleSubmoduleClick(sub)}
                            >
                              {sub.name}
                            </li>
                          ))}
                        </ul>
                      )}
                    </li>
                  ))}
                </ul>
              )}
            </div>
          ))}
        </div>
      </div>

      {/* Main Content */}
      <div className="main-content">
        <h2>
          Welcome,{' '}
          <span style={{ color: 'var(--primary)' }}>
            {storedUser.full_name || 'User'}
          </span>
          {' ('}
          {storedCompany.name || 'Company'}
          {')'}
        </h2>
        <p>Click any submodule in the left panel to view its content.</p>
      </div>
    </div>
  );
}
