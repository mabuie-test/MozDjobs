import 'package:flutter/material.dart';
import '../models/service.dart';
import '../widgets/service_card.dart';

class ServiceListScreen extends StatelessWidget {
  const ServiceListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final services = [
      ServiceModel(1, 'Criação de Website', 8500),
      ServiceModel(2, 'Marketing Digital', 5000),
      ServiceModel(3, 'Edição de Vídeo', 3500),
    ];

    return Scaffold(
      appBar: AppBar(title: const Text('Serviços')),
      body: ListView.builder(
        itemCount: services.length,
        itemBuilder: (_, i) => ServiceCard(service: services[i]),
      ),
    );
  }
}
