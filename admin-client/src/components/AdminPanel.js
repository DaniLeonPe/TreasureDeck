import React from "react";
import UserList from "./UserList";

export default function AdminPanel({ onLogout }) {
  const handleLogout = () => {
    localStorage.removeItem("token");
    if (onLogout) onLogout(); 
  };

  return (
    <div style={{ maxWidth: 800, margin: "20px auto", fontFamily: "Arial, sans-serif" }}>
      <header style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: 20 }}>
        <h1>Panel Administrativo</h1>
        <button
          onClick={handleLogout}
          style={{
            padding: "8px 16px",
            backgroundColor: "#d9534f",
            color: "white",
            border: "none",
            borderRadius: 4,
            cursor: "pointer",
          }}
        >
          Cerrar sesión
        </button>
      </header>
      <UserList />
    </div>
  );
}
