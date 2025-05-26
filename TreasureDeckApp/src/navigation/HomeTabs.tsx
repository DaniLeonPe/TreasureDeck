import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import React from 'react';
import Ionicons from 'react-native-vector-icons/Ionicons';

import CardsScreen from '../screens/Auth/AppStack/CardsStack/CardsScreen';
import CollectionScreen from '../screens/Auth/AppStack/InventoryStack/CollectionScreen';
import DecksScreen from '../screens/Auth/AppStack/DecksStack/DeckListScreen';
import DeckListScreen from '../screens/Auth/AppStack/DecksStack/DeckListScreen';
import ProfileScreen from '../screens/Auth/AppStack/ProfileScreen';

export type TabParamList = {
  Cartas: undefined;
  Colección: undefined;
  DeckList: undefined;
  Perfil: undefined;
};

const Tab = createBottomTabNavigator<TabParamList>();

const colors = {
  primary: '#c8472c',
  background: '#412a1e',
  accent: '#f8de3c',
  white: '#fefefe',
  blueLight: '#58acf4',
  blueMedium: '#105edd',
  blueDark: '#0b3075',
};

export default function HomeTabs() {
  return (
    <Tab.Navigator
    id={undefined}
    screenOptions={({ route }) => ({
        headerShown: false,
        tabBarShowLabel: true,
        tabBarActiveTintColor: colors.accent,
        tabBarInactiveTintColor: colors.white,
        tabBarStyle: {
          backgroundColor: colors.blueDark,
          borderTopColor: 'transparent',
          height: 70,
          paddingBottom: 10,
          paddingTop: 10,
          shadowColor: '#000',
          shadowOffset: { width: 0, height: -2 },
          shadowOpacity: 0.1,
          shadowRadius: 6,
          elevation: 10,
        },
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: '600',
        },
        tabBarIcon: ({ color, size }) => {
          let iconName = 'apps';

          if (route.name === 'Cartas') iconName = 'albums-outline';
          else if (route.name === 'Colección') iconName = 'layers-outline';
          else if (route.name === 'DeckList') iconName = 'bookmarks-outline';
          else if (route.name === 'Perfil') iconName = 'person-circle-outline';

          return <Ionicons name={iconName} size={size} color={color} />;
        },
      })}
    >
      <Tab.Screen name="Cartas" component={CardsScreen} />
      <Tab.Screen name="Colección" component={CollectionScreen} />
      <Tab.Screen name="DeckList" component={DeckListScreen} />
      <Tab.Screen name="Perfil" component={ProfileScreen} />

    </Tab.Navigator>
  );
}
