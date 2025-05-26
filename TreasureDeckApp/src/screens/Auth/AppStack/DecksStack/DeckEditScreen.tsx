import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  Button,
  FlatList,
  StyleSheet,
  TouchableOpacity,
  Image,
  Dimensions,
  ScrollView,
  Alert,
  InteractionManager,
} from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { PieChart } from 'react-native-chart-kit';
import { useNavigation } from '@react-navigation/native';
import { API_BASE_URL } from '../../../../constants/api';

type Card = {
  id: number;
  name: string;
  image_url?: string;
};

type Deck = {
  id: number;
  name: string;
  leader_card_version_id?: number;
  cards: { [cardId: number]: number };
  wins?: number;
  losses?: number;
};

export default function DeckEditScreen({ route }) {
  const navigation = useNavigation<any>();
  const { deckId } = route.params;
  const [deck, setDeck] = useState<Deck | null>(null);
  const [cards, setCards] = useState<Card[]>([]);
  const [saving, setSaving] = useState(false);

  const [victories, setVictories] = useState(0);
  const [defeats, setDefeats] = useState(0);

  useEffect(() => {
    loadDeck();
    loadCards();
  }, []);

  const loadDeck = async () => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) return;

      const res = await axios.get(`${API_BASE_URL}/v2/decks/${deckId}`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      const deckData = res.data.data;

      if (!deckData) {
        console.warn('Deck no encontrado o respuesta inesperada');
        return;
      }

      if (!deckData.cards) deckData.cards = {};
      setDeck(deckData);

      setVictories(deckData.wins || 0);
      setDefeats(deckData.losses || 0);
    } catch (error) {
      console.error('Error cargando deck:', error);
    }
  };

  const loadCards = async () => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) return;

      const res = await axios.get(`${API_BASE_URL}/v2/cardsVersion`, {
        headers: { Authorization: `Bearer ${token}` },
      });

      setCards(res.data.data);
    } catch (error) {
      console.error('Error cargando cartas:', error);
    }
  };

  const totalCards = Object.values(deck?.cards || {}).reduce((sum, qty) => sum + qty, 0);

  const handleAddCard = (cardId: number) => {
    if (!deck) return;
    const cardsInDeck = deck.cards || {};
    const currentQty = cardsInDeck[cardId] || 0;

    if (totalCards >= 51 || currentQty >= 4) return;

    setDeck({
      ...deck,
      cards: {
        ...cardsInDeck,
        [cardId]: currentQty + 1,
      },
    });
  };

  const handleRemoveCard = (cardId: number) => {
    if (!deck) return;
    const cardsInDeck = deck.cards || {};
    const currentQty = cardsInDeck[cardId] || 0;
    if (currentQty <= 0) return;

    const updated = { ...cardsInDeck };
    if (currentQty === 1) {
      delete updated[cardId];
    } else {
      updated[cardId] = currentQty - 1;
    }

    setDeck({ ...deck, cards: updated });
  };

  const updateDeckStats = (wins: number, losses: number) => {
    setVictories(wins);
    setDefeats(losses);
  };

  const handleSaveDeck = async () => {
    if (!deck) return;
    setSaving(true);

    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) {
        Alert.alert('Error', 'No hay token de autenticación');
        setSaving(false);
        return;
      }

      const headers = { Authorization: `Bearer ${token}` };

      const deckToSave = {
        ...deck,
        wins: victories,
        losses: defeats,
      };
console.log('Enviando deck actualizado:', deckToSave);

      const respuesta = await axios.put(
        `${API_BASE_URL}/v2/decks/${deck.id}`,
        {
          name: deckToSave.name,
          wins: deckToSave.wins,
          losses: deckToSave.losses,
        },
        { headers }
      );
      console.log('Respuesta del servidor:', respuesta.data);

      InteractionManager.runAfterInteractions(() => {
        navigation.navigate('Home', { screen: 'DeckList' });
      });
    } catch (error) {
      console.error('Error guardando deck:', error);
      Alert.alert('Error', 'No se pudo guardar el deck. Intenta de nuevo.');
    } finally {
      setSaving(false);
    }
  };

  const chartData = [
    {
      name: 'Victorias',
      population: victories,
      color: 'green',
      legendFontColor: '#7F7F7F',
      legendFontSize: 15,
    },
    {
      name: 'Derrotas',
      population: defeats,
      color: 'red',
      legendFontColor: '#7F7F7F',
      legendFontSize: 15,
    },
  ];

  if (!deck) return <Text>Cargando...</Text>;

  const selectedCards = cards.filter((card) => (deck.cards || {})[card.id]);

  return (
    <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
      <View style={styles.container}>
        <Text style={styles.title}>Editando: {deck.name}</Text>
        <Text style={styles.counter}>Cartas seleccionadas: {totalCards} / 51</Text>

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          key={Object.keys(deck.cards || {}).join('-')}
          contentContainerStyle={styles.selectedCardsContainer}
        >
          {selectedCards.map((item) => (
            <TouchableOpacity
              key={item.id.toString()}
              style={styles.selectedCard}
              onPress={() => handleRemoveCard(item.id)}
            >
              <Image source={{ uri: item.image_url }} style={styles.cardImageSmall} />
              <Text style={styles.cardQty}>{(deck.cards || {})[item.id]}x</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>

        <FlatList
          data={cards}
          keyExtractor={(item) => item.id.toString()}
          numColumns={3}
          scrollEnabled={false}
          renderItem={({ item }) => {
            const count = (deck.cards || {})[item.id] || 0;
            const isMax = count >= 4 || totalCards >= 51;

            return (
              <TouchableOpacity
                style={styles.cardItem}
                onPress={() => handleAddCard(item.id)}
                disabled={isMax}
              >
                <View style={{ position: 'relative' }}>
                  <Image
                    source={{ uri: item.image_url }}
                    style={[styles.cardImage, isMax && { opacity: 0.4 }]}
                  />
                  {count >= 4 && (
                    <View style={styles.maxBadge}>
                      <Text style={styles.maxBadgeText}>MAX</Text>
                    </View>
                  )}
                </View>
                <Text style={styles.cardName}>{item.name}</Text>
                <Text style={styles.cardQty}>{count}x</Text>
              </TouchableOpacity>
            );
          }}
        />

        <View style={{ marginTop: 16 }}>
          <Button title={saving ? 'Guardando...' : 'Guardar Deck'} onPress={handleSaveDeck} disabled={saving} />
        </View>

        <View style={styles.results}>
          <Text style={styles.resultsLabel}>Resultados:</Text>
          <View style={styles.buttonsRow}>
            <Button title="+ Victoria" onPress={() => setVictories(victories + 1)} />
            <Button title="+ Derrota" onPress={() => setDefeats(defeats + 1)} />
            <Button title="Resetear" onPress={() => { setVictories(0); setDefeats(0); }} />
          </View>

          <PieChart
            data={chartData}
            width={Dimensions.get('window').width - 32}
            height={220}
            chartConfig={{
              backgroundColor: '#fff',
              backgroundGradientFrom: '#fff',
              backgroundGradientTo: '#fff',
              decimalPlaces: 0,
              color: () => '#000',
              labelColor: () => '#000',
            }}
            accessor="population"
            backgroundColor="transparent"
            paddingLeft="15"
            absolute
          />
        </View>
      </View>
    </ScrollView>
  );
}
const styles = StyleSheet.create({
  container: { padding: 16, flex: 1, backgroundColor: '#fff' },
  title: { fontSize: 24, fontWeight: 'bold', marginBottom: 8 },
  counter: { fontSize: 16, marginBottom: 8 },
  selectedCardsContainer: { paddingVertical: 8 },
  selectedCard: { marginRight: 8, alignItems: 'center' },
  cardImageSmall: { width: 50, height: 70, borderRadius: 4 },
  cardItem: { flex: 1, margin: 4, alignItems: 'center' },
  cardImage: { width: 100, height: 140, borderRadius: 8 },
  maxBadge: {
    position: 'absolute',
    top: 4,
    right: 4,
    backgroundColor: 'rgba(255,0,0,0.8)',
    paddingHorizontal: 6,
    borderRadius: 4,
  },
  maxBadgeText: { color: '#fff', fontWeight: 'bold' },
  cardName: { textAlign: 'center', marginTop: 4 },
  cardQty: { position: 'absolute', bottom: 4, right: 4, color: '#fff', fontWeight: 'bold', backgroundColor: 'rgba(0,0,0,0.5)', paddingHorizontal: 4, borderRadius: 4 },
  results: { marginTop: 20 },
  resultsLabel: { fontSize: 18, fontWeight: 'bold', marginBottom: 8 },
  buttonsRow: { flexDirection: 'row', justifyContent: 'space-around', marginBottom: 12 },
});
