import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  Button,
  StyleSheet,
  Alert,
} from 'react-native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { useNavigation } from '@react-navigation/native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import type { RootStackParamList } from '../../../../navigation/AuthStack'; 
import { API_BASE_URL } from '../../../../constants/api';
type DeckCreateScreenNavigationProp = NativeStackNavigationProp<
  RootStackParamList,
  'DeckCreate'
>;

export default function DeckCreateScreen() {
  const [name, setName] = useState('');
  const navigation = useNavigation<DeckCreateScreenNavigationProp>();

  const handleCreate = async () => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) {
        Alert.alert('No estás autenticado');
        return;
      }

      if (!name.trim()) {
        Alert.alert('El nombre no puede estar vacío');
        return;
      }

      await axios.post(
        `${API_BASE_URL}/v2/decks`,
        { name },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      Alert.alert('Mazo creado');
  navigation.navigate('Home', { screen: 'DeckList' });
    } catch (error) {
      console.error('Error creando mazo:', error);
      Alert.alert('Error al crear el mazo');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Nombre del mazo:</Text>
      <TextInput
        style={styles.input}
        value={name}
        onChangeText={setName}
        placeholder="Escribe un nombre"
      />
      <Button title="Crear" onPress={handleCreate} />
      <Button title="Cancelar" onPress={() => navigation.goBack()} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  label: { fontSize: 16, marginBottom: 8 },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    padding: 12,
    marginBottom: 16,
    borderRadius: 4,
  },
});
