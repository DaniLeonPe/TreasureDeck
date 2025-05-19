import React from 'react';
import { View, Text, Button } from 'react-native';

export default function HomeScreen({ navigation }: any) {
  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
      <Text>Bienvenido a tu plataforma de cartas</Text>
      <Button title="Ver Cartas" onPress={() => navigation.navigate('CardsList')} />
      <Button title="Ver Inventario" onPress={() => navigation.navigate('UserInventory')} />
      <Button title="Ver Mazos" onPress={() => navigation.navigate('DecksList')} />
    </View>
  );
}
