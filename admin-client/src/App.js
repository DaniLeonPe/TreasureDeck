import React, { useState, useEffect } from "react";
import Login from "./components/Login";
import AdminPanel from "./components/AdminPanel";

export default function App() {
  const [token, setToken] = useState(null);

  // Al montar el componente, revisamos si hay token guardado
  useEffect(() => {
    const savedToken = localStorage.getItem("token");
    if (savedToken) {
      setToken(savedToken);
    }
  }, []);

  // Cuando login es exitoso, guardamos token y actualizamos estado
  const handleLogin = (newToken) => {
    setToken(newToken);
  };

  // Cuando logout: limpiamos token y estado
  const handleLogout = () => {
    localStorage.removeItem("token");
    setToken(null);
  };

  return (
    <>
      {token ? (
        <AdminPanel onLogout={handleLogout} />
      ) : (
        <Login onLogin={handleLogin} />
      )}
    </>
  );
}
