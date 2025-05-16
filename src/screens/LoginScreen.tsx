import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet } from 'react-native';
import { StackNavigationProp } from '@react-navigation/stack';

// Define los tipos de navegación
type RootStackParamList = {
  Login: undefined;
  Register: undefined;
};

type LoginScreenNavigationProp = StackNavigationProp<RootStackParamList, 'Login'>;

type Props = {
  navigation: LoginScreenNavigationProp;
};

const LoginScreen = ({ navigation }: Props) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = () => {
    // TODO: conectar con API
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>🏴‍☠️ TreasureDeck</Text>
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
      <TouchableOpacity style={styles.button} onPress={handleLogin}>
        <Text style={styles.buttonText}>Iniciar sesión</Text>
      </TouchableOpacity>
      <TouchableOpacity onPress={() => navigation.navigate('Register')}>
        <Text style={styles.link}>¿No tienes cuenta? Regístrate</Text>
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

export default LoginScreen;
