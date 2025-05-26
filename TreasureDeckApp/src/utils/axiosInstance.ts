import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const instance = axios.create({
  baseURL: 'http://192.168.1.35:8000/api', 
});

instance.interceptors.request.use(
  async config => {
    const token = await AsyncStorage.getItem('token');
    console.log('Token añadido a headers:', token);

    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);


export default instance;
