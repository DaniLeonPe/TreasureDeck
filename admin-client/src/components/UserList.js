import React, { useState, useEffect } from "react";
import { fetchUsersByRole, updateUser, deleteUser } from "../services/api";

export default function UserList() {
  const [users, setUsers] = useState([]);
  const [editingUserId, setEditingUserId] = useState(null);
  const [formData, setFormData] = useState({ name: "", email: "" });
  const [error, setError] = useState(null);

  useEffect(() => {
    loadUsers();
  }, []);

  const loadUsers = async () => {
    try {
      const data = await fetchUsersByRole(2); // solo role_id=2
      setUsers(data);
      setError(null);
    } catch {
      setError("Error cargando usuarios");
    }
  };

  const startEdit = (user) => {
    setEditingUserId(user.id);
    setFormData({ name: user.name, email: user.email });
    setError(null);
  };

  const cancelEdit = () => {
    setEditingUserId(null);
    setFormData({ name: "", email: "" });
    setError(null);
  };

  const handleChange = (e) => {
    setFormData((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const saveEdit = async () => {
    if (!formData.name.trim() || !formData.email.trim()) {
      setError("Nombre y correo son obligatorios");
      return;
    }
    try {
      await updateUser(editingUserId, formData);
      await loadUsers();
      cancelEdit();
    } catch {
      setError("Error al actualizar usuario");
    }
  };

  const handleDelete = async (id) => {
    if (window.confirm("¿Eliminar usuario?")) {
      try {
        await deleteUser(id);
        setUsers(users.filter((u) => u.id !== id));
      } catch {
        setError("Error al eliminar usuario");
      }
    }
  };

  return (
    <div
      style={{
        border: "1px solid #ddd",
        borderRadius: 6,
        padding: 20,
        fontFamily: "Arial, sans-serif",
        backgroundColor: "#f9f9f9",
      }}
    >
      <h2 style={{ marginBottom: 15, color: "#333" }}>Usuarios con rol Usuario</h2>
      {error && (
        <p
          style={{
            color: "red",
            marginBottom: 15,
            backgroundColor: "#ffe6e6",
            padding: 10,
            borderRadius: 4,
          }}
        >
          {error}
        </p>
      )}
      <ul style={{ listStyle: "none", padding: 0 }}>
        {users.map((user) => (
          <li
            key={user.id}
            style={{
              marginBottom: 15,
              padding: 15,
              borderRadius: 6,
              backgroundColor: "white",
              boxShadow: "0 1px 3px rgba(0,0,0,0.1)",
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
              flexWrap: "wrap",
              gap: "10px",
            }}
          >
            {editingUserId === user.id ? (
              <>
                <input
                  name="name"
                  value={formData.name}
                  onChange={handleChange}
                  placeholder="Nombre"
                  style={{
                    flexGrow: 1,
                    padding: 8,
                    borderRadius: 4,
                    border: "1px solid #ccc",
                    fontSize: 14,
                  }}
                />
                <input
                  name="email"
                  type="email"
                  value={formData.email}
                  onChange={handleChange}
                  placeholder="Email"
                  style={{
                    flexGrow: 1,
                    padding: 8,
                    borderRadius: 4,
                    border: "1px solid #ccc",
                    fontSize: 14,
                  }}
                />
                <div>
                  <button
                    onClick={saveEdit}
                    style={{
                      marginRight: 8,
                      padding: "6px 12px",
                      backgroundColor: "#5cb85c",
                      color: "white",
                      border: "none",
                      borderRadius: 4,
                      cursor: "pointer",
                    }}
                  >
                    Guardar
                  </button>
                  <button
                    onClick={cancelEdit}
                    style={{
                      padding: "6px 12px",
                      backgroundColor: "#f0ad4e",
                      color: "white",
                      border: "none",
                      borderRadius: 4,
                      cursor: "pointer",
                    }}
                  >
                    Cancelar
                  </button>
                </div>
              </>
            ) : (
              <>
                <div style={{ flexGrow: 1 }}>
                  <strong style={{ fontSize: 16 }}>{user.name}</strong>{" "}
                  <span style={{ color: "#555" }}>({user.email})</span>
                </div>
                <div>
                  <button
                    onClick={() => startEdit(user)}
                    style={{
                      marginRight: 8,
                      padding: "6px 12px",
                      backgroundColor: "#0275d8",
                      color: "white",
                      border: "none",
                      borderRadius: 4,
                      cursor: "pointer",
                    }}
                  >
                    Editar
                  </button>
                  <button
                    onClick={() => handleDelete(user.id)}
                    style={{
                      padding: "6px 12px",
                      backgroundColor: "#d9534f",
                      color: "white",
                      border: "none",
                      borderRadius: 4,
                      cursor: "pointer",
                    }}
                  >
                    Eliminar
                  </button>
                </div>
              </>
            )}
          </li>
        ))}
      </ul>
    </div>
  );
}
