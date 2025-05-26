import React, { useCallback, useState } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
  Button,
  Alert,
} from 'react-native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import type { RootStackParamList } from '../../../../navigation/AuthStack';
import { API_BASE_URL } from '../../../../constants/api';

type Deck = {
  id: number;
  name: string;
  leader_card_version_id: number;
};

type DeckListScreenNavigationProp = NativeStackNavigationProp<
  RootStackParamList,
  'DeckList'
>;

export default function DeckListScreen() {
  const [decks, setDecks] = useState<Deck[]>([]);
  const navigation = useNavigation<DeckListScreenNavigationProp>();

  const fetchDecks = async () => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) return;

      const res = await axios.get(`${API_BASE_URL}/v2/decks`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      setDecks(res.data.data || res.data);
    } catch (error) {
      console.error('Error fetching decks:', error);
    }
  };

  const deleteDeck = async (deckId: number) => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) return;
      await axios.delete(`${API_BASE_URL}/v2/deckCards/byDeck/${deckId}`, { 
         headers: { Authorization: `Bearer ${token}` }
       });

      await axios.delete(`${API_BASE_URL}/v2/decks/${deckId}`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      setDecks(decks.filter((d) => d.id !== deckId));
    } catch (error) {
      console.error('Error deleting deck:', error);
      Alert.alert('Error', 'No se pudo eliminar el mazo.');
    }
  };

  useFocusEffect(
    useCallback(() => {
      fetchDecks();
    }, [])
  );

  const confirmDelete = (deckId: number) => {
    Alert.alert('¿Eliminar mazo?', 'Esta acción no se puede deshacer.', [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Eliminar',
        style: 'destructive',
        onPress: () => deleteDeck(deckId),
      },
    ]);
  };

  return (
    <View style={styles.container}>
      <Button
        title="Crear nuevo mazo"
        onPress={() => navigation.navigate('DeckCreate')}
      />
      <FlatList
        data={decks}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={styles.deckItem}>
            <TouchableOpacity
              style={styles.deckInfo}
              onPress={() =>
                navigation.navigate('DeckDetail', { deckId: item.id })
              }
            >
              <Text style={styles.deckName}>{item.name}</Text>
            </TouchableOpacity>

            <View style={styles.actions}>
              <TouchableOpacity
                onPress={() =>
                  navigation.navigate('DeckEdit', { deckId: item.id })
                }
              >
                <Text style={styles.edit}>✏️</Text>
              </TouchableOpacity>
              <TouchableOpacity onPress={() => confirmDelete(item.id)}>
                <Text style={styles.delete}>❌</Text>
              </TouchableOpacity>
            </View>
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  deckItem: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: 12,
    borderBottomWidth: 1,
    borderColor: '#ccc',
  },
  deckInfo: {
    flex: 1,
  },
  deckName: {
    fontSize: 18,
  },
  actions: {
    flexDirection: 'row',
    gap: 8,
  },
  edit: {
    fontSize: 18,
    marginRight: 8,
  },
  delete: {
    fontSize: 18,
    color: 'red',
  },
});
