import React, { useEffect, useState } from 'react';
import { API_BASE_URL } from '../../../../constants/api';
import { useIsFocused } from '@react-navigation/native';

import {
  View,
  TextInput,
  Image,
  FlatList,
  StyleSheet,
  TouchableOpacity,
  ActivityIndicator,
  Text,
  Pressable,
} from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import type { RootStackParamList, CardVersion } from '../../../../navigation/AuthStack';

const colors = {
  primary: '#007bff',
  secondary: '#6c757d',
  background: '#f8f9fa',
  text: '#212529',
  inputBackground: '#fff',
  border: '#ced4da',
};

type AuthStackNavigationProp = NativeStackNavigationProp<RootStackParamList>;

type UserCard = {
  id: number;
  user_id: number;
  card_version_id: number;
  quantity: number;
};

export default function UserCollectionScreen() {
  const navigation = useNavigation<AuthStackNavigationProp>();
  const isFocused = useIsFocused();
  const [userCards, setUserCards] = useState<UserCard[]>([]);
  const [cardsDetails, setCardsDetails] = useState<CardVersion[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterInput, setFilterInput] = useState('');
  const [filterName, setFilterName] = useState('');

  const limit = 50; 

  useEffect(() => {
  if (isFocused) {
    fetchUserCards();
  }
}, [isFocused]);


  useEffect(() => {
    if (userCards.length === 0) {
      setCardsDetails([]);
      setLoading(false);
      return;
    }

    fetchCardsDetails(userCards, filterName);
  }, [userCards, filterName]);

  const fetchUserCards = async () => {
    setLoading(true);
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) throw new Error('No token found');

      const response = await axios.get(`${API_BASE_URL}/v2/userCards`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      setUserCards(response.data.data ?? response.data);
    } catch (error) {
      console.error('Error fetching userCards:', error);
    }
  };

  const fetchCardsDetails = async (userCards: UserCard[], nameFilter = '') => {
    setLoading(true);
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) throw new Error('No token found');

      const cardVersionIds = userCards.map((uc) => uc.card_version_id);
          console.log('IDs para pedir detalles:', cardVersionIds);

     
      const response = await axios.get(`${API_BASE_URL}/v2/cardsVersion`, {
        headers: { Authorization: `Bearer ${token}` },
        params: {
          ids: cardVersionIds.join(','), 
          
        },
      });
    console.log('cardsVersion response:', response.data);

      const data = response.data.data ?? response.data;

      const filteredCards = data.filter((card: CardVersion) =>
        cardVersionIds.includes(card.id)
      );

      setCardsDetails(filteredCards);
    } catch (error) {
      console.error('Error fetching card details:', error);
    } finally {
      setLoading(false);
    }
  };

  const getCardsToRender = () => {
    return cardsDetails.map((card) => {
      const userCard = userCards.find((uc) => uc.card_version_id === card.id);
      return {
        ...card,
        quantity: userCard?.quantity ?? 0,
        userCardId: userCard?.id,
      };
    });
  };

  const onSearchPress = () => {
    setFilterName(filterInput.trim());
  };

  if (loading) {
    return (
      <View style={[styles.container, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text>Cargando colección...</Text>
      </View>
    );
  }

  const cardsToRender = getCardsToRender();

  return (
    <View style={[styles.container, { backgroundColor: colors.background }]}>
      <View style={styles.filters}>
        <TextInput
          placeholder="Buscar por nombre"
          placeholderTextColor={colors.secondary}
          value={filterInput}
          onChangeText={setFilterInput}
          style={styles.input}
          autoCapitalize="none"
        />
        <Pressable onPress={onSearchPress} style={styles.searchButton}>
          <Text style={styles.searchButtonText}>Buscar</Text>
        </Pressable>
      </View>

      {cardsToRender.length === 0 ? (
        <View >
          <Text>No se encontraron cartas</Text>
        </View>
      ) : (
        <FlatList
          data={cardsToRender}
          keyExtractor={(item) => item.id.toString()}
          numColumns={3}
          contentContainerStyle={styles.flatListContent}
          renderItem={({ item }) => (
            <TouchableOpacity
              onPress={() =>
                navigation.navigate('CardDetailCollection', { cardVersion: item, cardId: item.card_id, userCardId: item.userCardId, })
              }
              style={styles.cardContainer}
              activeOpacity={0.85}
            >
              <Image source={{ uri: item.image_url }} style={styles.image} />
            </TouchableOpacity>
          )}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    padding: 16, 
    backgroundColor: colors.background,
  },
  filters: {
    flexDirection: 'row',
    marginBottom: 12,
  },
  input: {
    flex: 1,
    backgroundColor: colors.inputBackground,
    borderColor: colors.border,
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 12,
    paddingVertical: 8,
    fontSize: 16,
    color: colors.text,
  },
  searchButton: {
    backgroundColor: colors.primary,
    paddingHorizontal: 16,
    justifyContent: 'center',
    marginLeft: 8,
    borderRadius: 8,
  },
  searchButtonText: {
    color: '#fff',
    fontWeight: '600',
  },
  flatListContent: {
    paddingBottom: 16,
  },
  cardContainer: {
    flex: 1 / 3,
    aspectRatio: 0.7,
    margin: 6,
    borderRadius: 12,
    overflow: 'hidden',
    backgroundColor: '#fff',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 6,
    elevation: 4,
  },
  image: {
    width: '100%',
    height: '100%',
    borderRadius: 12,
    resizeMode: 'cover',
  },
});
