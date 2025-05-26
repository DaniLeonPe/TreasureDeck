import React from 'react';
import { View, Button, StyleSheet, Image } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation, NavigationProp } from '@react-navigation/native';
import { RootStackParamList } from '../../..//navigation/AuthStack'; 


const ProfileScreen = () => {
const navigation = useNavigation<NavigationProp<RootStackParamList>>();

  const handleLogout = async () => {
    await AsyncStorage.removeItem('token');
    navigation.reset({
      index: 0,
      routes: [{ name: 'Login' }],
    });
  };

  return (
    <View style={styles.container}>
       <Image 
            source={require('../../../../assets/Logo.png')} 
            style={{ width: 150, height: 150, marginBottom: 20 }} 
            resizeMode="contain"
          />
      <Button title="Cerrar sesión" onPress={handleLogout} color="#c8472c" />
    </View>
  );
};

export default ProfileScreen;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0b3075',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
});
