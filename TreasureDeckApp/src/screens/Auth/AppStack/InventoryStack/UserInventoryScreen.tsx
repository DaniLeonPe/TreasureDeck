import React, { useEffect, useState } from 'react';
import { View, FlatList, Text } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface UserCard {
  id: number;
  quantity: number;
  card_version: {
    id: number;
    versions: string;
    image_url: string;
  };
}

export default function UserInventoryScreen() {
  const [inventory, setInventory] = useState<UserCard[]>([]);

  useEffect(() => {
    (async () => {
      try {
        const token = await AsyncStorage.getItem('token');
        const res = await axios.get('http://localhost:8000/api/user/cards', {
          headers: { Authorization: `Bearer ${token}` }
        });
        setInventory(res.data);
      } catch (error) {
        console.log(error);
      }
    })();
  }, []);

  return (
    <View style={{ flex: 1 }}>
      <FlatList
        data={inventory}
        keyExtractor={(item) => item.id.toString()}
        renderItem={({ item }) => (
          <View style={{ flexDirection: 'row', padding: 10, borderBottomWidth: 1 }}>
            <Text style={{ flex: 1 }}>{item.card_version.versions}</Text>
            <Text style={{ width: 50 }}>x{item.quantity}</Text>
          </View>
        )}
      />
    </View>
  );
}
