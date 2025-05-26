import React, { useState } from "react";
import axios from "axios";
import { API_BASE_URL } from "../services/api";

export default function Login({ onLogin }) {
  const [name, setName] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);

    try {
      const res = await axios.post(`${API_BASE_URL}/login`, { name, password });
      const token = res.data.token;
      localStorage.setItem("token", token);
      onLogin(token);
    } catch {
      setError("Credenciales inválidas");
    }
  };

  return (
    <form
      onSubmit={handleSubmit}
      style={{
        maxWidth: 360,
        margin: "100px auto",
        padding: 20,
        border: "1px solid #ccc",
        borderRadius: 6,
        boxShadow: "0 2px 8px rgba(0,0,0,0.1)",
        fontFamily: "Arial, sans-serif",
      }}
    >
      <h2 style={{ textAlign: "center", marginBottom: 20 }}>Iniciar sesión</h2>
      {error && (
        <p style={{ color: "red", textAlign: "center", marginBottom: 10 }}>{error}</p>
      )}
      <input
        type="text"
        placeholder="Usuario"
        value={name}
        onChange={(e) => setName(e.target.value)}
        required
        style={{
          width: "100%",
          marginBottom: 15,
          padding: 10,
          borderRadius: 4,
          border: "1px solid #ccc",
          fontSize: 16,
        }}
      />
      <input
        type="password"
        placeholder="Contraseña"
        value={password}
        onChange={(e) => setPassword(e.target.value)}
        required
        style={{
          width: "100%",
          marginBottom: 20,
          padding: 10,
          borderRadius: 4,
          border: "1px solid #ccc",
          fontSize: 16,
        }}
      />
      <button
        type="submit"
        style={{
          width: "100%",
          padding: 12,
          fontSize: 16,
          backgroundColor: "#0275d8",
          color: "white",
          border: "none",
          borderRadius: 4,
          cursor: "pointer",
        }}
      >
        Entrar
      </button>
    </form>
  );
}
