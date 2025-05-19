import React, { useEffect, useState } from 'react';
import { View, Text, Image, FlatList } from 'react-native';
import axios from 'axios';

interface CardVersion {
  id: number;
  image_url: string;
  min_price: number;
  avg_price: number;
  versions: string;
}

export default function CardDetailScreen({ route }: any) {
  const { id } = route.params;
  const [cardVersions, setCardVersions] = useState<CardVersion[]>([]);

  useEffect(() => {
    (async () => {
      try {
        const res = await axios.get(`http://localhost:8000/api/cards/${id}/versions`);
        setCardVersions(res.data);
      } catch (error) {
        console.log(error);
      }
    })();
  }, [id]);

  return (
    <View style={{ flex: 1, padding: 20 }}>
      <FlatList
        data={cardVersions}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={{ marginBottom: 20 }}>
            <Image source={{ uri: item.image_url }} style={{ width: 150, height: 220 }} />
            <Text>Versión: {item.versions}</Text>
            <Text>Precio mínimo: ${item.min_price}</Text>
            <Text>Precio promedio: ${item.avg_price}</Text>
          </View>
        )}
      />
    </View>
  );
}
