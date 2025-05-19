import React, { useEffect, useState } from 'react';
import { View, FlatList, Text, TouchableOpacity } from 'react-native';
import axios from 'axios';

interface Card {
  id: number;
  name: string;
  expansion_name: string;
}

export default function CardsListScreen({ navigation }: any) {
  const [cards, setCards] = useState<Card[]>([]);

  useEffect(() => {
    (async () => {
      try {
        const res = await axios.get('http://localhost:8000/api/cards');
        setCards(res.data);
      } catch (error) {
        console.log(error);
      }
    })();
  }, []);

  return (
    <View style={{ flex: 1 }}>
      <FlatList
        data={cards}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <TouchableOpacity onPress={() => navigation.navigate('CardDetail', { id: item.id })}>
            <Text style={{ padding: 15, borderBottomWidth: 1 }}>
              {item.name} - {item.expansion_name}
            </Text>
          </TouchableOpacity>
        )}
      />
    </View>
  );
}
