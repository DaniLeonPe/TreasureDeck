import React from 'react';
import { API_BASE_URL } from '../../../../constants/api';

import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
  ScrollView,
  Alert,
} from 'react-native';
import { useRoute, RouteProp, useNavigation } from '@react-navigation/native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

type CardVersion = {
  id: number;
  card_id: number;
  versions: string;
  image_url: string;
  min_price: number;
  avg_price: number;
};

type RouteParams = {
  CardDetailCollection: { cardId: number; cardVersion: CardVersion; userCardId: number };
};

export default function CardDetailCollectionScreen() {
  const route = useRoute<RouteProp<RouteParams, 'CardDetailCollection'>>();
  const navigation = useNavigation();
  const { cardVersion, userCardId } = route.params;


  const handleRemoveFromCollection = async () => {
  try {
    const token = await AsyncStorage.getItem('token');
    if (!token) {
      Alert.alert('No estás autenticado');
      return;
    }

    await axios.delete(
      `${API_BASE_URL}/v2/userCards/${userCardId}`, // Usar userCardId aquí
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    Alert.alert('Carta eliminada de tu colección');
    navigation.goBack();
  } catch (error: any) {
    console.error('Error al eliminar la carta:', error.response?.data || error.message);
    Alert.alert('Hubo un error al eliminar la carta');
  }
};

  return (
    <View style={styles.container}>
      <ScrollView contentContainerStyle={styles.content}>
        <Image source={{ uri: cardVersion.image_url }} style={styles.image} />

        <Text style={styles.versionText}>Versión: {cardVersion.versions}</Text>

        <View style={styles.priceBox}>
          <Text style={styles.label}>Precio mínimo:</Text>
          <Text style={styles.value}>{Number(cardVersion.min_price).toFixed(2)}€</Text>
        </View>

        <View style={styles.priceBox}>
          <Text style={styles.label}>Precio promedio:</Text>
          <Text style={styles.value}>{Number(cardVersion.avg_price).toFixed(2)}€</Text>
        </View>

        <TouchableOpacity
          style={[styles.button, { backgroundColor: '#dc3545', marginTop: 10 }]}
          onPress={handleRemoveFromCollection}
        >
          <Text style={styles.buttonText}>Eliminar de la colección</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.button, { backgroundColor: '#6c757d', marginTop: 10 }]}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.buttonText}>Volver</Text>
        </TouchableOpacity>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  content: { padding: 20, alignItems: 'center' },
  image: { width: 250, height: 350, marginBottom: 20, borderRadius: 8 },
  versionText: { fontSize: 18, fontWeight: '600', marginBottom: 10 },
  priceBox: { flexDirection: 'row', justifyContent: 'space-between', width: '80%', marginBottom: 10 },
  label: { fontWeight: '500', fontSize: 16 },
  value: { fontSize: 16 },
  button: {
    backgroundColor: '#007bff',
    paddingVertical: 12,
    paddingHorizontal: 30,
    borderRadius: 6,
    marginTop: 10,
  },
  buttonText: { color: '#fff', fontWeight: '600', fontSize: 16, textAlign: 'center' },
});
