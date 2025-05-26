import React, { useEffect, useState } from 'react';
import { API_BASE_URL } from '../../../../constants/api';

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

export default function CardsScreen() {
  const navigation = useNavigation<AuthStackNavigationProp>();

  const [cards, setCards] = useState<CardVersion[]>([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [isLoadingMore, setIsLoadingMore] = useState(false);
  const [hasMore, setHasMore] = useState(true);
  const [filterInput, setFilterInput] = useState('');
  const [filterName, setFilterName] = useState('');
  const limit = 20;

  useEffect(() => {
    fetchCards(1, filterName);
  }, [filterName]);

  const fetchCards = async (pageNumber: number, nameFilter = '') => {
    try {
      if (pageNumber === 1) setLoading(true);
      else setIsLoadingMore(true);

      const token = await AsyncStorage.getItem('token');
      const response = await axios.get(`${API_BASE_URL}/v2/cardsVersion`, {
        headers: { Authorization: `Bearer ${token}` },
        params: {
          limit,
          page: pageNumber,
          name: nameFilter || undefined,
        },
      });

      const data = response.data.data ?? response.data;
      const pagination = response.data.meta ?? {};

      if (pageNumber === 1) {
        setCards(data);
      } else {
        setCards((prev) => [...prev, ...data]);
      }

      if (pagination.current_page && pagination.last_page) {
        setHasMore(pagination.current_page < pagination.last_page);
      } else {
        setHasMore(data.length === limit);
      }

      setPage(pageNumber);
    } catch (error) {
      console.error('Error fetching cards:', error);
    } finally {
      setLoading(false);
      setIsLoadingMore(false);
    }
  };

  const onSearchPress = () => {
    setPage(1);
    setHasMore(true);
    setFilterName(filterInput);
  };

  const loadMore = () => {
    if (!isLoadingMore && hasMore) {
      fetchCards(page + 1, filterName);
    }
  };

  if (loading && page === 1) {
    return (
  <View style={[styles.container, { backgroundColor: colors.background }]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Cargando cartas...</Text>
      </View>
    );
  }

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

      <FlatList
        data={cards}
        keyExtractor={(item) => item.id.toString()}
        numColumns={3}
        contentContainerStyle={styles.flatListContent}
        renderItem={({ item }) => (
          <TouchableOpacity
            onPress={() =>
              navigation.navigate('CardDetail', { cardId: item.card_id, cardVersion: item })
            }
            style={styles.cardContainer}
            activeOpacity={0.85}
          >
            <Image source={{ uri: item.image_url }} style={styles.image} />
          </TouchableOpacity>
        )}
        onEndReached={loadMore}
        onEndReachedThreshold={0.5}
        ListFooterComponent={() =>
          isLoadingMore ? (
            <ActivityIndicator size="large" color={colors.primary} style={styles.footerLoader} />
          ) : !hasMore ? (
            <View style={styles.footer}>
              <Text style={styles.footerText}>No hay más cartas</Text>
            </View>
          ) : null
        }
        ListEmptyComponent={() => (
          <View style={styles.emptyContainer}>
            <Text style={styles.emptyText}>No se encontraron cartas</Text>
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 10,
  },
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  filters: {
    flexDirection: 'row',
    marginBottom: 10,
  },
  input: {
    flex: 1,
    height: 40,
    backgroundColor: colors.inputBackground,
    borderRadius: 5,
    borderWidth: 1,
    borderColor: colors.border,
    paddingHorizontal: 10,
    color: colors.text,
  },
  searchButton: {
    backgroundColor: colors.primary,
    justifyContent: 'center',
    paddingHorizontal: 15,
    marginLeft: 8,
    borderRadius: 5,
  },
  searchButtonText: {
    color: 'white',
    fontWeight: '600',
  },
  flatListContent: {
    paddingBottom: 20,
  },
  cardContainer: {
    flex: 1 / 3,
    margin: 4,
    aspectRatio: 0.7,
    borderRadius: 6,
    overflow: 'hidden',
    backgroundColor: 'white',
    elevation: 3,
  },
  image: {
    width: '100%',
    height: '100%',
  },
  footerLoader: {
    marginVertical: 20,
  },
  footer: {
    paddingVertical: 20,
    alignItems: 'center',
  },
  footerText: {
    color: colors.secondary,
  },
  emptyContainer: {
    marginTop: 40,
    alignItems: 'center',
  },
  emptyText: {
    color: colors.secondary,
    fontSize: 16,
  },
  loadingText: {
    marginTop: 15,
    fontSize: 16,
    color: colors.primary,
  },
});
