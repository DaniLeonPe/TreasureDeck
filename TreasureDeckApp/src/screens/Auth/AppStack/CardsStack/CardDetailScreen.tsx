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
  CardDetail: { cardId: number; cardVersion: CardVersion };
};

export default function CardDetailScreen() {
  const route = useRoute<RouteProp<RouteParams, 'CardDetail'>>();
  const navigation = useNavigation();
  const { cardVersion } = route.params;

  console.log('min_price:', cardVersion.min_price, 'type:', typeof cardVersion.min_price);
  console.log('avg_price:', cardVersion.avg_price, 'type:', typeof cardVersion.avg_price);

  const handleAddToCollection = async () => {
  try {
    const token = await AsyncStorage.getItem('token');
    if (!token) {
      Alert.alert('No estás autenticado');
      return;
    }

    const response = await axios.post(
      `${API_BASE_URL}/v2/userCards`,
      {
        card_version_id: cardVersion.id,
        quantity: 1,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      }
    );

    Alert.alert('Carta agregada a tu colección');
    console.log(response.data);
  } catch (error: any) {
    console.error('Error al agregar la carta:', error.response?.data || error.message);
    Alert.alert('Hubo un error al agregar la carta');
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

        <TouchableOpacity style={styles.button} onPress={()=> handleAddToCollection()}>
          <Text style={styles.buttonText}>Agregar a colección</Text>
        </TouchableOpacity>

        <TouchableOpacity
          style={[styles.button, { backgroundColor: '#6c757d' }]}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.buttonText}>Volver</Text>
        </TouchableOpacity>

        
      </ScrollView>
    </View>
  );
}


const colors = {
  primary: '#c8472c',
  background: '#412a1e',
  accent: '#f8de3c',
  white: '#fefefe',
  blueLight: '#58acf4',
  blueMedium: '#105edd',
  blueDark: '#0b3075',
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.blueDark,
  },
  content: {
    alignItems: 'center',
    padding: 20,
  },
  image: {
    width: 250,
    height: 350,
    borderRadius: 12,
    marginBottom: 24,
    borderWidth: 2,
    borderColor: colors.accent,
    resizeMode: 'contain',
  },
  versionText: {
    fontSize: 22,
    fontWeight: 'bold',
    color: colors.accent,
    marginBottom: 20,
    textAlign: 'center',
  },
  priceBox: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    width: '90%',
    marginVertical: 10,
    padding: 12,
    backgroundColor: colors.background,
    borderRadius: 10,
  },
  label: {
    fontSize: 16,
    color: colors.white,
  },
  value: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.accent,
  },
  button: {
    backgroundColor: colors.blueMedium,
    paddingVertical: 14,
    paddingHorizontal: 24,
    borderRadius: 10,
    marginTop: 20,
    width: '90%',
    alignItems: 'center',
  },
  buttonText: {
    color: colors.white,
    fontWeight: 'bold',
    fontSize: 16,
  },
});
