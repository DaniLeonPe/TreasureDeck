import React, { useState } from 'react';
import { View, TextInput, Button, Text, Alert, StyleSheet, TouchableOpacity } from 'react-native';
import axios from 'axios';
import { API_BASE_URL } from '../../constants/api';


export default function RegisterScreen({ navigation }: any) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleRegister = async () => {
  console.log('handleRegister triggered');
  try {
    console.log('Enviando datos:', { name, email, password });
    await axios.post(`${API_BASE_URL}/register`, { name, email, password });
    console.log('Registro exitoso');
    Alert.alert('Éxito', 'Registrado correctamente. Por favor verifica tu email.');
    navigation.navigate('Login');
  } catch (error: any) {
    console.log('Error caught:', error);
    Alert.alert('Error', error.response?.data?.message || error.message || 'Error al registrar');
  }
};


  return (
    <View style={styles.container}>
      <Text style={styles.title}>Crea tu cuenta</Text>
      <TextInput
        placeholder="Nombre de usuario"
        placeholderTextColor="#ccc"
        value={name}
        onChangeText={setName}
        style={styles.input}
      />
      <TextInput
        placeholder="Email"
        placeholderTextColor="#ccc"
        value={email}
        onChangeText={setEmail}
        style={styles.input}
      />
      <TextInput
        placeholder="Contraseña"
        placeholderTextColor="#ccc"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
        style={styles.input}
      />
      <TouchableOpacity style={styles.button} onPress={handleRegister}>
        <Text style={styles.buttonText}>Registrarse</Text>
      </TouchableOpacity>
      <TouchableOpacity onPress={() => navigation.goBack()}>
        <Text style={styles.link}>¿Ya tienes cuenta? Inicia sesión</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#001f3f', justifyContent: 'center', padding: 20 },
  title: { fontSize: 32, color: 'gold', marginBottom: 40, textAlign: 'center', fontWeight: 'bold' },
  input: {
    backgroundColor: '#003366',
    color: 'white',
    padding: 12,
    marginBottom: 16,
    borderRadius: 8,
    borderColor: 'gold',
    borderWidth: 1,
  },
  button: {
    backgroundColor: '#cc0000',
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: { color: 'white', fontWeight: 'bold' },
  link: { marginTop: 20, color: 'skyblue', textAlign: 'center' },
});

