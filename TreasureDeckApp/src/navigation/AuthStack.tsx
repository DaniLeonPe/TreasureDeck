import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import LoginScreen from '../screens/Auth/LoginScreen';
import RegisterScreen from '../screens/Auth/RegisterScreen';
import HomeTabs, { TabParamList } from './HomeTabs';
import CardDetailScreen from '../screens/Auth/AppStack/CardsStack/CardDetailScreen';
import CardDetailCollectionScreen from '../screens/Auth/AppStack/InventoryStack/CardDetailCollectionScreen';
import DeckCreateScreen from '../screens/Auth/AppStack/DecksStack/DeckCreateScreen';
import DeckListScreen from '../screens/Auth/AppStack/DecksStack/DeckListScreen';
import DeckEditScreen from '../screens/Auth/AppStack/DecksStack/DeckEditScreen';

 export type CardVersion = {
  id: number;
  card_id: number;
  versions: string;
  image_url: string;
  min_price: number;
  avg_price: number;
};

 export type RootStackParamList = {
  Login: undefined;
  Register: undefined;
  Home: { screen: keyof TabParamList } | undefined;
  CardDetail: { cardId: number; cardVersion: CardVersion };
  CreateDeck: undefined;
  CardDetailCollection: { cardId: number; cardVersion: CardVersion; userCardId: number; };
  DeckList: undefined;
  DeckCreate: undefined;
  DeckDetail: { deckId: number };
   DeckEdit: { deckId: number };

};
const Stack = createNativeStackNavigator<RootStackParamList>();

export default function AuthStack() {
  return (
    <Stack.Navigator id={undefined} screenOptions={{ headerShown: false }}>
      <Stack.Screen name="Login" component={LoginScreen} />
      <Stack.Screen name="Register" component={RegisterScreen} />
      <Stack.Screen name="Home" component={HomeTabs} />
      <Stack.Screen name="CardDetail" component={CardDetailScreen} />
      <Stack.Screen name="CardDetailCollection" component={CardDetailCollectionScreen} />
      <Stack.Screen name="DeckCreate" component={DeckCreateScreen} />
      <Stack.Screen  name="DeckList" component={DeckListScreen}/>
      <Stack.Screen  name="DeckEdit" component={DeckEditScreen}/>

    </Stack.Navigator>
  );
}
