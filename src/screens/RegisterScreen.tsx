import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet } from 'react-native';
import { StackNavigationProp } from '@react-navigation/stack';

// Definir los tipos de las rutas
type RootStackParamList = {
  Login: undefined;
  Register: undefined;
};

// Definir los tipos de navegación
type RegisterScreenNavigationProp = StackNavigationProp<RootStackParamList, 'Register'>;

type Props = {
  navigation: RegisterScreenNavigationProp;
};

const RegisterScreen = ({ navigation }: Props) => {
  const [email, setEmail] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const handleRegister = () => {
    // TODO: llamar a la API de registro
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>🗺️ Crea tu cuenta</Text>
      <TextInput
        placeholder="Nombre de usuario"
        placeholderTextColor="#ccc"
        value={username}
        onChangeText={setUsername}
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

export default RegisterScreen;
