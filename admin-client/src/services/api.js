import axios from "axios";

export const API_BASE_URL = "http://192.168.1.40:8000/api";

const apiClient = axios.create({
  baseURL: API_BASE_URL,
});

// Agrega el token automáticamente a cada request
apiClient.interceptors.request.use(config => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const fetchUsersByRole = async (roleId) => {
  const res = await apiClient.get("/v2/users");
  // Filtramos solo los usuarios con role_id === roleId
  return res.data.data.filter(user => user.role_id === roleId);
};

export const updateUser = async (id, data) => {
  return apiClient.put(`/v2/users/${id}`, data);
};

export const deleteUser = async (id) => {
  return apiClient.delete(`/v2/users/${id}`);
};
